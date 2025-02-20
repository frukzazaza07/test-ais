<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use ProductCategory;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'p_name_th' => 'ทีวีเสี่ยวมี่',
            'p_name_en' => 'xiaomi TV',
            'p_serial_number' => generateSerialNumber(1),
            'p_price' => 40000,
            'p_pc_id' => 1,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'ทีวีแอลจี',
            'p_name_en' => 'LG TV',
            'p_serial_number' => generateSerialNumber(1),
            'p_price' => 50000,
            'p_pc_id' => 1,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'ทีวีซัมซุง',
            'p_name_en' => 'sumsung TV',
            'p_serial_number' => generateSerialNumber(1),
            'p_price' => 60000,
            'p_pc_id' => 1,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'ตู้เย็นเสี่ยวมี่',
            'p_name_en' => 'xiaomi Fridege',
            'p_serial_number' => generateSerialNumber(2),
            'p_price' => 40000,
            'p_pc_id' => 2,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'ตู้เย็นแอลจี',
            'p_name_en' => 'LG Fridege',
            'p_serial_number' => generateSerialNumber(2),
            'p_price' => 50000,
            'p_pc_id' => 2,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'ตู้เย็นซัมซุง',
            'p_name_en' => 'sumsung Fridege',
            'p_serial_number' => generateSerialNumber(2),
            'p_price' => 60000,
            'p_pc_id' => 2,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'เตารีดเสี่ยวมี่',
            'p_name_en' => 'xiaomi Iron',
            'p_serial_number' => generateSerialNumber(3),
            'p_price' => 40000,
            'p_pc_id' => 3,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'เตารีดแอลจี',
            'p_name_en' => 'LG Iron',
            'p_serial_number' => generateSerialNumber(3),
            'p_price' => 50000,
            'p_pc_id' => 3,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
        Product::create([
            'p_name_th' => 'เตารีดซัมซุง',
            'p_name_en' => 'sumsung Iron',
            'p_serial_number' => generateSerialNumber(3),
            'p_price' => 60000,
            'p_pc_id' => 3,
            'p_created_by' => 1,
            'p_updated_by' => 1,
        ]);
    }
}
