Multilingual support is in place for the public site in English, French, German, and Arabic, with session-persisted language switching and RTL layout for Arabic. The locale is now applied centrally through app/Http/Middleware/SetLocale.php, registered in bootstrap/app.php, exposed to views via app/Providers/AppServiceProvider.php, and switchable through the new route in routes/web.php. The shared public and guest layouts now set dir="rtl" for Arabic, include a language switcher, and apply the translated navigation/footer labels in resources/views/layouts/app.blade.php and resources/views/layouts/guest.blade.php.

I also localized the main public pages and added translation catalogs in lang/fr.json, lang/de.json, and lang/ar.json. Validation passed: php artisan route:list, php artisan view:cache, and php artisan config:cache all succeeded, and a targeted scan found no remaining hardcoded English in the public pages I updated.

If you want, the natural next step is:

Extend the same localization pass to the auth/profile/admin screens so the entire back office is multilingual too.
Add locale-aware URLs like /fr/... and /ar/... instead of session-based switching.
