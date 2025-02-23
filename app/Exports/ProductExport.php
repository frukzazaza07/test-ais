<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'Name (TH)',
            'Name (EN)',
            'Category (TH)',
            'Category (EN)',
            'Serial Number',
            'Price',
        ];
    }

    public function map($product): array
    {
        return [
            $product->p_name_th,
            $product->p_name_en,
            $product->ProductCategory->pc_name_th,
            $product->ProductCategory->pc_name_en,
            $product->p_serial_number,
            number_format($product->p_price, 2),
        ];
    }
}
