<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserIDcard extends Model
{
    public $fillable = [
      'user_id',
      'realname',
      'no',
      'status',
      'device',
      'iccid',
      'img_path1',
      'img_path2',
      'img_path3',
    ];
}
