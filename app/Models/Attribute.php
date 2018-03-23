<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table='attribute';
    protected $primaryKey = 'id';
    function goods(){
        return $this->belongsTo('\App\Models\Goods');
    }
    function attributelists(){
        return $this->hasMany('\App\Models\Attributelist','attribute_id');
    }
}
