<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFlow extends Model
{
    public $fillable = [
        'user_id',
        'user_level',
        'voucher_id',
        'type',
        'asset_type',
        'trade_type',
        'amount',
        'before_frozen',
        'after_frozen',
        'before_balance',
        'after_balance',
        'relation_user_id',
        'date',
        'poundage',
    ];

    /**
     * @param $query
     * @param $filter
     */
    public static function scopeFilter($query, $filter)
    {
        if (isset($filter['voucherId']) && $filter['voucherId'])  {
            $query->where('voucher_id', $filter['voucherId']);
        }

        if (isset($filter['userPhone']) && $filter['userPhone']) {
            $user = User::where('phone', $filter['userPhone'])->first();
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }

        if (isset($filter['assetType']) && $filter['assetType']) {
            $query->where('asset_type', $filter['assetType']);
        }

        if (isset($filter['tradeType']) && $filter['tradeType']) {
            if (in_array($filter['tradeType'], [11, 12, 13, 21, 22, 23])) {
                $query->where('trade_type', substr($filter['tradeType'], 0, 1))->where('user_level', substr($filter['tradeType'], 1, 1));
            } else {
                $query->where('trade_type', $filter['tradeType']);
            }
        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function relationUser()
    {
        return $this->hasOne(User::class, 'id', 'relation_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
