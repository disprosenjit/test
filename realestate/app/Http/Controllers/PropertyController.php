<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index(): View
    {
        $properties = Property::with('images')
            ->where('is_available', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('properties.index', compact('properties'));
    }

    /**
     * Display the specified property.
     */
    public function show(Property $property): View
    {
        $property->load('images');
        return view('properties.show', compact('property'));
    }

    /**
     * Filter properties by type and location
     */
    public function filter(): View
    {
        $type = request('type');
        $location = request('location');
        $minPrice = request('min_price');
        $maxPrice = request('max_price');

        $query = Property::where('is_available', true);

        if ($type) {
            $query->where('type', $type);
        }

        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        $properties = $query->with('images')->paginate(12);

        return view('properties.index', compact('properties'));
    }
}
