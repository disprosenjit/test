<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User\Address;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->get();

        return response()->json($addresses);
    }

    public function show(Request $request, int $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        return response()->json($address);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:billing,shipping,both',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:100',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $address = $request->user()->addresses()->create($request->validated());

        return response()->json($address, 201);
    }

    public function update(Request $request, int $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);

        $request->validate([
            'type' => 'required|in:billing,shipping,both',
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'company_name' => 'nullable|string|max:100',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $address->update($request->validated());

        return response()->json($address);
    }

    public function destroy(Request $request, int $id)
    {
        $address = $request->user()->addresses()->findOrFail($id);
        $address->delete();

        return response()->json(['message' => 'Address deleted']);
    }

    public function setDefault(Request $request, int $id, string $type)
    {
        $request->validate([
            'type' => 'required|in:billing,shipping,both',
        ]);

        // Reset other defaults
        $request->user()->addresses()
            ->whereIn('type', ['billing', 'both'])
            ->update(['is_default' => false]);

        $address = $request->user()->addresses()->findOrFail($id);
        $address->update(['is_default' => true]);

        return response()->json($address);
    }
}
