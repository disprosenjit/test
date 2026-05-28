<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\View\View;

class MapController extends Controller
{
    public function index(): View
    {
        $base = Property::where('is_available', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        // Country-level aggregates
        $countries = (clone $base)
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(*) as count, AVG(latitude) as lat, AVG(longitude) as lng')
            ->groupBy('country')
            ->get();

        // State-level aggregates
        $states = (clone $base)
            ->whereNotNull('country')
            ->whereNotNull('state')
            ->selectRaw('country, state, COUNT(*) as count, AVG(latitude) as lat, AVG(longitude) as lng')
            ->groupBy('country', 'state')
            ->get();

        // City-level aggregates
        $cities = (clone $base)
            ->whereNotNull('country')
            ->whereNotNull('state')
            ->whereNotNull('city')
            ->selectRaw('country, state, city, COUNT(*) as count, AVG(latitude) as lat, AVG(longitude) as lng')
            ->groupBy('country', 'state', 'city')
            ->get();

        // Individual property markers
        $properties = (clone $base)
            ->select('id', 'title', 'price', 'type', 'city', 'state', 'country', 'latitude', 'longitude', 'is_featured')
            ->get();

        return view('map', compact('countries', 'states', 'cities', 'properties'));
    }
}
