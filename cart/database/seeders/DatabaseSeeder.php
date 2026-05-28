<?php

namespace Database\Seeders;

use App\Models\Commerce\Brand;
use App\Models\Commerce\Category;
use App\Models\Audit\VesselType;
use App\Models\Commerce\Product;
use App\Models\Commerce\Inventory;
use App\Models\Content\ChatbotFaq;
use App\Models\User\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // // Create users
        // User::firstOrCreate([
        //     'email' => 'test@example.com'
        // ], [
        //     'name' => 'Test User',
        //     'phone' => '9876543210',
        //     'role' => 'customer',
        //     'is_active' => true,
        //     'password' => bcrypt('password')
        // ]);

        // User::firstOrCreate([
        //     'email' => 'admin@example.com'
        // ], [
        //     'name' => 'Admin User',
        //     'phone' => '9999999999',
        //     'password' => bcrypt('password'),
        //     'role' => 'admin',
        //     'is_active' => true
        // ]);

        // // Create Brands
        // $brandData = [
        //     ['name' => 'Caterpillar', 'slug' => 'caterpillar', 'description' => 'Leading manufacturer of heavy machinery'],
        //     ['name' => 'Volvo', 'slug' => 'volvo', 'description' => 'Premium heavy equipment brand'],
        //     ['name' => 'Komatsu', 'slug' => 'komatsu', 'description' => 'Japanese construction equipment leader'],
        //     ['name' => 'John Deere', 'slug' => 'john-deere', 'description' => 'Trusted agricultural equipment brand'],
        //     ['name' => 'Hitachi', 'slug' => 'hitachi', 'description' => 'Global equipment manufacturer']
        // ];
        // $brands = [];
        // foreach ($brandData as $brand) {
        //     $brands[] = Brand::create($brand);
        // }

        // // Create Categories
        // $categoryData = [
        //     ['name' => 'Engine Parts', 'slug' => 'engine-parts', 'description' => 'Engine components and accessories'],
        //     ['name' => 'Hydraulic Components', 'slug' => 'hydraulic-components', 'description' => 'Hydraulic systems and parts'],
        //     ['name' => 'Transmission Parts', 'slug' => 'transmission-parts', 'description' => 'Transmission and gearbox parts'],
        //     ['name' => 'Electrical Components', 'slug' => 'electrical-components', 'description' => 'Electrical systems and components'],
        //     ['name' => 'Undercarriage Parts', 'slug' => 'undercarriage-parts', 'description' => 'Track and undercarriage parts']
        // ];
        // $categories = [];
        // foreach ($categoryData as $category) {
        //     $categories[] = Category::create($category);
        // }

        // // Create Vessel Types
        // $vesselTypeData = [
        //     ['name' => 'General Cargo Ship', 'code' => 'GENERAL_CARGO'],
        //     ['name' => 'Container Ship', 'code' => 'CONTAINER_SHIP'],
        //     ['name' => 'Bulk Carrier', 'code' => 'BULK_CARRIER'],
        //     ['name' => 'Tanker', 'code' => 'TANKER'],
        //     ['name' => 'Heavy Equipment', 'code' => 'HEAVY_EQUIPMENT']
        // ];
        // $vesselTypes = [];
        // foreach ($vesselTypeData as $vesselType) {
        //     $vesselTypes[] = VesselType::create($vesselType);
        // }

        // // Create Products with Inventory
        // $products = [
        //     [
        //         'name' => 'Engine Cylinder Head',
        //         'sku' => 'ECH-001',
        //         'part_number' => 'CAT-ECH-001',
        //         'brand_id' => 1,
        //         'category_id' => 1,
        //         'vessel_type_id' => 1,
        //         'description' => 'Genuine Caterpillar engine cylinder head, compatible with marine engines',
        //         'price' => 85000,
        //         'cost' => 65000,
        //         'specifications' => ['material' => 'Cast Iron', 'weight' => '45kg'],
        //         'images' => ['https://images.unsplash.com/photo-1621905252507-b35492cc74b4?w=600&q=80', 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&q=80', 'https://images.unsplash.com/photo-1567789884554-0b844b597180?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Marine Fuel Filter',
        //         'sku' => 'MFF-002',
        //         'part_number' => 'VOL-MFF-002',
        //         'brand_id' => 2,
        //         'category_id' => 1,
        //         'vessel_type_id' => 2,
        //         'description' => 'High-performance fuel filter for container ships',
        //         'price' => 12500,
        //         'cost' => 9000,
        //         'specifications' => ['capacity' => '50 micron', 'flow_rate' => '100gph'],
        //         'images' => ['https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=600&q=80', 'https://images.unsplash.com/photo-1537944434965-cf4679d1a598?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Hydraulic Pump Assembly',
        //         'sku' => 'HPA-003',
        //         'part_number' => 'KOM-HPA-003',
        //         'brand_id' => 3,
        //         'category_id' => 2,
        //         'vessel_type_id' => 3,
        //         'description' => 'Complete hydraulic pump assembly for bulk carriers',
        //         'price' => 250000,
        //         'cost' => 180000,
        //         'specifications' => ['pressure' => '280 bar', 'displacement' => '40cc'],
        //         'images' => ['https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&q=80', 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80', 'https://images.unsplash.com/photo-1562408590-e32931084e23?w=600&q=80', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Alternator 100A',
        //         'sku' => 'ALT-004',
        //         'part_number' => 'JD-ALT-004',
        //         'brand_id' => 4,
        //         'category_id' => 4,
        //         'vessel_type_id' => 4,
        //         'description' => 'Marine-grade alternator for tanker vessels',
        //         'price' => 45000,
        //         'cost' => 32000,
        //         'specifications' => ['output' => '100A', 'voltage' => '24V'],
        //         'images' => ['https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&q=80', 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80', 'https://images.unsplash.com/photo-1537944434965-cf4679d1a598?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Track Link Assembly',
        //         'sku' => 'TLA-005',
        //         'part_number' => 'HIT-TLA-005',
        //         'brand_id' => 5,
        //         'category_id' => 5,
        //         'vessel_type_id' => 5,
        //         'description' => 'Heavy-duty undercarriage track link for excavators',
        //         'price' => 15000,
        //         'cost' => 10000,
        //         'specifications' => ['pitch' => '100mm', 'material' => 'Steel'],
        //         'images' => ['https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&q=80', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Turbocharger Assembly',
        //         'sku' => 'TUR-006',
        //         'part_number' => 'CAT-TUR-006',
        //         'brand_id' => 1,
        //         'category_id' => 1,
        //         'vessel_type_id' => 1,
        //         'description' => 'Genuine turbo for marine diesel engines',
        //         'price' => 180000,
        //         'cost' => 125000,
        //         'specifications' => ['boost' => '2.5 bar', 'type' => 'Fixed'],
        //         'images' => ['https://images.unsplash.com/photo-1621905252507-b35492cc74b4?w=600&q=80', 'https://images.unsplash.com/photo-1567789884554-0b844b597180?w=600&q=80', 'https://images.unsplash.com/photo-1504917595217-d4dc5ebe6122?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Oil Cooler Unit',
        //         'sku' => 'OCU-007',
        //         'part_number' => 'VOL-OCU-007',
        //         'brand_id' => 2,
        //         'category_id' => 2,
        //         'vessel_type_id' => 2,
        //         'description' => 'Plate frame oil cooler for ship engines',
        //         'price' => 95000,
        //         'cost' => 68000,
        //         'specifications' => ['capacity' => '50 kW', 'flow' => '80 m3/h'],
        //         'images' => ['https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&q=80', 'https://images.unsplash.com/photo-1562408590-e32931084e23?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Gearbox Bearing',
        //         'sku' => 'GBR-008',
        //         'part_number' => 'KOM-GBR-008',
        //         'brand_id' => 3,
        //         'category_id' => 3,
        //         'vessel_type_id' => 3,
        //         'description' => 'Precision roller bearing for ship transmission',
        //         'price' => 32000,
        //         'cost' => 22000,
        //         'specifications' => ['bore' => '50mm', 'type' => 'Roller'],
        //         'images' => ['https://images.unsplash.com/photo-1486262715619-67b85e0b08d3?w=600&q=80', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&q=80', 'https://images.unsplash.com/photo-1582735689369-4fe89db7114c?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Water Pump Assembly',
        //         'sku' => 'WPA-009',
        //         'part_number' => 'JD-WPA-009',
        //         'brand_id' => 4,
        //         'category_id' => 1,
        //         'vessel_type_id' => 4,
        //         'description' => 'Centrifugal cooling water pump for marine engines',
        //         'price' => 28000,
        //         'cost' => 18000,
        //         'specifications' => ['flow' => '120 m3/h', 'pressure' => '3 bar'],
        //         'images' => ['https://images.unsplash.com/photo-1513828583688-c52646db42da?w=600&q=80', 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80'],
        //     ],
        //     [
        //         'name' => 'Electrical Control Panel',
        //         'sku' => 'ECP-010',
        //         'part_number' => 'HIT-ECP-010',
        //         'brand_id' => 5,
        //         'category_id' => 4,
        //         'vessel_type_id' => 5,
        //         'description' => 'Complete electrical control panel for heavy machinery',
        //         'price' => 125000,
        //         'cost' => 85000,
        //         'specifications' => ['voltage' => '415V', 'phase' => '3-phase'],
        //         'images' => ['https://images.unsplash.com/photo-1518770660439-4636190af475?w=600&q=80', 'https://images.unsplash.com/photo-1537944434965-cf4679d1a598?w=600&q=80', 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=600&q=80', 'https://images.unsplash.com/photo-1562408590-e32931084e23?w=600&q=80'],
        //     ]
        // ];

        // foreach ($products as $productData) {
        //     $product = Product::create($productData);
            
        //     // Create inventory for each product
        //     $warehouseQty = rand(20, 100);
        //     $reservedQty = rand(0, 10);
        //     Inventory::create([
        //         'product_id' => $product->id,
        //         'warehouse_qty' => $warehouseQty,
        //         'reserved_qty' => $reservedQty,
        //         'available_qty' => $warehouseQty - $reservedQty
        //     ]);
        // }

        // // Create FAQ entries
        // $faqData = [
        //     [
        //         'question' => 'What is your shipping policy?',
        //         'answer' => 'We ship all orders within 2-3 business days. Free shipping on orders above ₹50,000.',
        //         'category' => 'Shipping'
        //     ],
        //     [
        //         'question' => 'Do you offer bulk discounts?',
        //         'answer' => 'Yes! We offer 10-15% discounts on bulk orders. Contact our sales team for details.',
        //         'category' => 'Pricing'
        //     ],
        //     [
        //         'question' => 'How long do spare parts typically last?',
        //         'answer' => 'Our genuine parts are designed to last as long as OEM parts, typically 3-5 years depending on usage.',
        //         'category' => 'Products'
        //     ],
        //     [
        //         'question' => 'Can I return a part if it doesn\'t fit?',
        //         'answer' => 'Yes, we offer 30-day returns on all products. The item must be unused and in original packaging.',
        //         'category' => 'Returns'
        //     ],
        //     [
        //         'question' => 'What payment methods do you accept?',
        //         'answer' => 'We accept bank transfers, UPI, credit/debit cards, and corporate checks.',
        //         'category' => 'Payment'
        //     ]
        // ];
        // foreach ($faqData as $faq) {
        //     ChatbotFaq::create($faq);
        // }

        $this->call(DownloadableProductsSeeder::class);
    }
}
