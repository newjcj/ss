<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    public $fillable = [
        'title' ,
        'telephone_fare' ,
        'price' ,
        'original_price' ,
        'carriage' ,
        'stock_quantity' ,
        'sale_quantity' ,
        'seller_one_integral',
        'seller_two_integral',
        'seller_three_integral' ,
        'agent_one_integral' ,
        'agent_two_integral' ,
        'agent_three_integral' ,
        'sort' ,
        'status' ,
        'desc' ,
        'detail',
        'images',
    ];

    public function getImagesAttribute($value)
    {
        return json_decode($value);
    }

    public function setImagesAttribute($value)
    {
        return $this->attributes['images'] = json_encode($value);
    }
}
