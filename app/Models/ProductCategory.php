<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_category';
    protected $fillable = ['pc_name_th', 'pc_name_en', 'pc_prefix_serial_number', 'pc_created_by', 'pc_updated_by'];
    public $timestamps = false;
    protected $dates = ['pc_created_at', 'pc_updated_at', 'pc_deleted_at'];
    const CREATED_AT = 'pc_created_at';
    const UPDATED_AT = 'pc_updated_at';
    const DELETED_AT = 'pc_deleted_at';

    public static function rules($notPickup = [], $pickup = [], $data = null)
    {
        $rules = [
            'pc_name_th' => [
                'required',
                'max:255',
                regexValidateThaiChar(),
                Rule::unique('product_category', 'pc_name_th')->ignore(isset($data['id']) ? $data['id'] : null)->whereNull('pc_deleted_at')
            ],
            'pc_name_en' => [
                'required',
                'max:255',
                regexValidateEngChar(),
                Rule::unique('product_category', 'pc_name_en')->ignore(isset($data['id']) ? $data['id'] : null)->whereNull('pc_deleted_at')
            ],
            'pc_prefix_serial_number' => [
                'required',
                'max:10',
                regexValidateEngUppercaseChar(),
                Rule::unique('product_category', 'pc_prefix_serial_number')->ignore(isset($data['id']) ? $data['id'] : null)->whereNull('pc_deleted_at')
            ],
        ];
        return setRules($rules, $notPickup, $pickup);
    }

    public function Product()
    {
        return $this->hasMany(Product::class, 'p_pc_id', 'id');
    }
}
