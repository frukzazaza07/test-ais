<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductCategory::create([
            'pc_name_th' => 'ทีวี',
            'pc_name_en' => 'TV',
            'pc_prefix_serial_number' => 'TV',
            'pc_created_by' => 1,
            'pc_updated_by' => 1,
        ]);
        ProductCategory::create([
            'pc_name_th' => 'ตู้เย็น',
            'pc_name_en' => 'Fridge',
            'pc_prefix_serial_number' => 'FR',
            'pc_created_by' => 1,
            'pc_updated_by' => 1,
        ]);
        ProductCategory::create([
            'pc_name_th' => 'เตารีด',
            'pc_name_en' => 'Iron',
            'pc_prefix_serial_number' => 'IR',
            'pc_created_by' => 1,
            'pc_updated_by' => 1,
        ]);
    }
}
