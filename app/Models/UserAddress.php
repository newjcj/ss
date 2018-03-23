<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    public $fillable = [
      'user_id',
      'name',
      'is_default',
      'phone',
      'pos_province',
      'pos_city',
      'pos_district',
      'pos_name',
      'address_detail',
    ];
}
