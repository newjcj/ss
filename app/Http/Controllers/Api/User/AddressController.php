<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Auth, DB;

class AddressController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $list = UserAddress::where('user_id', $request->user)->where('status', 1)->get();

        return response()->json(responseFormat(1, 'success', ['list' => $list]));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        if ($request->id == 0) {
            $user = User::where('token', $request->token)->first();
            $address = UserAddress::where('is_default', 1)->where('user_id', $user->id)->first();
        } else {
            $address = UserAddress::where('id', $request->id)->first();
        }

        return response()->json(responseFormat(1, 'success', $address));
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        UserAddress::where('id', $request->id)->where('user_id', $user->id)->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'pos_province' => $request->pos_province,
            'pos_city' => $request->pos_city,
            'pos_district' => $request->pos_district,
            'pos_name' => $request->pos_name,
            'address_detail' => $request->address_detail,
        ]);

        return response()->json(responseFormat(1, '修改成功'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function set(Request $request)
    {
        $address = UserAddress::where('id', $request->id)->first();

        if ($address->is_default == 0) {
            $address->is_default = 1;
            $address->save();
            UserAddress::where('id', '!=', $address->id)->update(['is_default' => 0]);
        } else {
            $address->is_default = 0;
            $address->save();
        }
        return response()->json(responseFormat(1, 'success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        if ($user) {
            UserAddress::where('id', $request->id)->where('user_id', $user->id)->update(['status' => 2]);
        }
        return response()->json(responseFormat(1, '删除成功'));
    }

    public function add(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        $count = UserAddress::where('user_id', $user->id)->get()->toArray();

        $default = 0;
        if (!count($count)) {
            $default = 1;
        }

        UserAddress::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'is_default' => $request->is_default,
            'phone' => $request->phone,
            'pos_province' => $request->pos_province,
            'pos_city' => $request->pos_city,
            'pos_district' => $request->pos_district,
            'pos_name' => $request->pos_name,
            'address_detail' => $request->address_detail,
            'is_default' => $default,
        ]);
        return response()->json(responseFormat(1, '添加成功'));
    }
}