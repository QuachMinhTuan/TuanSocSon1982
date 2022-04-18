<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected $fillable = [
        'masp',
        'shortened_link',
        'thumbnail',
        'name',
        'slug_name',
        'color',
        'status',
        'price',
        'product_information',
        'description',
        'the_firm',
        'slug_the_firm',
        'creator',
        'repairer',
        'disabler',
        'productcat_id',
        'parentlistproduct_id',
    ];
}
