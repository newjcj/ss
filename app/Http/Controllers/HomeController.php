<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Service\Phone;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;
use Auth, DB, QrCode, Image;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class HomeController extends Controller
{

    private $smsTemplate = [
        1 => 'SMS_114665094', // 用户注册
        2 => 'SMS_114665093', // 找回密码
    ];

    /**
     * @param $qr
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function register($qr)
    {
        $inviteUser = User::where('qr_code', $qr)->first();
        return view('frontend.register', compact('inviteUser'));
    }

    public function doRegister(Request $request, $qrCode)
    {
        $phone = $request->phone;
        $password = $request->password;
        $code = $request->code;

        if (!$code) {
            return response()->json(responseFormat(0, '请输入验证码'));
        }
        $codeCache = cache()->get(trim($phone) . 1);

        if (!$code || $code != $codeCache) {
            return response()->json(responseFormat(0, '验证码错误'));
         }

        $referee = User::where('qr_code', $qrCode)->where('type', '!=', 0)->first();

        if (!$referee) {
            return response()->json(responseFormat(0, '推荐人不存在'));
        }

        if (!$phone || strlen($phone) != 11) {
            return response()->json(responseFormat(0, '请输入正确手机号'));
        }

        if (!$password) {
            return response()->json(responseFormat(0, '请输入密码'));
        }

        $user = User::where(['phone' => $request->phone])->first();

        if ($user) {
            return response()->json(responseFormat(0, '该手机已经注册'));
        }

        $qrCode = str_random(10);
        $result = User::create([
            'phone' => $request->phone,
            'parent_id' => $referee->id,
            'password' => bcrypt($request->password),
            'qr_code' => $qrCode,
        ]);

        QrCode::format('png')->size(200)->generate(route('register', ['qr' => $qrCode]),public_path('storage/qr/' . $qrCode . '.png'));

        if ($result) {
            (new Phone())->register($request->phone);
            cache()->delete(trim($phone) . 1);
            return response()->json(responseFormat(1, '注册成功'));
        }
    }

    /**
     * 发送短信
     * @param Request $request
     * @return bool
     */
    public function smsSend(Request $request)
    {
        $phone = trim($request->phone);
        $easySms = new EasySms(config('sms.config'));

        try {
            $code = randNumber();
            cache()->add($phone . 1, $code, 10);
            $easySms->send($phone, [
                'content' => '',
                'template' => $this->smsTemplate[1],
                'data' => [
                    'code' => $code
                ],
            ]);
            return response()->json(responseFormat(1, '短信已经发送至您手机，请注意查收'));
        } catch (NoGatewayAvailableException $e) {
            return response()->json(responseFormat(0, '发送失败，请稍后再试'));
        }
    }
}
