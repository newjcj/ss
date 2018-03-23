<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use App\Models\UserFlow;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Predis\Replication\RoleException;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class WithdrawalController extends Controller
{
    /**
     * @param Request $request
     * @return $this
     */
    public function index(Request $request)
    {
        $status = $request->status;
        $userPhone = $request->user_phone;
        $type= $request->type;

        $withdrawal = Withdrawal::filter(compact('status', 'userPhone', 'type'))
            ->with('bank')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('backend.withdrawal.index', compact('withdrawal', 'status', 'userPhone', 'type'));
    }

    /**
     * 同意
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agree(Request $request)
    {
        $withdrawal = Withdrawal::where('id', $request->id)->where('status', 1)->first();

        if($withdrawal) {
            $withdrawal->status = 2;
            $withdrawal->save();
            return response()->json(responseFormat(1, '操作成功'));
        } else {
            return response()->json(responseFormat(0, '该提现申请已经处理'));

        }
    }

    /**
     * 拒绝
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request)
    {
        $withdrawal = Withdrawal::where('id', $request->id)->where('status', 1)->first();

        if ($withdrawal) {
            $withdrawal->status = 3;
            $withdrawal->save();
            // 退款
            $tradeType = $withdrawal->type == 3 ? 29 : 28;
            (new \App\Service\UserFlow($withdrawal->user_id, $withdrawal->voucher_id, $withdrawal->amount, $withdrawal->type, $tradeType, 0, $withdrawal->user_id))->income();
            return response()->json(responseFormat(1, '操作成功'));
        } else {
            return response()->json(responseFormat(0, '该提现申请已经处理'));
        }
    }
}
