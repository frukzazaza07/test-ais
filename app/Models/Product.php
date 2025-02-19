<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';
    protected $filable = ['p_name_th', 'p_name_en'];

    public function ProductCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'p_pc_id', 'id');
    }
}
