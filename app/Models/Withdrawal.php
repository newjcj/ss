<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    public $fillable = [
        'user_id',
        'type',
        'user_phone',
        'amount',
        'poundage',
        'status',
        'voucher_id',
        'user_bank_card_id',
    ];

    /**
     * @param $query
     * @param $filter
     */
    public static function scopeFilter($query, $filter)
    {
        if (isset($filter['status']) && $filter['status']) {
            $query->where('status', $filter['status']);
        }
        if (isset($filter['type']) && $filter['type']) {
            $query->where('type', $filter['type']);
        }
        if (isset($filter['userPhone']) && $filter['userPhone']) {
            $query->where('user_phone', $filter['userPhone']);
        }
        return $query;
    }

    public function bank()
    {
        return $this->belongsTo(UserBankCard::class, 'user_bank_card_id', 'id');
    }
}
