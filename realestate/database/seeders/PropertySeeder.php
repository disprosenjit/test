<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $properties = [
            [
                'title' => 'Beautiful Downtown Apartment',
                'description' => 'Modern apartment in the heart of downtown with stunning city views, full amenities, and close proximity to restaurants and shopping centers.',
                'price' => 350000,
                'type' => 'residential',
                'location' => 'Downtown',
                'address' => '123 Main Street, Downtown District',
                'area' => 1500,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'parking_spaces' => 2,
                'is_featured' => true,
                'is_available' => true,
                'agent_name' => 'John Smith',
                'agent_contact' => '+1 (555) 123-4567',
            ],
            [
                'title' => 'Modern Office Complex',
                'description' => 'Prime commercial space perfect for corporate offices. Features high-speed internet, professional workspace, parking, and modern amenities.',
                'price' => 750000,
                'type' => 'commercial',
                'location' => 'Business District',
                'address' => '456 Commerce Avenue, Business Hub',
                'area' => 5000,
                'bedrooms' => null,
                'bathrooms' => 4,
                'parking_spaces' => 15,
                'is_featured' => true,
                'is_available' => true,
                'agent_name' => 'Sarah Johnson',
                'agent_contact' => '+1 (555) 234-5678',
            ],
            [
                'title' => 'Spacious Suburban Home',
                'description' => 'Beautiful family home in a quiet suburban neighborhood with large yard, modern kitchen, and excellent schools nearby.',
                'price' => 425000,
                'type' => 'residential',
                'location' => 'Suburban Area',
                'address' => '789 Oak Lane, Quiet Neighborhood',
                'area' => 2500,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'parking_spaces' => 3,
                'is_featured' => true,
                'is_available' => true,
                'agent_name' => 'Michael Brown',
                'agent_contact' => '+1 (555) 345-6789',
            ],
            [
                'title' => 'Prime Development Land',
                'description' => 'Excellent investment opportunity. Zoned for mixed-use development with great potential for residential or commercial projects.',
                'price' => 500000,
                'type' => 'land',
                'location' => 'Emerging District',
                'address' => '321 Future Development Way',
                'area' => 10000,
                'bedrooms' => null,
                'bathrooms' => null,
                'parking_spaces' => null,
                'is_featured' => true,
                'is_available' => true,
                'agent_name' => 'John Smith',
                'agent_contact' => '+1 (555) 123-4567',
            ],
            [
                'title' => 'Luxury Penthouse',
                'description' => 'Stunning penthouse with panoramic views, premium finishes, private balcony, and exclusive building amenities.',
                'price' => 890000,
                'type' => 'residential',
                'location' => 'Premium District',
                'address' => '999 Luxury Heights, Downtown',
                'area' => 3200,
                'bedrooms' => 3,
                'bathrooms' => 3,
                'parking_spaces' => 3,
                'is_featured' => true,
                'is_available' => true,
                'agent_name' => 'Sarah Johnson',
                'agent_contact' => '+1 (555) 234-5678',
            ],
            [
                'title' => 'Cozy Studio Apartment',
                'description' => 'Perfect starter home or investment property. Recently renovated with modern appliances and neutral decor.',
                'price' => 180000,
                'type' => 'residential',
                'location' => 'Central District',
                'address' => '456 Central Park Avenue',
                'area' => 650,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'parking_spaces' => 1,
                'is_featured' => false,
                'is_available' => true,
                'agent_name' => 'Michael Brown',
                'agent_contact' => '+1 (555) 345-6789',
            ],
            [
                'title' => 'Retail Shop Space',
                'description' => 'Excellent retail location with high foot traffic. Ideal for boutique, cafe, or any retail business.',
                'price' => 300000,
                'type' => 'commercial',
                'location' => 'Shopping District',
                'address' => '555 Retail Plaza',
                'area' => 2000,
                'bedrooms' => null,
                'bathrooms' => 2,
                'parking_spaces' => 8,
                'is_featured' => false,
                'is_available' => true,
                'agent_name' => 'John Smith',
                'agent_contact' => '+1 (555) 123-4567',
            ],
            [
                'title' => 'Waterfront Property',
                'description' => 'Stunning waterfront residence with direct beach access, modern architecture, and breathtaking water views.',
                'price' => 1200000,
                'type' => 'residential',
                'location' => 'Waterfront',
                'address' => '100 Ocean Drive, Beach Front',
                'area' => 4000,
                'bedrooms' => 5,
                'bathrooms' => 4,
                'parking_spaces' => 4,
                'is_featured' => true,
                'is_available' => true,
                'agent_name' => 'Sarah Johnson',
                'agent_contact' => '+1 (555) 234-5678',
            ],
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
