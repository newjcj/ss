<?php

namespace App\Http\Controllers\Backend;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class OrderController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $no = $request->no;
        $status = $request->status;
        $phone = $request->phone;
        $modeOfPayment = $request->mode_of_payment;

        $orders = Order::filter(compact('no', 'status', 'phone', 'modeOfPayment','attributes'))->with('user')->orderBy('id', 'desc')
            ->paginate(20);

        return view('backend.order.index', compact('orders', 'no', 'status', 'phone', 'modeOfPayment', 'attributes'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deliver(Request $request)
    {
        Order::where('id', $request->id)
            ->where('status', 2)
            ->update([
                'express_no' => $request->express_no ? $request->express_no : '',
                'status' => 3,
            ]);
        return response(responseFormat(1, '发货成功'));
    }

    public function excel(Request $request){
        $no = $request->no;
        $status = $request->status;
        $phone = $request->phone;
        $modeOfPayment = $request->mode_of_payment;

        $orders = Order::filter(compact('no', 'status', 'phone', 'modeOfPayment'))->with('user')->orderBy('id', 'desc')->get();
        $data = array();
        foreach ($orders as $key => $item) {
            $temp["no"] = "单号".$item["no"];
            $temp["phone"] = $item->user->phone ?? '';
            $temp["goods_title"] = $item->goods_title;
            $temp["attributes"] = $item->attributes;
            $temp["amount"] = $item->amount;
            $temp["created_at"] = $item->created_at;
            $temp["status"] = config('order.status')[$item->status] ?? '';
            $temp["mode_of_payment"] = config('order.payment')[$item->mode_of_payment] ?? '';
            $temp["time_of_payment"] = $item->time_of_payment;
            $temp["payment"] = $item->payment;
            $temp["remark"] = $item->remark;
            $temp["logistics_mode"] = $item->logistics_mode == 1 ? '自提' : '快递';
            $temp["express_no"] = $item->express_no ? "单号".$item->express_no : "";
            $temp["receiving_name"] = $item->receiving_name ? $item->receiving_name : "";
            $temp["receiving_phone"] = $item->receiving_phone ? $item->receiving_phone : "";
            $temp["receiving_address"] = $item->receiving_address ? $item->receiving_address : "";
            $data[] = $temp;
        }
        $table = '';
        $table .= "<table border=1>
            <thead>
                <tr>
                    <th>订单号</th>
                    <th>用户</th>
                    <th>商品</th>
                    <th>商品属性</th>
                    <th>订单金额</th>
                    <th>订单时间</th>
                    <th>订单状态</th>
                    <th>支付方式</th>
                    <th>支付时间</th>
                    <th>支付金额</th>
                    <th>用户留言</th>
                    <th>提货方式</th>
                    <th>物流单号</th>
                    <th>收货人姓名</th>
                    <th>收货人手机</th>
                    <th>收货人地址</th>
                </tr>
            </thead>
            <tbody>";
        foreach ($data as $key => $item) {
            $table .= "<tr>
            <td>".$item["no"]."</td>
            <td>".$item["phone"]."</td>
            <td>".$item["goods_title"]."</td>
            <td>".$item["attributes"]."</td>
            <td>".$item["amount"]."</td>
            <td>".$item["created_at"]."</td>
            <td>".$item["status"]."</td>
            <td>".$item["mode_of_payment"]."</td>
            <td>".$item["time_of_payment"]."</td>
            <td>".$item["payment"]."</td>
            <td>".$item["remark"]."</td>
            <td>".$item["logistics_mode"]."</td>
            <td>".$item["express_no"]."</td>
            <td>".$item["receiving_name"]."</td>
            <td>".$item["receiving_phone"]."</td>
            <td>".$item["receiving_address"]."</td>
            </tr>";
        }
        $table .= "</tbody></table>";
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="订单表.xls"');
        header("Content-Transfer-Encoding:binary");
        //$table=iconv('UTF-8',"GB2312//IGNORE",$table);
        echo $table;
    }
}
