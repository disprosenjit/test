<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Build location hierarchy data for cascading dropdowns.
     */
    private function locationData(): array
    {
        $rows = Property::where('is_available', true)
            ->whereNotNull('country')
            ->select('country', 'state', 'city')
            ->distinct()
            ->orderBy('country')
            ->orderBy('state')
            ->orderBy('city')
            ->get();

        $countries = $rows->pluck('country')->unique()->values();

        $hierarchy = [];
        foreach ($rows as $row) {
            if (!$row->country) continue;
            $hierarchy[$row->country][$row->state ?? ''][] = $row->city;
        }

        return compact('countries', 'hierarchy');
    }

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

        ['countries' => $countries, 'hierarchy' => $hierarchy] = $this->locationData();

        return view('properties.index', compact('properties', 'countries', 'hierarchy'));
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
     * Filter properties by type, location, and price.
     */
    public function filter(): View
    {
        $type     = request('type');
        $country  = request('country');
        $state    = request('state');
        $city     = request('city');
        $minPrice = request('min_price');
        $maxPrice = request('max_price');

        $query = Property::where('is_available', true);

        if ($type)     { $query->where('type', $type); }
        if ($country)  { $query->where('country', $country); }
        if ($state)    { $query->where('state', $state); }
        if ($city)     { $query->where('city', $city); }
        if ($minPrice) { $query->where('price', '>=', $minPrice); }
        if ($maxPrice) { $query->where('price', '<=', $maxPrice); }

        $properties = $query->with('images')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends(request()->query());

        ['countries' => $countries, 'hierarchy' => $hierarchy] = $this->locationData();

        return view('properties.index', compact(
            'properties', 'countries', 'hierarchy',
            'type', 'country', 'state', 'city', 'minPrice', 'maxPrice'
        ));
    }
}
