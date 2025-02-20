<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $fillable = ['p_pc_id', 'p_name_th', 'p_name_en', 'p_price', 'p_serial_number', 'p_created_by', 'p_updated_by'];

    public static function rules($notPickup = [], $pickup = [])
    {
        $rules = [
            'p_pc_id' => 'required|numeric|max:99999999999',
            'p_name_th' => 'required|max:255',
            'p_name_en' => 'required|max:255',
            'p_price' => 'required|numeric|max:99999999999',
        ];
        return setRules($rules, $notPickup, $pickup);
    }

    public function ProductCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'p_pc_id', 'id');
    }
}
