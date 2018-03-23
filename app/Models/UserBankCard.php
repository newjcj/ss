<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserBankCard extends Model
{
    public $fillable = [
      'user_id',
      'name',
      'bank_card',
      'bank_name',
    ];
}
