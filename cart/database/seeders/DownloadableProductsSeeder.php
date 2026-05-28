<?php

namespace Database\Seeders;

use App\Models\Commerce\Brand;
use App\Models\Commerce\Category;
use App\Models\Commerce\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DownloadableProductsSeeder extends Seeder
{
    public function run(): void
    {
        $brand = Brand::query()->first();
        $category = Category::query()->first();

        if (!$brand || !$category) {
            return;
        }

        $disk = Storage::disk('local');

        $files = [
            [
                'path' => 'products/downloads/marine-engine-manual.pdf',
                'name' => 'Marine Engine Maintenance Manual.pdf',
                'mime' => 'application/pdf',
                'content' => "%PDF-1.1\n1 0 obj<< /Type /Catalog /Pages 2 0 R >>endobj\n2 0 obj<< /Type /Pages /Kids [3 0 R] /Count 1 >>endobj\n3 0 obj<< /Type /Page /Parent 2 0 R /MediaBox [0 0 300 144] /Contents 4 0 R /Resources<< /Font<< /F1 5 0 R >> >> >>endobj\n4 0 obj<< /Length 64 >>stream\nBT /F1 18 Tf 24 92 Td (Marine Engine Maintenance Manual Sample) Tj ET\nendstream\nendobj\n5 0 obj<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>endobj\nxref\n0 6\n0000000000 65535 f \n0000000010 00000 n \n0000000063 00000 n \n0000000122 00000 n \n0000000248 00000 n \n0000000362 00000 n \ntrailer<< /Root 1 0 R /Size 6 >>\nstartxref\n432\n%%EOF",
            ],
            [
                'path' => 'products/downloads/inspection-checklist.csv',
                'name' => 'Inspection Checklist.csv',
                'mime' => 'text/csv',
                'content' => "Item,Status,Notes\nFuel line,Pending,Verify for leaks\nCooling system,Pending,Check pressure\nAlternator,Pending,Confirm output\n",
            ],
            [
                'path' => 'products/downloads/parts-reference-guide.txt',
                'name' => 'Parts Reference Guide.txt',
                'mime' => 'text/plain',
                'content' => "Sample downloadable content for reference guides and product documentation.\n",
            ],
        ];

        foreach ($files as $index => $file) {
            if (!$disk->exists($file['path'])) {
                $disk->put($file['path'], $file['content']);
            }

            Product::updateOrCreate(
                ['sku' => 'DWL-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT)],
                [
                    'name' => match ($index) {
                        0 => 'Marine Engine Maintenance Manual',
                        1 => 'Inspection Checklist Pack',
                        default => 'Parts Reference Guide',
                    },
                    'part_number' => 'DIGI-' . str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'brand_id' => $brand->id,
                    'category_id' => $category->id,
                    'description' => 'Downloadable product delivered instantly after payment approval.',
                    'product_type' => 'downloadable',
                    'price' => [1499, 799, 999][$index],
                    'cost' => [200, 100, 150][$index],
                    'stock_qty' => 0,
                    'specifications' => [
                        'format' => strtoupper(pathinfo($file['name'], PATHINFO_EXTENSION)),
                        'delivery' => 'Instant download after purchase',
                    ],
                    'images' => [],
                    'is_active' => true,
                    'download_file_path' => $file['path'],
                    'download_file_name' => $file['name'],
                    'download_file_mime_type' => $file['mime'],
                    'download_file_size' => $disk->size($file['path']),
                ]
            );
        }
    }
}