<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attributelist extends Model
{
    protected $table='attribute_list';
    protected $primaryKey = 'id';
    function attribute(){
        return $this->belongsTo('\App\Models\Attribute');
    }
}