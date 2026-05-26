<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = AppSettings::getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_email' => ['required', 'email'],
            'store_phone' => ['nullable', 'string', 'max:20'],
            'store_address' => ['nullable', 'string'],
            'store_city' => ['nullable', 'string'],
            'store_state' => ['nullable', 'string'],
            'store_zip' => ['nullable', 'string'],
            'store_country' => ['nullable', 'string'],
            'business_registration' => ['nullable', 'string'],
            'tax_id' => ['nullable', 'string'],
            'currency' => ['nullable', 'string'],
            'timezone' => ['nullable', 'string'],
            'items_per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
            'enable_notifications' => ['nullable', 'boolean'],
            'enable_api' => ['nullable', 'boolean'],
            'footer_contact_description' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,ico', 'max:512'],
            'primary_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'secondary_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'accent_color' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'dark_mode_bg' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'dark_mode_text' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'dark_mode_accent' => ['nullable', 'regex:/^#(?:[0-9a-fA-F]{3}){1,2}$/'],
            'enable_dark_mode' => ['nullable', 'boolean'],
        ]);

        // Convert checkboxes to boolean
        $validated['enable_notifications'] = $request->has('enable_notifications');
        $validated['enable_api'] = $request->has('enable_api');
        $validated['enable_dark_mode'] = $request->has('enable_dark_mode');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $settings = AppSettings::getSettings();
            // Delete old logo if exists
            if ($settings->logo_path && Storage::disk('public')->exists($settings->logo_path)) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            // Store new logo
            $logoPath = $request->file('logo')->store('assets/logos', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $settings = AppSettings::getSettings();
            // Delete old favicon if exists
            if ($settings->favicon_path && Storage::disk('public')->exists($settings->favicon_path)) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            // Store new favicon
            $faviconPath = $request->file('favicon')->store('assets/favicons', 'public');
            $validated['favicon_path'] = $faviconPath;
        }

        // Get or create settings and update
        $settings = AppSettings::getSettings();
        $settings->update($validated);

        return redirect('/admin/settings')
            ->with('success', 'Settings updated successfully');
    }
}
