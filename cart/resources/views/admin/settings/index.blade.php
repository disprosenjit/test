@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Settings</h1>
    <p class="text-gray-600 mt-1">Manage store and system settings</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Settings Menu -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <nav class="flex flex-col">
                <a href="#general" onclick="switchTab('general')" class="tab-link active px-4 py-3 border-l-4 border-blue-600 bg-blue-50 font-semibold text-blue-600">
                    <i class="fas fa-cog mr-2"></i>General
                </a>
                <a href="#store" onclick="switchTab('store')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-store mr-2"></i>Store Info
                </a>
                <a href="#branding" onclick="switchTab('branding')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-palette mr-2"></i>Branding
                </a>
                <a href="#theme" onclick="switchTab('theme')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-moon mr-2"></i>Theme Colors
                </a>
                <a href="#system" onclick="switchTab('system')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-sliders-h mr-2"></i>System
                </a>
                <a href="#notifications" onclick="switchTab('notifications')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-bell mr-2"></i>Notifications
                </a>
                <a href="#footer" onclick="switchTab('footer')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-shoe-prints mr-2"></i>Footer
                </a>
                <a href="#security" onclick="switchTab('security')" class="tab-link px-4 py-3 border-l-4 border-transparent hover:bg-gray-50">
                    <i class="fas fa-lock mr-2"></i>Security
                </a>
            </nav>
        </div>
    </div>

    <!-- Settings Content -->
    <div class="lg:col-span-3">
        <form method="POST" action="/admin/settings" class="space-y-6" enctype="multipart/form-data">
            @csrf

            <!-- General Settings -->
            <div id="general-tab" class="settings-tab bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">General Settings</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Store Name *</label>
                        <input type="text" name="store_name" value="{{ old('store_name', $settings->store_name ?? 'Ship Spare Parts') }}" required
                               class="w-full px-4 py-2 border rounded-lg @error('store_name') border-red-500 @endif" />
                        @error('store_name')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Email *</label>
                            <input type="email" name="store_email" value="{{ old('store_email', $settings->store_email ?? 'support@shipparts.com') }}" required
                                   class="w-full px-4 py-2 border rounded-lg @error('store_email') border-red-500 @endif" />
                            @error('store_email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">Phone</label>
                            <input type="tel" name="store_phone" value="{{ old('store_phone', $settings->store_phone ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('store_phone') border-red-500 @endif" />
                            @error('store_phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Timezone</label>
                        <select name="timezone" class="w-full px-4 py-2 border rounded-lg">
                            <option value="UTC" {{ old('timezone', $settings->timezone ?? 'UTC') === 'UTC' ? 'selected' : '' }}>UTC</option>
                            <option value="Asia/Kolkata" {{ old('timezone', $settings->timezone) === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                            <option value="America/New_York" {{ old('timezone', $settings->timezone) === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                            <option value="Europe/London" {{ old('timezone', $settings->timezone) === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Currency</label>
                        <select name="currency" class="w-full px-4 py-2 border rounded-lg">
                            <option value="INR" {{ old('currency', $settings->currency ?? 'USD') === 'INR' ? 'selected' : '' }}>₹ INR (Indian Rupee)</option>
                            <option value="USD" {{ old('currency', $settings->currency ?? 'USD') === 'USD' ? 'selected' : '' }}>$ USD (US Dollar)</option>
                            <option value="EUR" {{ old('currency', $settings->currency) === 'EUR' ? 'selected' : '' }}>€ EUR (Euro)</option>
                            <option value="GBP" {{ old('currency', $settings->currency) === 'GBP' ? 'selected' : '' }}>£ GBP (British Pound)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-2">Items Per Page</label>
                        <input type="number" name="items_per_page" value="{{ old('items_per_page', $settings->items_per_page ?? 20) }}" min="5" max="100"
                               class="w-full px-4 py-2 border rounded-lg @error('items_per_page') border-red-500 @endif" />
                        @error('items_per_page')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Store Info -->
            <div id="store-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Store Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Address</label>
                        <input type="text" name="store_address" value="{{ old('store_address', $settings->store_address ?? '') }}"
                               class="w-full px-4 py-2 border rounded-lg @error('store_address') border-red-500 @endif"
                               placeholder="Street address" />
                        @error('store_address')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">City</label>
                            <input type="text" name="store_city" value="{{ old('store_city', $settings->store_city ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('store_city') border-red-500 @endif" />
                            @error('store_city')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">State</label>
                            <input type="text" name="store_state" value="{{ old('store_state', $settings->store_state ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('store_state') border-red-500 @endif" />
                            @error('store_state')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">ZIP Code</label>
                            <input type="text" name="store_zip" value="{{ old('store_zip', $settings->store_zip ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('store_zip') border-red-500 @endif" />
                            @error('store_zip')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">Country</label>
                            <input type="text" name="store_country" value="{{ old('store_country', $settings->store_country ?? 'India') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('store_country') border-red-500 @endif" />
                            @error('store_country')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2">Business Registration Number</label>
                            <input type="text" name="business_registration" value="{{ old('business_registration', $settings->business_registration ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('business_registration') border-red-500 @endif" />
                            @error('business_registration')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-2">Tax ID / GSTIN</label>
                            <input type="text" name="tax_id" value="{{ old('tax_id', $settings->tax_id ?? '') }}"
                                   class="w-full px-4 py-2 border rounded-lg @error('tax_id') border-red-500 @endif" />
                            @error('tax_id')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding -->
            <div id="branding-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Branding</h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-3">Logo</label>
                        <div class="flex items-start gap-6">
                            <div>
                                @if($settings->logo_path)
                                    <div class="mb-3">
                                        <img src="{{ $settings->getLogoUrl() }}" alt="Current Logo" class="h-20 bg-gray-100 p-2 rounded">
                                        <p class="text-xs text-gray-500 mt-1">Current logo</p>
                                    </div>
                                @endif
                                <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    @error('logo') file:bg-red-50 file:text-red-700 @endif" />
                                <p class="text-xs text-gray-500 mt-2">Recommended: 200x50px, Max 2MB (PNG, JPG, GIF, SVG)</p>
                            </div>
                        </div>
                        @error('logo')
                            <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="border-t pt-6">
                        <label class="block text-sm font-semibold mb-3">Favicon</label>
                        <div class="flex items-start gap-6">
                            <div>
                                @if($settings->favicon_path)
                                    <div class="mb-3">
                                        <img src="{{ $settings->getFaviconUrl() }}" alt="Current Favicon" class="h-10 w-10 bg-gray-100 p-1 rounded">
                                        <p class="text-xs text-gray-500 mt-1">Current favicon</p>
                                    </div>
                                @endif
                                <input type="file" name="favicon" accept="image/*" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    @error('favicon') file:bg-red-50 file:text-red-700 @endif" />
                                <p class="text-xs text-gray-500 mt-2">Recommended: 32x32px or 64x64px, Max 512KB (PNG, JPG, ICO)</p>
                            </div>
                        </div>
                        @error('favicon')
                            <p class="text-red-600 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Theme Colors -->
            <div id="theme-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Theme & Dark Mode Colors</h2>
                
                <div class="space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-900">Customize the colors used throughout your frontend. Choose colors using the color picker or enter hex color codes.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Light Theme Colors -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-4">Light Mode Colors</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Primary Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="primary_color" value="{{ $settings->primary_color ?? '#2563eb' }}"
                                               class="h-12 w-16 border rounded cursor-pointer" />
                                        <input type="text" name="primary_color" id="primary_color_text" value="{{ $settings->primary_color ?? '#2563eb' }}"
                                               class="flex-1 px-3 py-2 border rounded-lg text-sm font-mono
                                               @error('primary_color') border-red-500 @endif" />
                                    </div>
                                    @error('primary_color')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Secondary Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="secondary_color" value="{{ $settings->secondary_color ?? '#1e40af' }}"
                                               class="h-12 w-16 border rounded cursor-pointer" />
                                        <input type="text" name="secondary_color" id="secondary_color_text" value="{{ $settings->secondary_color ?? '#1e40af' }}"
                                               class="flex-1 px-3 py-2 border rounded-lg text-sm font-mono
                                               @error('secondary_color') border-red-500 @endif" />
                                    </div>
                                    @error('secondary_color')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Accent Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="accent_color" value="{{ $settings->accent_color ?? '#3b82f6' }}"
                                               class="h-12 w-16 border rounded cursor-pointer" />
                                        <input type="text" name="accent_color" id="accent_color_text" value="{{ $settings->accent_color ?? '#3b82f6' }}"
                                               class="flex-1 px-3 py-2 border rounded-lg text-sm font-mono
                                               @error('accent_color') border-red-500 @endif" />
                                    </div>
                                    @error('accent_color')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dark Mode Colors -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-4">Dark Mode Colors</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Background Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="dark_mode_bg" value="{{ $settings->dark_mode_bg ?? '#1f2937' }}"
                                               class="h-12 w-16 border rounded cursor-pointer" />
                                        <input type="text" name="dark_mode_bg" id="dark_mode_bg_text" value="{{ $settings->dark_mode_bg ?? '#1f2937' }}"
                                               class="flex-1 px-3 py-2 border rounded-lg text-sm font-mono
                                               @error('dark_mode_bg') border-red-500 @endif" />
                                    </div>
                                    @error('dark_mode_bg')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Text Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="dark_mode_text" value="{{ $settings->dark_mode_text ?? '#f3f4f6' }}"
                                               class="h-12 w-16 border rounded cursor-pointer" />
                                        <input type="text" name="dark_mode_text" id="dark_mode_text_text" value="{{ $settings->dark_mode_text ?? '#f3f4f6' }}"
                                               class="flex-1 px-3 py-2 border rounded-lg text-sm font-mono
                                               @error('dark_mode_text') border-red-500 @endif" />
                                    </div>
                                    @error('dark_mode_text')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-2">Accent Color</label>
                                    <div class="flex items-center gap-3">
                                        <input type="color" id="dark_mode_accent" value="{{ $settings->dark_mode_accent ?? '#60a5fa' }}"
                                               class="h-12 w-16 border rounded cursor-pointer" />
                                        <input type="text" name="dark_mode_accent" id="dark_mode_accent_text" value="{{ $settings->dark_mode_accent ?? '#60a5fa' }}"
                                               class="flex-1 px-3 py-2 border rounded-lg text-sm font-mono
                                               @error('dark_mode_accent') border-red-500 @endif" />
                                    </div>
                                    @error('dark_mode_accent')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="border-t pt-4">
                                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                        <input type="checkbox" name="enable_dark_mode" value="1" {{ $settings->enable_dark_mode ? 'checked' : '' }} class="w-4 h-4 rounded">
                                        <div>
                                            <p class="font-semibold">Enable Dark Mode</p>
                                            <p class="text-sm text-gray-600">Allow users to switch to dark mode</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Settings -->
            <div id="system-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">System Settings</h2>
                
                <div class="space-y-4">
                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="enable_api" value="1" {{ old('enable_api', $settings->enable_api ?? false) ? 'checked' : '' }} class="w-4 h-4 rounded">
                        <div>
                            <p class="font-semibold">Enable API</p>
                            <p class="text-sm text-gray-600">Allow API access for third-party integrations</p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="enable_notifications" value="1" {{ old('enable_notifications', $settings->enable_notifications ?? true) ? 'checked' : '' }} class="w-4 h-4 rounded">
                        <div>
                            <p class="font-semibold">Enable System Notifications</p>
                            <p class="text-sm text-gray-600">Send notifications for important events</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Notifications -->
            <div id="notifications-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Notification Preferences</h2>
                
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <p class="font-semibold">New Orders</p>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <p class="font-semibold">Payment Received</p>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <p class="font-semibold">Low Stock Alert</p>
                    </label>

                    <label class="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" checked class="w-4 h-4 rounded">
                        <p class="font-semibold">Daily Summary</p>
                    </label>
                </div>
            </div>

            <!-- Footer -->
            <div id="footer-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Footer Settings</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold mb-2">Contact Description</label>
                        <textarea name="footer_contact_description" rows="3"
                                  class="w-full px-4 py-2 border rounded-lg @error('footer_contact_description') border-red-500 @endif"
                                  placeholder="Enter the contact description that will appear in the footer">{{ old('footer_contact_description', $settings->footer_contact_description ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">This description appears in the Contact section of the footer (max 500 characters)</p>
                        @error('footer_contact_description')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Security -->
            <div id="security-tab" class="settings-tab hidden bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-bold mb-4">Security Settings</h2>
                
                <div class="space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-900 mb-3">For security reasons, you can update your password from your profile settings.</p>
                        <a href="/admin/profile" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            <i class="fas fa-user mr-2"></i>Go to Profile
                        </a>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-bold text-gray-900 mb-2">Session Security</h3>
                        <p class="text-sm text-gray-600 mb-3">Sessions are automatically terminated after 30 minutes of inactivity for security.</p>
                        <p class="text-xs text-gray-500">Last login: {{ Auth::user()->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-bold text-red-800 mb-2">Please fix the following errors:</h4>
                    <ul class="text-red-700 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-green-800 font-semibold">✓ {{ session('success') }}</p>
                </div>
            @endif

            <div class="flex gap-3 pt-4 border-t">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                    <i class="fas fa-save mr-2"></i>Save Settings
                </button>
                <a href="/admin" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-2 rounded font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tab) {
    // Hide all tabs
    document.querySelectorAll('.settings-tab').forEach(el => el.classList.add('hidden'));
    
    // Remove active state from all links
    document.querySelectorAll('.tab-link').forEach(el => {
        el.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
        el.classList.add('border-transparent');
    });
    
    // Show selected tab
    const tabEl = document.getElementById(tab + '-tab');
    if (tabEl) {
        tabEl.classList.remove('hidden');
    }
    
    // Set active state
    event.target.closest('.tab-link').classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');
    event.target.closest('.tab-link').classList.remove('border-transparent');
}

// Sync color picker and text input
document.addEventListener('DOMContentLoaded', function() {
    const colorPairs = [
        { picker: 'primary_color', text: 'primary_color_text' },
        { picker: 'secondary_color', text: 'secondary_color_text' },
        { picker: 'accent_color', text: 'accent_color_text' },
        { picker: 'dark_mode_bg', text: 'dark_mode_bg_text' },
        { picker: 'dark_mode_text', text: 'dark_mode_text_text' },
        { picker: 'dark_mode_accent', text: 'dark_mode_accent_text' },
    ];
    
    colorPairs.forEach(pair => {
        const colorPicker = document.getElementById(pair.picker);
        const textInput = document.getElementById(pair.text);
        
        if (colorPicker && textInput) {
            // Update text input when color picker changes
            colorPicker.addEventListener('input', function() {
                textInput.value = this.value;
            });
            
            // Update color picker when text input changes
            textInput.addEventListener('input', function() {
                if (/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(this.value)) {
                    colorPicker.value = this.value;
                }
            });
        }
    });
});
</script>
@endsection
