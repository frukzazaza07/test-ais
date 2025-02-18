<?php

use App\Models\Product;

enum ProductCategory
{
    case TV;
    case FRIDEGE;
    case IRON;
}


function generateSerialNumber(ProductCategory $productCategory)
{
    $serialNumber = '';
    switch ($productCategory) {
        case ProductCategory::TV:
            $latestId = Product::where('p_pc_id', 1)->max('id');
            $latestNumber = $latestId ? $latestId + 1 : 1;
            $serialNumber = 'INV-TV' . str_pad($latestNumber, 6, '0', STR_PAD_LEFT);
            break;
        case ProductCategory::FRIDEGE:
            $latestId = Product::where('p_pc_id', 2)->max('id');
            $latestNumber = $latestId ? $latestId + 1 : 1;
            $serialNumber = 'INV-FR' . str_pad($latestNumber, 6, '0', STR_PAD_LEFT);
            break;
        case ProductCategory::IRON:
            $latestId = Product::where('p_pc_id', 3)->max('id');
            $latestNumber = $latestId ? $latestId + 1 : 1;
            $serialNumber = 'INV-IR' . str_pad($latestNumber, 6, '0', STR_PAD_LEFT);
            break;
    }
    return $serialNumber;
}
