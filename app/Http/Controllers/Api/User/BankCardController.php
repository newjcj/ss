<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserBankCard;
use Illuminate\Http\Request;
use Auth, DB;

class BankCardController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        $list = UserBankCard::where('user_id', $user->id)->where('status', 1)->get();

        return response()->json(responseFormat(1, 'success', $list));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        $bankCard = UserBankCard::where('id', $request->id)->where('user_id', $user->id)->first();

        return response()->json(responseFormat(1, 'success', $bankCard));
    }

    public function add(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        UserBankCard::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'bank_card' => $request->bank_card,
        ]);
        return response()->json(responseFormat(1, '添加成功'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        UserBankCard::where('id', $request->id)->where('user_id', $user->id)->update([
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'bank_card' => $request->bank_card,
        ]);

        return response()->json(responseFormat(1, '修改成功'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        if ($user) {
            UserBankCard::where('id', $request->id)->where('user_id', $user->id)->update(['status' => 2]);
        }

        return response()->json(responseFormat(1, '删除成功'));
    }
}