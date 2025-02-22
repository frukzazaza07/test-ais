<?php

use App\Http\Resources\ApiCollection;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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

    $productCategory = ProductCategory::find($productCategoryId);
    $latestId = Product::withTrashed()->where('p_pc_id', $productCategoryId)->count();
    $latestNumber = $latestId ? $latestId + 1 : 1;
    $latestLength = mb_strlen($latestNumber, 'UTF-8');
    $maxSerialNumber = $latestLength > $maxSerialNumber ? $latestLength : $maxSerialNumber;
    $serialNumber = 'INV' . $productCategory->pc_prefix_serial_number . str_pad($latestNumber, $maxSerialNumber, '0', STR_PAD_LEFT);
    return $serialNumber;
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

function regexValidateThaiChar()
{
    return 'regex:/^[ก-๙0-9 ]+$/u';
}

function regexValidateEngChar()
{
    return 'regex:/^[a-zA-Z0-9 ]+$/';
}

function regexValidateEngUppercaseChar()
{
    return 'regex:/^[A-Z]+$/';
}

function apiResponse(Request $request, $data, $model, $status = 200)
{
    if (!$request->wantsJson()) {
        return null;
    }
    // Return JSON for API requests
    return response()->json(
        new ApiCollection($data, $model, $status),
        $status >= 200 && $status < 300 ? 200 : $status,
        ['Content-Type' => 'application/json; charset=UTF-8'],
        JSON_UNESCAPED_UNICODE
    );
}

function getApiResponseMessage($status = 200)
{
    try {
        $message = [
            200 => 'Request Successfully.',
            201 => 'Created Successfully.',
            202 => 'Updated Successfully.',
            204 => 'Deleted Successfully.',
            400 => 'Validation Input Error.',
            404 => 'Not found.',
        ];
        return $message[$status];
    } catch (Exception $e) {
        throw 'getApiResponseMessage not match';
    }
}

function generateQrcode($generateData, $format = 'png', $size = 300)
{
    return QrCode::format($format)
        // ->merge('/public/backoffice/insureok-logo.png')
        ->margin(2)
        ->size($size)
        ->errorCorrection('M')
        ->generate($generateData);
}
