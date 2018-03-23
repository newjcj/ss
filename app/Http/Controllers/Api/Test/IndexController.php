<?php

namespace App\Http\Controllers\Api\Test;

/*
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;
use App\Models\User;
use App\Service\Rebate;
use Auth, DB, QrCode, Image;
use App\Exceptions\CustomException;
use App\Models\Goods;
use App\Models\Order;
use App\Service\UserFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
*/
use Yansongda\Pay\Pay;
use Yansongda\Pay\Log;
use App\Models\User;
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
 * @package App\Http\Controllers\Goods
 */
class IndexController extends Controller
{
    /**
     * 测试页面
     * $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $file = "C:\phpstudy\WWW3\public/master.jpg";
        $water_file = "C:\phpstudy\WWW3\public/shui.png";
        $new_file = "C:\phpstudy\WWW3\public/master.jpg";

        $water = imagecreatefrompng($water_file);
        imagealphablending($water, false);
        imagesavealpha($water, true);
        $img = imagecreatefromjpeg($file);
        $img_w = imagesx($img);
        $img_h = imagesy($img);
        $water_w = imagesx($water);
        $water_h = imagesy($water);
        imagecopy($img, $water, 0, 0, 0, 0, $water_w, $water_h);
        imagejpeg($img, $new_file, 100);
        imagedestroy($img);
        imagedestroy($water);
        exit;

        /* 生成xls
        $table = '';
        $table .= "<table border=1>
            <thead>
                <tr>
                    <th class='name'>名称</th>
                    <th class='name'>入库日期</th>
                    <th class='name'>入库库位</th>
                    <th class='name'>供货商</th>
                    <th class='name'>入库人</th>
                    <th class='name'>数量</th>
                    <th class='name'>单价</th>
                </tr>
            </thead>
            <tbody>";
        $table .= "<tr>
                <td class='name'>小米6</td>
                <td class='name'>2018-03-12</td>
                <td class='name'>A-8</td>
                <td class='name'>小米</td>
                <td class='name'>雷军</td>
                <td class='name'>100</td>
                <td class='name'>2499.00</td>
            </tr>";
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
        echo $table;*/





        /*$user = DB::table('user_idcard')->where('id', 100)->first();
        if($user){
            var_dump($user);
        }else{
            echo "<script> alert('请先登录'); window.location.href = 'http://www.baidu.com';</script>";
        }
        exit;*/
        
        //给支付失败的订单生成返利
        //$no = "2018030117145700009162";
        //$no = "2018030114473000006962";
        //$no = "2018030114345700009143";
        //$orderInfo = Order::where('no', $no)->first();
        //$this->paymentAfter($orderInfo);

        /*
        //生成推广二维码
        $offset = (0) * 1000;
        $user = User::orderBy('id')->offset($offset)->limit(1000)->get();
        if(count($user) == 0){
            echo "no data"; exit;
        }
        foreach ($user as $key => $value) {
            $qrCode = $value["qr_code"];
            QrCode::format('png')->size(200)->generate("http://app.szsousou.com/register/".$qrCode, ('C:\phpstudy\WWW3\storage\app\public\qr/' . $qrCode . '.png'));
        }*/
        
    }


    private function paymentAfter($orderInfo, $paymentAmount = '', $paymentTime = '')
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



}