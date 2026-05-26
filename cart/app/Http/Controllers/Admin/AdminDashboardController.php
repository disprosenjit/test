<?php

namespace App\Http\Controllers\Admin;

use App\Models\Commerce\Order;
use App\Models\Commerce\Product;
use App\Models\Payment\Payment;
use App\Models\User\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $thisYear = Carbon::now()->startOfYear();

        // Key metrics
        $metrics = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'low_stock_products' => Product::where('stock_qty', '<', 10)->count(),
        ];

        // Revenue metrics
        $revenue = [
            'today' => Order::where('status', '!=', 'cancelled')
                ->whereDate('created_at', $today)
                ->sum('total'),
            'this_month' => Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$thisMonth->copy()->startOfMonth(), $thisMonth->copy()->endOfMonth()])
                ->sum('total'),
            'this_year' => Order::where('status', '!=', 'cancelled')
                ->whereBetween('created_at', [$thisYear->copy()->startOfYear(), $thisYear->copy()->endOfYear()])
                ->sum('total'),
        ];

        // Order metrics
        $orders = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Payment metrics
        $payments = [
            'total_received' => Payment::where('status', 'approved')->sum('amount'),
            'pending' => Payment::where('status', 'pending')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->limit(10)
            ->get();

        // Top products
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->limit(5)
            ->get();

        // Daily revenue chart data
        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total) as revenue')
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->get();

        return view('admin.dashboard', compact(
            'metrics',
            'revenue',
            'orders',
            'payments',
            'recentOrders',
            'topProducts',
            'dailyRevenue'
        ));
    }

    /**
     * Display analytics
     */
    public function analytics(Request $request)
    {
        $period = $request->period ?? 'month';
        $from = $this->getPeriodStart($period);
        $to = now();

        // Sales analytics
        $salesData = Order::where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw('DATE(created_at) as date')
            ->selectRaw('COUNT(*) as orders')
            ->selectRaw('SUM(total) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Product analytics
        $productData = Product::selectRaw('name')
            ->selectRaw('view_count')
            ->selectRaw('(SELECT COUNT(*) FROM order_items WHERE product_id = products.id) as sold')
            ->orderBy('sold', 'desc')
            ->limit(10)
            ->get();

        // Customer analytics
        $customerData = [
            'total' => User::count(),
            'new_this_period' => User::whereBetween('created_at', [$from, $to])->count(),
            'repeat_customers' => User::whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('orders')
                    ->groupBy('user_id')
                    ->havingRaw('COUNT(*) > 1');
            })->count(),
        ];

        return view('admin.analytics', compact('salesData', 'productData', 'customerData', 'period'));
    }

    /**
     * Get period start date
     */
    private function getPeriodStart($period)
    {
        return match($period) {
            'week' => now()->subDays(7),
            'month' => now()->subDays(30),
            'quarter' => now()->subMonths(3),
            'year' => now()->subYear(),
            default => now()->subDays(30)
        };
    }
}
