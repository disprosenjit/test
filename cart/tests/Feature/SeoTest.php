<?php

namespace Tests\Feature;

use App\Models\Commerce\Product;
use App\Models\Content\Page;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SeoTest extends TestCase
{
    use DatabaseTransactions;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create or get an admin user
        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );
    }

    /** @test */
    public function it_can_save_seo_fields_on_product_creation_and_update()
    {
        $this->actingAs($this->adminUser);

        // Define product data with SEO parameters
        $productData = [
            'name' => 'SEO Test Engine Part',
            'sku' => 'SEO-TEST-SKU-123',
            'part_number' => 'SEO-TEST-PART-123',
            'description' => 'Test engine description',
            'price' => 1250.00,
            'cost' => 800.00,
            'brand_id' => 1,
            'category_id' => 1,
            'is_active' => 1,
            'meta_title' => 'Custom SEO Part Title',
            'meta_description' => 'Custom SEO meta description for testing.',
            'meta_keywords' => 'seo, test, engine, spare part',
        ];

        // 1. Test creation
        $response = $this->post('/admin/products', $productData);

        $product = Product::where('sku', 'SEO-TEST-SKU-123')->first();
        $this->assertNotNull($product);
        $this->assertEquals('Custom SEO Part Title', $product->meta_title);
        $this->assertEquals('Custom SEO meta description for testing.', $product->meta_description);
        $this->assertEquals('seo, test, engine, spare part', $product->meta_keywords);

        // 2. Test update
        $updatedData = array_merge($productData, [
            'name' => 'SEO Test Engine Part Updated',
            'meta_title' => 'Updated Custom SEO Part Title',
            'meta_description' => 'Updated Custom SEO meta description.',
            'meta_keywords' => 'seo, test, engine, updated',
        ]);

        $response = $this->put("/admin/products/{$product->id}", $updatedData);

        $product->refresh();
        $this->assertEquals('Updated Custom SEO Part Title', $product->meta_title);
        $this->assertEquals('Updated Custom SEO meta description.', $product->meta_description);
        $this->assertEquals('seo, test, engine, updated', $product->meta_keywords);
    }

    /** @test */
    public function it_can_save_seo_fields_on_page_creation_and_update()
    {
        $this->actingAs($this->adminUser);

        // Define page data with SEO parameters
        $pageData = [
            'title' => 'SEO Test Page Title',
            'content' => '<p>This is a page for testing SEO parameters.</p>',
            'is_active' => 1,
            'meta_title' => 'SEO Custom Page Title',
            'meta_description' => 'SEO Custom Page Meta Description.',
            'meta_keywords' => 'seo, page, test, policy',
        ];

        // 1. Test creation
        $response = $this->post('/admin/pages', $pageData);

        $page = Page::where('title', 'SEO Test Page Title')->first();
        $this->assertNotNull($page);
        $this->assertEquals('SEO Custom Page Title', $page->meta_title);
        $this->assertEquals('SEO Custom Page Meta Description.', $page->meta_description);
        $this->assertEquals('seo, page, test, policy', $page->meta_keywords);

        // 2. Test update
        $updatedData = array_merge($pageData, [
            'title' => 'SEO Test Page Title Updated',
            'meta_title' => 'Updated SEO Page Title',
            'meta_description' => 'Updated SEO Page Meta Description.',
            'meta_keywords' => 'seo, page, updated',
        ]);

        $response = $this->put("/admin/pages/{$page->slug}", $updatedData);

        $page->refresh();
        $this->assertEquals('Updated SEO Page Title', $page->meta_title);
        $this->assertEquals('Updated SEO Page Meta Description.', $page->meta_description);
        $this->assertEquals('seo, page, updated', $page->meta_keywords);
    }

    /** @test */
    public function it_renders_seo_meta_tags_on_product_detail_page()
    {
        // Get or create product
        $product = Product::firstOrCreate(
            ['sku' => 'SEO-RENDER-SKU-999'],
            [
                'name' => 'SEO Render Engine Part',
                'part_number' => 'SEO-RENDER-PART-999',
                'description' => 'Detailed description for testing rendering.',
                'price' => 500.00,
                'cost' => 300.00,
                'brand_id' => 1,
                'category_id' => 1,
                'is_active' => 1,
                'meta_title' => 'Meta Rendered Title',
                'meta_description' => 'Meta Rendered Description.',
                'meta_keywords' => 'rendered, engine, parts',
            ]
        );

        $response = $this->get("/products/{$product->id}");

        $response->assertStatus(200);
        $response->assertSee('Meta Rendered Title');
        $response->assertSee('name="description" content="Meta Rendered Description."', false);
        $response->assertSee('name="keywords" content="rendered, engine, parts"', false);
    }

    /** @test */
    public function it_renders_seo_meta_tags_on_cms_page()
    {
        // Get or create page
        $page = Page::firstOrCreate(
            ['slug' => 'seo-render-page-slug'],
            [
                'title' => 'SEO Render Page Title',
                'content' => '<p>Page content for rendering test.</p>',
                'is_active' => 1,
                'meta_title' => 'Meta Page Rendered Title',
                'meta_description' => 'Meta Page Rendered Description.',
                'meta_keywords' => 'rendered, page, tags',
            ]
        );

        $response = $this->get("/pages/{$page->slug}");

        $response->assertStatus(200);
        $response->assertSee('Meta Page Rendered Title');
        $response->assertSee('name="description" content="Meta Page Rendered Description."', false);
        $response->assertSee('name="keywords" content="rendered, page, tags"', false);
    }

    /** @test */
    public function it_can_export_orders_without_404_error()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/admin/orders/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertSee('Items');
    }

    /** @test */
    public function it_can_list_customers_as_admin()
    {
        $this->actingAs($this->adminUser);

        // Create a customer user
        $customer = User::firstOrCreate(
            ['email' => 'customer_test@example.com'],
            [
                'name' => 'John Customer',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $response = $this->get('/admin/customers');

        $response->assertStatus(200);
        $response->assertSee('John Customer');
        $response->assertSee('customer_test@example.com');
    }

    /** @test */
    public function it_can_update_customer_details()
    {
        $this->actingAs($this->adminUser);

        $customer = User::firstOrCreate(
            ['email' => 'update_customer@example.com'],
            [
                'name' => 'Initial Customer Name',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $response = $this->put("/admin/customers/{$customer->id}", [
            'name' => 'Updated Customer Name',
            'email' => 'update_customer_new@example.com',
            'phone' => '1234567890',
        ]);

        $response->assertRedirect('/admin/customers');
        $customer->refresh();
        $this->assertEquals('Updated Customer Name', $customer->name);
        $this->assertEquals('update_customer_new@example.com', $customer->email);
        $this->assertEquals('1234567890', $customer->phone);
    }

    /** @test */
    public function it_can_delete_customer()
    {
        $this->actingAs($this->adminUser);

        $customer = User::create([
            'name' => 'Delete Customer',
            'email' => 'delete_customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $response = $this->delete("/admin/customers/{$customer->id}");

        $response->assertRedirect('/admin/customers');
        $this->assertNull(User::find($customer->id));
    }

    /** @test */
    public function it_shows_paypal_api_credentials_fields_on_payment_methods_page()
    {
        $this->actingAs($this->adminUser);

        // Find or seed PayPal payment method
        $paypalMethod = \App\Models\Payment\PaymentMethod::firstOrCreate(
            ['key' => 'paypal'],
            [
                'name' => 'PayPal',
                'description' => 'Accept payments via PayPal checkout',
                'type' => 'wallet',
                'is_enabled' => false,
                'is_default' => false,
                'sort_order' => 2,
            ]
        );

        $response = $this->get("/admin/payment-methods/{$paypalMethod->id}");

        $response->assertStatus(200);
        $response->assertSee('PayPal API Credentials');
        $response->assertSee('Client ID');
        $response->assertSee('Client Secret');
    }

    /** @test */
    public function it_can_update_paypal_credentials()
    {
        $this->actingAs($this->adminUser);

        // Find or seed PayPal payment method
        $paypalMethod = \App\Models\Payment\PaymentMethod::firstOrCreate(
            ['key' => 'paypal'],
            [
                'name' => 'PayPal',
                'description' => 'Accept payments via PayPal checkout',
                'type' => 'wallet',
                'is_enabled' => false,
                'is_default' => false,
                'sort_order' => 2,
            ]
        );

        $response = $this->put("/admin/payment-methods/{$paypalMethod->id}", [
            'sort_order' => 5,
            'description' => 'Updated description',
            'client_id' => 'test-client-id',
            'client_secret' => 'test-client-secret',
            'environment' => 'sandbox',
            'currency' => 'USD',
        ]);

        $response->assertRedirect("/admin/payment-methods/{$paypalMethod->id}");

        $paypalMethod->refresh();
        $this->assertEquals(5, $paypalMethod->sort_order);
        $this->assertEquals('Updated description', $paypalMethod->description);

        $settings = \Plugins\PaymentPayPal\Models\PaypalSetting::first();
        $this->assertNotNull($settings);
        $this->assertEquals('test-client-id', $settings->client_id);
        $this->assertEquals('test-client-secret', $settings->client_secret);
        $this->assertEquals('sandbox', $settings->environment);
        $this->assertEquals('USD', $settings->currency);
    }

    /** @test */
    public function it_shows_stripe_api_credentials_fields_on_payment_methods_page()
    {
        $this->actingAs($this->adminUser);

        // Find or seed Stripe payment method
        $stripeMethod = \App\Models\Payment\PaymentMethod::firstOrCreate(
            ['key' => 'stripe'],
            [
                'name' => 'Credit/Debit Card (Stripe)',
                'description' => 'Accept payments via Stripe',
                'type' => 'card',
                'is_enabled' => true,
                'is_default' => true,
                'sort_order' => 1,
            ]
        );

        $response = $this->get("/admin/payment-methods/{$stripeMethod->id}");

        $response->assertStatus(200);
        $response->assertSee('Stripe API Credentials');
        $response->assertSee('Publishable Key');
        $response->assertSee('Secret Key');
    }

    /** @test */
    public function it_can_update_stripe_credentials()
    {
        $this->actingAs($this->adminUser);

        // Find or seed Stripe payment method
        $stripeMethod = \App\Models\Payment\PaymentMethod::firstOrCreate(
            ['key' => 'stripe'],
            [
                'name' => 'Credit/Debit Card (Stripe)',
                'description' => 'Accept payments via Stripe',
                'type' => 'card',
                'is_enabled' => true,
                'is_default' => true,
                'sort_order' => 1,
            ]
        );

        $response = $this->put("/admin/payment-methods/{$stripeMethod->id}", [
            'sort_order' => 3,
            'description' => 'New Stripe description',
            'publishable_key' => 'stripe-pub-key',
            'secret_key' => 'stripe-secret-key',
            'webhook_secret' => 'stripe-webhook-key',
            'environment' => 'test',
            'currency' => 'EUR',
        ]);

        $response->assertRedirect("/admin/payment-methods/{$stripeMethod->id}");

        $stripeMethod->refresh();
        $this->assertEquals(3, $stripeMethod->sort_order);
        $this->assertEquals('New Stripe description', $stripeMethod->description);

        $settings = \Plugins\PaymentStripe\Models\StripeSetting::first();
        $this->assertNotNull($settings);
        $this->assertEquals('stripe-pub-key', $settings->publishable_key);
        $this->assertEquals('stripe-secret-key', $settings->secret_key);
        $this->assertEquals('stripe-webhook-key', $settings->webhook_secret);
        $this->assertEquals('test', $settings->environment);
        $this->assertEquals('EUR', $settings->currency);
    }
}
