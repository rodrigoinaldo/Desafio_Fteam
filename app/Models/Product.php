<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function catalogs()
    {
        return $this->belongsToMany(Catalog::class);
    }

}
