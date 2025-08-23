<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Catalog extends Model
{
    protected $fillable = [
        'name',
        'description'
        ];

    public function product()
    {
         return $this->hasMany(Product::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'catalog_product');
    }



}
