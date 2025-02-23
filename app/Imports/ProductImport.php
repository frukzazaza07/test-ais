<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Column name should match the CSV/Excel header
        $productCategory = ProductCategory::find($row['category_id']);
        if (!$productCategory) {
            throw 'Product Category Not found';
        }
        $productCategoryId = $productCategory->id;
        return new Product([
            'p_name_th'     => $row['name_th'],
            'p_name_en'    => $row['name_en'],
            'p_serial_number'    =>  generateSerialNumber($productCategoryId),
            'p_price' => $row['price'],
            'p_pc_id' => $productCategoryId,
            'p_created_by' => auth()->user()->id,
            'p_updated_by' => auth()->user()->id,
        ]);
    }

    public function rules(): array
    {
        return Product::rules(['file']);
    }
}
