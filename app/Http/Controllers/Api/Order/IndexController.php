<?php

namespace App\Http\Controllers\Api\Order;

use Yansongda\Pay\Pay;
use App\Models\User;
use App\Service\Rebate;
use DB;
use Log;
use App\Exceptions\CustomException;
use App\Models\Goods;
use App\Models\Order;
use App\Service\UserFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * 订单状态 1 待支付 2 已支付待发货 3 订单完成 4 订单取消
 * Class IndexController
 * @package App\Http\Controllers\Goods
 */
class IndexController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user;
        $goodsId = $request->goods_id;
        $quantity= $request->quantity;
        $payment = $request->payment;
        $logistics = $request->logistics;
        $remark = $request->remark;
        $receivingAddress = $request->receiving_address;
        $receivingPhone = $request->receiving_phone;
        $receivingName = $request->receiving_name;

        if ($goodsId && $quantity && $payment && $logistics && in_array($payment, [1,2,3,4]) && in_array($logistics, [1,2])) {
            $goodsInfo = Goods::find($goodsId);

            if ($goodsInfo) {
                // 总额
                $amount = bcmul($goodsInfo->price, $quantity, 2);

                $order = Order::create([
                    'no' => generateOrderNo(),
                    'user_id' => $user,
                    'goods_id' => $goodsInfo->id,
                    'goods_title' => $goodsInfo->title,
                    'price' => $goodsInfo->price,
                    'quantity' => $quantity,
                    'mode_of_payment' => $payment,
                    'logistics_mode' => $logistics,
                    'receiving_address' => $receivingAddress,
                    'receiving_phone' => $receivingPhone,
                    'receiving_name' => $receivingName,
                    'amount' => $amount,
                    'remark' => $remark,
                ]);
                //加订单的属性
                $attributes = json_decode($request->goodsmsg,1);
                $attrs = [];
                foreach ($attributes as $k=>$attribute) {
                    $attrs[$k] = $attribute['dist_id'];
                }
                $attributes = Order::goodsAttribute($attrs);
                $aorder = Order::find($order->id);
                $aorder->attributes = $attributes;
                $aorder->save();

                // 增加已出售
                $goodsInfo->sale_quantity = $goodsInfo->sale_quantity + $quantity;
                $goodsInfo->save();

                return response()->json(responseFormat(1, 'success', [
                    'amount' => $amount,
                    'balance' => 100000,
                    'no' => $order->no,
                ]));
            }
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function payment(Request $request)
    {
        $no = $request->no;

        $orderInfo = Order::where('no', $no)->first();

        if ($orderInfo) {
            $payPar = '';
            if ($orderInfo->mode_of_payment == 1) { // 支付宝
                $order = [
                    'out_trade_no' => $orderInfo->no,
                    'total_amount' => $orderInfo->amount,
                    'subject' => $orderInfo->goods_title,
                ];
                $alipay = Pay::alipay(config('pay.ali'))->app($order);
                $payPar =  $alipay->getContent();

            } else if($orderInfo->mode_of_payment == 2) { // 微信
                $order = [
                    'out_trade_no' => $orderInfo->no,
                    'total_fee' => bcmul($orderInfo->amount, 100,0),
                    'body' => $orderInfo->goods_title,
                ];
                $pay = Pay::wechat(config('pay.wechat'))->app($order);
                $payPar =  json_decode($pay->getContent());
            } else {
                $this->paymentAfter($orderInfo);
            }
            return response()->json(responseFormat(1, '支付成功', ['mode_of_payment' => $orderInfo->mode_of_payment, 'pay_par' => $payPar]));
        } else {
            return response()->json(responseFormat(0, 'error'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        $orderList = Order::orderBy('id', 'desc')
            ->where('user_id', $request->user)
            ->with([
                'goods' => function($query){
                    return $query->select('id', 'images');
                }
            ])
            ->paginate(50);

        foreach ($orderList as $key => $value) {
            if($value["status"] == 1 && ((time()-strtotime($value["created_at"])) > 48*60*60) ){
                unset($orderList[$key]);
            }
        }
        Log::info('111111111111111111111');
        Log::info($orderList);
        Log::info('111111111111111111111');

        return response()->json(responseFormat(1, 'success', $orderList));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function aliNotify(Request $request)
    {
        $alipay = Pay::alipay(config('pay.ali'));

        try{
            $data = $alipay->verify();

            $orderInfo = Order::where('no', $data->out_trade_no)->first();
            // 订单存在并且支付金额与原单金额相同，状态为待支付，则进行后续处理
//            if ($orderInfo && in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED']) &&  $orderInfo->amount == $data->total_amount && $orderInfo->status == 1) {
            if ($orderInfo && in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])  && $orderInfo->status == 1) {
                $this->paymentAfter($orderInfo, $data->total_amount, $data->gmt_payment);
            } else {
//                \Log::alert('noorder');
                file_put_contents('1', 1);
            }
        } catch (\Exception $e) {
            file_put_contents('2', \GuzzleHttp\json_encode($e->getMessage()));
        }
        return $alipay->success();
    }


    /**
     * @param Request $request
     */
    public function aliReturn(Request $request)
    {
        file_put_contents('3', 3);
    }

    /**
     * 微信回调
     * @param Request $request
     */
    public function wechatNotify(Request $request)
    {
        $pay = Pay::wechat(config('pay.wechat'));

        try{
            $data = $pay->verify();

            $orderInfo = Order::where('no', $data->out_trade_no)->first();
            // 订单存在并且支付金额与原单金额相同，状态为待支付，则进行后续处理
            if ($orderInfo && in_array($data->result_code, ['SUCCESS']) ) {
                $this->paymentAfter($orderInfo, bcdiv($data->total_fee, 100, 2), date('Y-m-d H:i:s'));
            } else {
                file_put_contents('1', 1);
            }
        } catch (\Exception $e) {
            file_put_contents('2', \GuzzleHttp\json_encode($e->getMessage()));
        }
        return $pay->success();
    }


    /**
     * @param $orderInfo
     * @param string $paymentAmount
     * @param string $paymentTime
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentAfter($orderInfo, $paymentAmount = '', $paymentTime = '')
    {
        DB::beginTransaction();
        try {
            (new UserFlow($orderInfo->user_id, $orderInfo->no, $orderInfo->amount, $orderInfo->mode_of_payment, 3, 0, $orderInfo->user_id))->expend();
            (new Rebate())->promotionSpecialist($orderInfo->user_id, $orderInfo->no, $orderInfo->goods_id, $orderInfo->quantity);
            (new Rebate())->operators($orderInfo->user_id, $orderInfo->no, $orderInfo->goods_id, $orderInfo->quantity);
            $orderInfo->status = 2;
            $orderInfo->payment = $paymentAmount ? $paymentAmount : $orderInfo->amount;
            $orderInfo->time_of_payment = $paymentTime ? $paymentTime : date('Y-m-d H:i:s');
            $orderInfo->save();

            $goods = Goods::where(['id' => $orderInfo->goods_id])->first();//by zhang
            if($goods->telephone_fare > 0){//
                User::where('id', $orderInfo->user_id)->update(['type' => 2]);
            }//by zhang
            
        } catch (CustomException $exception) {
            DB::rollBack();
            \Log::alert(json_encode($exception->getMessage()));
            return response()->json(responseFormat(0, $exception->getMessage()));
        }
        DB::commit();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function express(Request $request)
    {
        $order = Order::where('no', $request->no)->first();

        if ($order && $order->status == 3) {
            $host = "http://jisukdcx.market.alicloudapi.com";
            $path = "/express/query";
            $method = "GET";
            $appCode = "1d7ce4bf9490429d9b1641a19700ba56";
            $headers = array();
            array_push($headers, "Authorization:APPCODE " . $appCode);
            $query = "number=" . $order->express_no .  "&type=auto";
            $url = $host . $path . "?" . $query;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_FAILONERROR, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($curl));

            $express = [];

            if ($response->status == 0) {
                $express = collect($response->result)->toArray();
                $express['name'] = config('express')[$express['type']];
            }
            return response()->json(responseFormat(1, 'success', $express));
        }
        return response()->json(responseFormat(0, 'success'));
    }
}