<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['supplier_id', 'product_name', 'product_price', 'product_image'];

    public function supplier()
    {
        return $this->belongsTo(Product::class, 'supplier_id');
    }
}
