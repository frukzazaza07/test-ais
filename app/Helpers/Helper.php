<?php

use App\Models\Product;
use App\Models\ProductCategory;

// enum ProductCategory
// {
//     case TV;
//     case FRIDEGE;
//     case IRON;
// }


function generateSerialNumber($productCategoryId, $maxSerialNumber = 6)
{
    if (!$productCategoryId) {
        return null;
    }

    $productCategory = ProductCategory::where('id', $productCategoryId)->first();
    $latestId = Product::where('p_pc_id', $productCategoryId)->max('id');
    $latestNumber = $latestId ? $latestId + 1 : 1;
    $latestLength = mb_strlen($latestNumber, 'UTF-8');
    $maxSerialNumber = $latestLength > $maxSerialNumber ? $latestLength : $maxSerialNumber;
    $serialNumber = 'INV' . $productCategory->pc_prefix_serial_number . str_pad($latestNumber, $maxSerialNumber, '0', STR_PAD_LEFT);
    return $serialNumber;


    // $serialNumber = '';
    // switch ($productCategory) {
    //     case ProductCategory::TV:
    //         $latestId = Product::where('p_pc_id', 1)->max('id');
    //         $latestNumber = $latestId ? $latestId + 1 : 1;
    //         $latestLength = mb_strlen($latestNumber, 'UTF-8');
    //         $maxSerialNumber = $latestLength > $maxSerialNumber ? $latestLength : $maxSerialNumber;
    //         $serialNumber = 'INVTV' . str_pad($latestNumber, $maxSerialNumber, '0', STR_PAD_LEFT);
    //         break;
    //     case ProductCategory::FRIDEGE:
    //         $latestId = Product::where('p_pc_id', 2)->max('id');
    //         $latestNumber = $latestId ? $latestId + 1 : 1;
    //         $latestLength = mb_strlen($latestNumber, 'UTF-8');
    //         $maxSerialNumber = $latestLength > $maxSerialNumber ? $latestLength : $maxSerialNumber;
    //         $serialNumber = 'INVFR' . str_pad($latestNumber, $maxSerialNumber, '0', STR_PAD_LEFT);
    //         break;
    //     case ProductCategory::IRON:
    //         $latestId = Product::where('p_pc_id', 3)->max('id');
    //         $latestNumber = $latestId ? $latestId + 1 : 1;
    //         $latestLength = mb_strlen($latestNumber, 'UTF-8');
    //         $maxSerialNumber = $latestLength > $maxSerialNumber ? $latestLength : $maxSerialNumber;
    //         $serialNumber = 'INVIR' . str_pad($latestNumber, $maxSerialNumber, '0', STR_PAD_LEFT);
    //         break;
    // }
    // return $serialNumber;
}

function convertColumnSnakeToCamel($string, $removePrefix = true)
{
    if ($removePrefix) {
        $stringExplode = explode('_', $string);
        array_shift($stringExplode);
        $string = implode("_", $stringExplode);
    }
    return snakeToCamel($string);
}


function setPayload($request, $useField = [], $prefix = '')
{
    $results = [];
    foreach ($useField as $key => $value) {
        $useFieldCamelCase = $value;
        if ($prefix && $prefix != '') {
            $valueExplode = explode('_', $value);
            array_shift($valueExplode);
            $useFieldCamelCase = implode("_", $valueExplode);
        }
        $results[$value] = $request[snakeToCamel($useFieldCamelCase)];
    }
    return $results;
}

function camelToSnake($input)
{
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}
function snakeToCamel($input)
{
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
}

function setRules($rules = [], $notPickup = [], $pickup = [])
{
    // filter not use
    $rules = array_filter($rules, function ($key) use ($notPickup) {
        return !in_array($key, $notPickup);
    }, ARRAY_FILTER_USE_KEY);

    if (count($pickup)) {
        // filter use
        $rules = array_filter($rules, function ($key) use ($pickup) {
            return in_array($key, $pickup);
        }, ARRAY_FILTER_USE_KEY);
    }

    return $rules;
}
