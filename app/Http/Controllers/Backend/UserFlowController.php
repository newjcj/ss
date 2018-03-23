<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\UserFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class UserFlowController extends Controller
{
    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $assetType = $request->asset_type;
        $tradeType = $request->trade_type;
        $voucherId = $request->voucher_id;
        $userPhone = $request->user_phone;

        $userFlow = UserFlow::filter(compact('assetType', 'tradeType', 'voucherId', 'userPhone'))
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('backend.user-flow.index', compact('userFlow', 'assetType', 'tradeType', 'voucherId', 'userPhone'));
    }

    /**
     * 用户列表
     * @param Request $request
     * @return mixed
     */
    public function userList(Request $request)
    {
        $pageSize = $request->limit;

        $goods = UserFlow::orderBy('id', 'desc')->paginate($pageSize ?? 20);

        return response()->json([
            "code" => 0,
            "msg" => "",
            "count" => $goods->total(),
            "data" => $goods->items()
        ]);
    }
}
