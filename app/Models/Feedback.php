<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $fillable =[
      'user_id',
      'status',
      'content',
    ];

    public function reply()
    {
        return $this->hasOne(self::class, 'relation_id', 'id');
    }
}
