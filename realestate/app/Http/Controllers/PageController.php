<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Show the home page.
     */
    public function home(): View
    {
        $featuredProperties = Property::with('images')
            ->where('is_available', true)
            ->where('is_featured', true)
            ->take(6)
            ->get();

        $latestProperties = Property::with('images')
            ->where('is_available', true)
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('home', compact('featuredProperties', 'latestProperties'));
    }

    /**
     * Show the about page.
     */
    public function about(): View
    {
        return view('about');
    }

    /**
     * Show the investors page.
     */
    public function investors(): View
    {
        return view('investors.index');
    }
}
