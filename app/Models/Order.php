<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $fillable = [
        'no',
        'user_id',
        'goods_id',
        'goods_title',
        'mode_of_payment',
        'logistics_mode',
        'quantity',
        'price',
        'amount',
        'remark',
        'receiving_address',
        'receiving_phone',
        'receiving_name',
    ];

    /**
     * @param $query
     * @param $filter
     */
    public static function scopeFilter($query, $filter)
    {
        if (isset($filter['no']) && $filter['no']) {
            //$query->where('no', $filter['no']);
            $query->where('no', 'like', '%'.$filter['no'].'%');
        }
        if (isset($filter['status']) && $filter['status']) {
            $query->where('status', $filter['status']);
        }
        if (isset($filter['modeOfPayment']) && $filter['modeOfPayment']) {
            $query->where('mode_of_payment', $filter['modeOfPayment']);
        }
        if (isset($filter['phone']) && $filter['phone']) {
            $user = User::where('phone', $filter['phone'])->first();
            if ($user) {
                $query->where('user_id', $user->id);
            }
        }
        return $query;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function goods()
    {
        return $this->belongsTo(Goods::class, 'goods_id', 'id');
    }
}
