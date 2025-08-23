<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Catalog;

class Product extends Model
{
    protected $fillable = [
        'external_id',
        'title',
        'price',
        'description',
        'category',
        'image',
    ];

    public function catalog()
    {
         return $this->belongsTo(Catalog::class);

    }

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class, 'catalog_product');
    }



}
