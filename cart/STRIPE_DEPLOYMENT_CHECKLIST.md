# Stripe Integration - Deployment Checklist

## Pre-Deployment

### Environment Setup
- [ ] Copy `.env.example` to `.env`
- [ ] Add Stripe keys to `.env`:
  ```env
  STRIPE_PUBLISHABLE_KEY=pk_test_...
  STRIPE_SECRET_KEY=sk_test_...
  STRIPE_CURRENCY=INR
  STRIPE_WEBHOOK_SECRET=whsec_...
  ```

### Database
- [ ] Run migrations:
  ```bash
  php artisan migrate
  ```
- [ ] Verify tables created:
  ```bash
  php artisan tinker
  >>> DB::table('stripe_customers')->count()
  >>> DB::table('stripe_cards')->count()
  >>> DB::table('stripe_charges')->count()
  ```

### Cache & Assets
- [ ] Clear cache:
  ```bash
  php artisan cache:clear
  php artisan config:cache
  ```
- [ ] Rebuild assets (if needed):
  ```bash
  npm run build
  ```

## Testing Checklist

### Unit Tests
```bash
# Test Stripe payment flow
php artisan test --filter StripePaymentTest

# Test models
php artisan test --filter StripeCustomerTest
php artisan test --filter StripeCardTest
php artisan test --filter StripeChargeTest
```

### Manual Testing
- [ ] User registration (create test user)
- [ ] Product browsing and cart functionality
- [ ] Checkout page loads correctly
- [ ] Address selection works
- [ ] Stripe payment method appears in options
- [ ] Click "Place Order" with Stripe → redirects to card form
- [ ] Card form displays correctly
- [ ] Enter test card: 4242 4242 4242 4242
- [ ] Payment processes successfully
- [ ] Success page displays order confirmation
- [ ] Check database for records in:
  - stripe_customers
  - stripe_cards
  - stripe_charges
- [ ] Try failed card: 4000 0000 0000 0002
- [ ] Verify error handling works
- [ ] Save card option works
- [ ] Select saved card on next purchase
- [ ] Card deletion works

### Browser Testing
- [ ] Desktop (Chrome, Firefox, Safari)
- [ ] Mobile (iOS Safari, Android Chrome)
- [ ] Tablet views
- [ ] Card form validation messages appear
- [ ] Error messages are user-friendly
- [ ] Success redirects work properly

## Production Deployment

### Before Going Live
- [ ] Switch Stripe keys from test to live:
  ```env
  STRIPE_PUBLISHABLE_KEY=pk_live_...
  STRIPE_SECRET_KEY=sk_live_...
  ```
- [ ] Update environment to production
- [ ] Set up Stripe webhooks (optional but recommended):
  - Endpoint: `https://yourdomain.com/webhook/stripe`
  - Events: payment_intent.succeeded, payment_intent.payment_failed
- [ ] Configure SSL/TLS certificate
- [ ] Test with real card (if allowed by payment processor)

### Monitoring
- [ ] Set up error logging and alerting
- [ ] Monitor Stripe dashboard for failed transactions
- [ ] Check database for any orphaned records
- [ ] Monitor application logs for Stripe errors

## File Locations

### Migrations
- `/database/migrations/2026_05_25_000001_create_stripe_customers_table.php`
- `/database/migrations/2026_05_25_000002_create_stripe_cards_table.php`
- `/database/migrations/2026_05_25_000003_create_stripe_charges_table.php`

### Models
- `/app/Models/Payment/StripeCustomer.php`
- `/app/Models/Payment/StripeCard.php`
- `/app/Models/Payment/StripeCharge.php`

### Service
- `/app/Services/StripePaymentService.php`

### Controller
- `/app/Http/Controllers/Web/StripePaymentController.php`

### Views
- `/resources/views/frontend/checkout/stripe-card-form.blade.php`
- `/resources/views/frontend/checkout/stripe-success.blade.php`
- `/resources/views/frontend/checkout/stripe-failed.blade.php`

### Routes
- `/routes/web.php` (Stripe payment routes added)

### Configuration
- `/config/services.php` (Stripe config added)

### Updated Files
- `/resources/views/frontend/checkout/index.blade.php` (Stripe handling added)

## Troubleshooting

### "Stripe secret key not configured"
- Verify `STRIPE_SECRET_KEY` is set in `.env`
- Run `php artisan config:cache` to refresh cache

### "Order created but order ID is missing"
- Check API response for errors
- Verify order creation endpoint working
- Check database for pending orders

### "Stripe customer creation failed"
- Verify API key has customer creation permission
- Check Stripe dashboard for API key status
- Review Stripe error logs

### "Payment processing fails silently"
- Check Laravel logs: `storage/logs/laravel.log`
- Check Stripe dashboard for failed charges
- Verify card is valid for test mode

### "Card form doesn't load"
- Verify route is accessible
- Check order_id parameter passed correctly
- Verify authentication middleware working
- Check browser console for JavaScript errors

## Performance Optimization

### Caching
```php
// Cache customer lookups
Cache::remember("stripe_customer_{$userId}", 3600, function() {
    return StripeCustomer::where('user_id', $userId)->first();
});
```

### Database Optimization
```bash
# Add indexes if not present
php artisan make:migration add_stripe_indexes --table=stripe_customers
```

### API Rate Limiting
Consider implementing rate limiting on payment routes:
```php
Route::post('/checkout/stripe/process', [...])
    ->middleware('throttle:10,1'); // 10 requests per minute
```

## Security Audit

- [ ] All payment routes require authentication
- [ ] Card data never logged
- [ ] CSRF tokens verified on all POST requests
- [ ] SQL injection protection (using Eloquent ORM)
- [ ] XSS protection (Blade escaping)
- [ ] Proper error messages (no sensitive data exposed)
- [ ] Stripe API key in environment only
- [ ] Publishable key can be safely exposed
- [ ] Payment records properly encrypted in transit

## Backup & Recovery

- [ ] Regular database backups include stripe_* tables
- [ ] Document backup/restore procedures
- [ ] Test restore process with stripe data
- [ ] Document Stripe data reconciliation process

## Support & Resources

### Documentation Files
- `STRIPE_INTEGRATION_GUIDE.md` - Comprehensive integration guide
- `README.md` - Project overview (update with Stripe feature)

### External Resources
- [Stripe API Documentation](https://stripe.com/docs/api)
- [Stripe Testing Guide](https://stripe.com/docs/testing)
- [Laravel Cashier (optional payment wrapper)](https://laravel.com/docs/billing)

## Rollback Plan

If issues occur in production:

1. Disable Stripe payment method in admin panel
2. Set `STRIPE_SECRET_KEY` to test key
3. Restart application
4. Investigate errors in logs
5. Deploy fix
6. Re-enable with live key

## Notes

- Test card `4242 4242 4242 4242` works only in test mode
- Payment intent automatically confirms in test mode
- 3D Secure not required for test cards
- Webhook handling is optional but recommended for production
- Email notifications should be sent on successful payment
