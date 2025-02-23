<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product';
    protected $fillable = ['p_pc_id', 'p_name_th', 'p_name_en', 'p_price', 'p_serial_number', 'p_serial_number_image', 'p_created_by', 'p_updated_by'];
    public $timestamps = false;
    protected $dates = ['p_created_at', 'p_updated_at', 'p_deleted_at'];
    const CREATED_AT = 'p_created_at';
    const UPDATED_AT = 'p_updated_at';
    const DELETED_AT = 'p_deleted_at';

    public static function rules($notPickup = [], $pickup = [])
    {
        $rules = [
            'p_pc_id' => 'required|numeric|max:99999999999',
            'p_name_th' => 'required|max:255|' . regexValidateThaiChar(),
            'p_name_en' => 'required|max:255|' . regexValidateEngChar(),
            'p_price' => 'required|numeric|max:99999999999',
            'p_serial_number' => [
                'required',
                Rule::unique('product', 'p_serial_number')->ignore(isset($data['id']) ? $data['id'] : null)
            ],
            'file' => 'required|mimes:csv,xls,xlsx',
        ];
        return setRules($rules, $notPickup, $pickup);
    }

    public function ProductCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'p_pc_id', 'id');
    }
}
