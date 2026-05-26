<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Product;
use App\Models\Commerce\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminInventoryController extends Controller
{
    /**
     * Display all inventory
     */
    public function index(Request $request)
    {
        $query = Inventory::with('product');

        // Filter by stock status
        if ($request->stock_status === 'low') {
            $query->where('warehouse_qty', '<', 10);
        } elseif ($request->stock_status === 'out') {
            $query->where('warehouse_qty', 0);
        }

        // Search by SKU or product name
        if ($request->search) {
            $query->whereHas('product', function ($q) {
                $q->where('sku', 'like', '%' . request('search') . '%')
                  ->orWhere('name', 'like', '%' . request('search') . '%');
            });
        }

        $inventory = $query->paginate(20);
        
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Inventory::sum('warehouse_qty'),
            'low_stock' => Inventory::where('warehouse_qty', '>', 0)->where('warehouse_qty', '<', 10)->count(),
            'out_of_stock' => Inventory::where('warehouse_qty', 0)->count(),
        ];

        return view('admin.inventory.index', compact('inventory', 'stats'));
    }

    /**
     * Show edit form for inventory
     */
    public function edit(Inventory $inventory)
    {
        $inventory->load('product.category', 'product.brand');
        return view('admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update stock quantity
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'warehouse_qty' => 'required|integer|min:0',
            'reason' => 'nullable|string',
            'action_type' => 'nullable|in:set,add,subtract'
        ]);

        $oldQuantity = $inventory->warehouse_qty;
        $inputQty = $validated['warehouse_qty'];
        $actionType = $validated['action_type'] ?? 'set';

        // Calculate new quantity based on action type
        if ($actionType === 'add') {
            $newQuantity = $oldQuantity + $inputQty;
        } elseif ($actionType === 'subtract') {
            $newQuantity = max(0, $oldQuantity - $inputQty);
        } else {
            $newQuantity = $inputQty;
        }

        $inventory->update(['warehouse_qty' => $newQuantity]);

        // Log the change
        \Log::info('Inventory updated', [
            'product_id' => $inventory->product_id,
            'old_qty' => $oldQuantity,
            'new_qty' => $newQuantity,
            'action' => $actionType,
            'reason' => $validated['reason'] ?? 'Manual update',
            'updated_by' => auth()->id()
        ]);

        return redirect('/admin/inventory')
            ->with('success', "Stock updated from $oldQuantity to $newQuantity" . ($validated['reason'] ? " - {$validated['reason']}" : ''));
    }

    /**
     * Restock product
     */
    public function restock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'supplier' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0'
        ]);

        $oldQuantity = $inventory->warehouse_qty;
        $inventory->update([
            'warehouse_qty' => $oldQuantity + $validated['quantity']
        ]);

        return redirect('/admin/inventory')
            ->with('success', 'Restocked ' . $validated['quantity'] . ' units');
    }

    /**
     * Get low stock alert
     */
    public function lowStockAlert()
    {
        $lowStockProducts = Inventory::where('warehouse_qty', '<', 10)
            ->where('warehouse_qty', '>', 0)
            ->with('product')
            ->orderBy('warehouse_qty')
            ->get();

        $outOfStockProducts = Inventory::where('warehouse_qty', 0)
            ->with('product')
            ->get();

        return view('admin.inventory.alerts', compact('lowStockProducts', 'outOfStockProducts'));
    }

    /**
     * Export inventory report
     */
    public function export(Request $request)
    {
        $inventory = Inventory::with('product')->get();

        $filename = 'inventory_report_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://memory', 'w');

        fputcsv($handle, ['SKU', 'Product Name', 'Stock Quantity', 'Reserved', 'Cost Price', 'Sale Price']);

        foreach ($inventory as $item) {
            fputcsv($handle, [
                $item->product->sku,
                $item->product->name,
                $item->warehouse_qty,
                $item->reserved_qty,
                $item->product->cost,
                $item->product->price
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }
}
