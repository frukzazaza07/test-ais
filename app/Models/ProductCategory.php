<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_category';
    protected $filable = ['pc_name_th', 'pc_name_en'];

    public static function rules($notPickup = [], $pickup = [])
    {
        $rules = [
            'pc_name_th' => 'required|max:255',
            'pc_name_en' => 'required|max:255',
        ];
        return setRules($rules, $notPickup, $pickup);
    }

    public function Product()
    {
        return $this->hasMany(Product::class, 'p_pc_id', 'id');
    }
}
