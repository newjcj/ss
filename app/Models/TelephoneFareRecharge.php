<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelephoneFareRecharge extends Model
{
    public $fillable = [
        'user_id',
        'phone',
        'card',
        'order_no',
        'type',
        'result_message',
        'result_code',
    ];
}
