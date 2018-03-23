<?php

namespace App\Http\Controllers\Api\Phone;

use App\Models\CallRecode;
use App\Models\User;
use App\Service\Phone;
use App\Service\Rebate;
use DB;
use App\Exceptions\CustomException;
use App\Models\Goods;
use App\Models\Order;
use App\Service\UserFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class IndexController
 * @package App\Http\Controllers\Api\Order
 */
class IndexController extends Controller
{
    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        $user = $request->phone;
        $callee = $request->callee;
        $result = (new Phone())->call($user, $callee, '');
        return response()->json(responseFormat(1, $result['msg']));
    }

    /**
     * @param Request $request
     */
    public function callback(Request $request)
    {
       try {
           CallRecode::create([
               'session_id' => $request->session_id,
               'account' => $request->account,
               'leg' => $request->leg,
               'caller' => $request->caller,
               'callee' => $request->callee,
               'create_time' => $request->create_time,
               'ring_time' => $request->ring_time,
               'answer_time' => $request->answer_time,
               'end_time' => $request->end_time,
               'bill_sec' => $request->bill_sec,
               'bill_rate' => $request->bill_rate,
               'bill_total' => $request->bill_total,
               'hangup' => $request->hangup,
           ]);
       } catch (\Exception $exception) {

       }
        echo 'success';
    }
}