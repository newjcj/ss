<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRecode extends Model
{
    public $fillable = [
      'session_id',
      'account',
      'leg',
      'callee',
      'caller',
      'create_time',
      'ring_time',
      'answer_time',
      'end_time',
      'bill_sec',
      'bill_total',
      'bill_rate',
      'hangup',
    ];
}
