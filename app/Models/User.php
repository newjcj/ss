<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone', 'password', 'parent_id','special_type', 'qr_code', 'token', 'gender' ,'weixi' ,'nickname', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * @param $query
     * @param $filter
     */
    public static function scopeFilter($query, $filter)
    {
        if (isset($filter['role']) && $filter['role'])  {
            $query->where('special_type', $filter['role']);
        }

        if (isset($filter['type']) && $filter['type']) {
            $query->where('type', $filter['type']);
        }

        if (isset($filter['phone']) && $filter['phone']) {
            $query->where('phone', $filter['phone']);
        }
        return $query;
    }

    public function parent()
    {
        return $this->hasOne(User::class, 'id', 'parent_id');
    }
}
