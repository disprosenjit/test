<?php

namespace App\Http\Controllers\Admin;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class PropertyController extends Controller
{
    /**
     * Display a listing of properties.
     */
    public function index(Request $request): View
    {
        $query = Property::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('admin.properties.index', compact('properties'));
    }

    /**
     * Show the form for creating a new property.
     */
    public function create(): View
    {
        return view('admin.properties.create');
    }

    /**
     * Store a newly created property.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:residential,commercial,land',
            'location' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area' => 'required|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
            'hide_agent_info' => 'boolean',
            'agent_name' => 'nullable|string|max:255',
            'agent_contact' => 'nullable|string|max:20',
        ]);

        Property::create($validated);

        return redirect()->route('admin.properties.index')->with('success', 'Property created successfully.');
    }

    /**
     * Show the form for editing a property.
     */
    public function edit(Property $property): View
    {
        $property->load('images');
        return view('admin.properties.edit', compact('property'));
    }

    /**
     * Update the specified property.
     */
    public function update(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:residential,commercial,land',
            'location' => 'required|string|max:255',
            'country' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'area' => 'required|numeric|min:0',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'parking_spaces' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
            'hide_agent_info' => 'boolean',
            'agent_name' => 'nullable|string|max:255',
            'agent_contact' => 'nullable|string|max:20',
        ]);

        $property->update($validated);

        return redirect()->route('admin.properties.index')->with('success', 'Property updated successfully.');
    }

    /**
     * Delete the specified property.
     */
    public function destroy(Property $property): RedirectResponse
    {
        foreach ($property->images as $image) {
            $path = public_path($image->image_path);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $property->delete();
        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully.');
    }

    public function uploadImages(Request $request, Property $property): RedirectResponse
    {
        $request->validate([
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ]);

        $maxOrder = $property->images()->max('order') ?? 0;

        foreach ($request->file('images') as $file) {
            $maxOrder++;
            $filename = uniqid('prop_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/properties'), $filename);

            $property->images()->create([
                'image_path' => 'images/properties/' . $filename,
                'alt_text' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'order' => $maxOrder,
            ]);
        }

        return redirect()->route('admin.properties.edit', $property)->with('success', count($request->file('images')) . ' image(s) uploaded.');
    }

    public function deleteImage(Property $property, PropertyImage $image): RedirectResponse
    {
        if ($image->property_id !== $property->id) {
            abort(404);
        }

        $path = public_path($image->image_path);
        if (File::exists($path)) {
            File::delete($path);
        }

        $image->delete();

        return redirect()->route('admin.properties.edit', $property)->with('success', 'Image deleted.');
    }
}
