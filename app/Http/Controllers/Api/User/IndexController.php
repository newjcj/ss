<?php

namespace App\Http\Controllers\Api\User;

use App\Exceptions\CustomException;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TelephoneFareRecharge;
use App\Models\User;
use App\Models\UserBankCard;
use App\Models\UserFlow;
use App\Models\UserCard;
use App\Models\Withdrawal;
use App\Providers\ResponseMacroServiceProvider;
use App\Service\Phone;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth, DB, QrCode, Image;
use Illuminate\Http\UploadedFile;
use League\Flysystem\Exception;
use Log;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class IndexController extends Controller
{
    private $loweLevel = [];

    private $count = [
        1 => [
            'number' => 0,
            'buy_number' => 0,
        ],
        2 => [
            'number' => 0,
            'buy_number' => 0,
        ],
        3 => [
            'number' => 0,
            'buy_number' => 0,
        ]
    ];

    private $smsTemplate = [
        1 => 'SMS_114665094', // 用户注册
        2 => 'SMS_114665093', // 找回密码
    ];

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $user = User::where(['phone' => $request->phone])->first();

        if (!$user) {
            return response()->json(responseFormat(0, '该手机没有注册'));
        }

        if (Auth::attempt(['phone' => $request->phone, 'password' => $request->password])) {
            $token = str_random(64);
            $user->token = $token;
            $user->save();
            return response()->json(responseFormat(1, '登录成功', [
                'token' => $token,
                'id' => $user->id,
                'phone' => $user->phone,
                'type' => $user->type,
                'avatar' => $user->avatar,
                'weixi' => $user->weixi,
                'gender' => $user->gender,
                'nickname' => $user->nickname,
                'qr_code' => $user->qr_code,
            ]));
        } else {
            return response()->json(responseFormat(0, '手机号或密码错误'));
        }
    }

    public function register(Request $request)
    {
        $phone = $request->phone;
        $password = $request->password;
        $refereePhone = $request->referee_phone;
        $code = $request->code;

        if (!$code) {
            return response()->json(responseFormat(0, '请输入验证码'));
        }
        $codeCache = cache()->get(trim($phone) . 1);

        if (!$code || $code != $codeCache) {
            return response()->json(responseFormat(0, '验证码错误'));
        }

        $referee = User::where('phone', $refereePhone)->where('type', '!=', 0)->first();

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

        QrCode::format('png')->size(200)->generate(route('register', ['qr' => $qrCode]), public_path('storage/qr/' . $qrCode . '.png'));

        if ($result) {
            (new Phone())->register($request->phone);
            cache()->delete(trim($phone) . 1);
            return response()->json(responseFormat(1, '注册成功'));
        }
    }

    /**
     * 找回密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forget(Request $request)
    {
        $phone = $request->phone;
        $password = $request->password;
        $code = $request->code;

        if (!$phone || strlen($phone) != 11) {
            return response()->json(responseFormat(0, '请输入正确手机号'));
        }
        $codeCache = cache()->get(trim($phone) . 2);

        if (!$codeCache || $codeCache != $code) {
            return response()->json(responseFormat(0, '验证码错误'));
        }

        User::where('phone', $phone)->update([
            'password' => bcrypt(trim($password)),
        ]);
        cache()->delete(trim($phone) . 2);
        return response()->json(responseFormat(1, '密码修改成功'));
    }

    /**
     * 积分与学分余额查询
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function balance(Request $request)
    {
        $user = User::where('token', $request->token)->first();
        if ($user && $request->type == 3) {
            return response()->json(responseFormat(1, 'success', ['balance' => (float)$user->integral]));
        } else if ($user && $request->type == 4) {
            return response()->json(responseFormat(1, 'success', ['balance' => (float)$user->credit]));
        } else {
            return response()->json(responseFormat(1, 'success', ['balance' => 0]));
        }
    }

    /**
     * 基础信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Request $request)
    {
        $token = $request->token;
        if (strlen($token) == 64) {
            // 呢称 账号 设备有效期
            $user = User::where('token', $token)->first();

            if ($user) {
                // 1 学分 奖励学分
                $credit = UserFlow::where('user_id', $user->id)->whereNotIn('type', [4, 5])->where('asset_type', 4)->sum('amount');
                // 2 积分 激励积分
                $integral = UserFlow::where('user_id', $user->id)->whereNotIn('type', [4, 5])->where('asset_type', 3)->sum('amount');
                // 3 提现 已兑换积分
                $withdrawals = UserFlow::where('user_id', $user->id)->where('type', 3)->whereIn('asset_type', [3, 4])->sum('amount');
                // 总累计积分
                //$sum = $credit + $integral;
                $sum = UserFlow::where('user_id', $user->id)->where('type', 1)->whereNotIn('trade_type', [29])->sum('amount');
                //  未兑换积分
                $noWithdrawals = $sum - $withdrawals;
                // 话费余额
                $phoneBalance = (new Phone())->balance($user->phone);

                return response()->json(responseFormat(1, '', [
                    'avatar' => $user->avatar,
                    'weixi' => $user->weixi,
                    'gender' => $user->gender,
                    'nickname' => $user->nickname ?: '您的呢称',
                    'phone' => $user->phone,
                    'expiry_time' => $user->expiry_time,
                    'sum' => (float)$sum,
                    'credit' => (float)$user->credit,
                    'integral' => (float)$user->integral,
                    'no_withdrawals' => (float)$noWithdrawals,
                    'withdrawals' => (float)$withdrawals,
                    'phone_balance' => isset($phoneBalance['data']['balance']) ? $phoneBalance['data']['balance'] : 0,
                ]));
            } else {
                return response()->json(responseFormat(-1, '您的登录信息已过期，请重新登录'));
            }
        }
    }

    /**
     * 我的团队
     * @param Request $request
     * @return array
     */
    public function team(Request $request)
    {
        $token = $request->token;
        if (strlen($token) == 64) {
            $user = User::where('token', $token)->first();
            if ($user) {

                $parent = User::where('id', $user->parent_id)->first();

                // 一级用户
                $one = User::where('parent_id', $user->id)->pluck('id')->toArray();
                $oneBuy = Order::whereIn('user_id', $one)->whereIn('status', [2, 3, 4])->count();
                // 二级用户
                $two = User::whereIn('parent_id', $one)->pluck('id')->toArray();;
                $twoBuy = Order::whereIn('user_id', $two)->whereIn('status', [2, 3, 4])->count();
                // 三级用户
                $three = User::whereIn('parent_id', $two)->pluck('id')->toArray();;
                $threeBuy = Order::whereIn('user_id', $three)->whereIn('status', [2, 3, 4])->count();

                return response()->json(responseFormat(1, '', [
                    'parent_name' => $parent->name ?? '',
                    'parent_phone' => $parent->phone ?? '',
                    'one' => count($one),
                    'one_buy' => $oneBuy,
                    'two' => count($two),
                    'two_buy' => $twoBuy,
                    'three' => count($three),
                    'three_buy' => $threeBuy,
                ]));
            }
        }
    }

    public function teamDetail(Request $request)
    {
        $token = $request->token;
        $date = $request->date;
        $type = $request->type;
        $phone = $request->phone;

        $offset = ($request->page) * 30;

        $user = User::where('token', $token)->first();
        // 一级用户
        $one = User::where('parent_id', $user->id)->pluck('id')->toArray();
        // 二级用户
        $two = User::whereIn('parent_id', $one)->pluck('id')->toArray();
        // 三级用户
        $three = User::whereIn('parent_id', $two)->pluck('id')->toArray();

        $queryResult = User::whereIn('id', array_merge($one, $two, $three))
            ->when(!empty($date), function ($query) use ($date) {
                return $query->whereBetween('created_at', [$date, $date . ' 23:59:59']);
            })
            ->when(!empty($type), function ($query) use ($type, $one, $two, $three) {
                if (in_array($type, [1, 2, 3])) { // 代理
                    return $query->where('special_type', $type);
                } else if ($type == 4) { // 创客
                    return $query->where('special_type', 0)->where('type', 2);
                } else if ($type == 5) { // 注册用户
                    return $query->where('type', 1)->where('special_type', 0);
                } else if ($type == 6) {
                    return $query->whereIn('id', $one);
                } else if ($type == 7) {
                    return $query->whereIn('id', $two);
                } else if ($type == 8) {
                    return $query->whereIn('id', $three);
                }
            })
            ->when(!empty($phone), function ($query) use ($phone) {
                return $query->where('phone', $phone);
            })
            ->orderBy('id', 'desc')
            ->offset($offset)->limit(30)->get();
            //->paginate(30);

        $responseData = [];
        foreach ($queryResult as $item) {
            $type = '';
            if ($item->special_type == 0 && $item->type == 2) {
                $type = '创客';
            } else if ($item->special_type == 0 && $item->type == 1) {
                $type = '注册用户';
            } else {
                $type = config('user.role')[$item->special_type];
            }
            $responseData[] = [
                'name' => $item->name,
                'phone' => $item->phone,
                'type' => $type,
                'created_at' => $item->created_at->format('Y-m-d')
            ];
        }

        return response()->json(responseFormat(1, 'success', $responseData));
    }

    /**
     * 我的账号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function account(Request $request)
    {
        $user = User::where('id', $request->user)->first();

        // 话费余额
        $phoneBalance = (new Phone())->balance($user->phone);

        return response()->json(responseFormat(1, 'success', [
            'phone' => $user->phone,
            'credit' => $user->credit,
            'integral' => $user->integral,
            'balance' => $phoneBalance
        ]));
    }

    /**
     * 积分转赠
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function transfer(Request $request)
    {
        $phone = $request->phone;
        $credit = $request->credit;
        $integral = $request->integral;

        $other = User::where('phone', $phone)->first();

        if (!$other) {
            return response()->json(responseFormat(0, '对方账号不存在'));
        }
        $user = User::where('id', $request->user)->lockForUpdate()->first();

        if ($user->credit < $credit) {
            return response()->json(responseFormat(0, '您当前学分不够，请调整后再提交'));
        }
        if ($user->integral < $integral) {
            return response()->json(responseFormat(0, '您当前积分不够，请调整后再提交'));
        }
        $orderNo = generateOrderNo();
        if ($credit > 0) {
            (new \App\Service\UserFlow($user->id, $orderNo, $credit, 4, 4, $user->type, $other->id))->expend();
            (new \App\Service\UserFlow($other->id, $orderNo, $credit, 4, 4, $other->type, $user->id))->income();
        }
        if ($integral > 0) {
            (new \App\Service\UserFlow($user->id, $orderNo, $integral, 3, 5, $user->type, $other->id))->expend();
            (new \App\Service\UserFlow($other->id, $orderNo, $integral, 3, 5, $other->type, $user->id))->income();
        }
        return response()->json(responseFormat(1, '转赠成功'));
    }

    /**
     * 提现申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdrawal(Request $request)
    {
        $credit = $request->credit;
        $integral = $request->integral;
        $token = $request->token;

        if (strlen($token) ==  64) {
            $user = User::where('token', $token)->lockForUpdate()->first();

            if ($user) {
                if ($user->credit < $credit) {
                    return response()->json(responseFormat(0, '您当前学分不够，请调整后再提交'));
                }
                if ($user->integral < $integral) {
                    return response()->json(responseFormat(0, '您当前积分不够，请调整后再提交'));
                }

                $card = UserBankCard::where('user_id', $user->id)->where('id', $request->id)->first();
                if ($card) {
                    DB::beginTransaction();

                    $orderNo = generateOrderNo();
                    try {
                        if ($credit > 0) {
                            (new \App\Service\UserFlow($user->id, $orderNo, $credit, 4, 6, $user->type, $user->id))->expend();

                            // 创建提现单据
                            Withdrawal::create([
                                'user_id' => $user->id,
                                'type' => 4,
                                'user_phone' => $user->phone,
                                'amount' => $credit,
                                'user_bank_card_id' => $card->id,
                                'voucher_id' => $orderNo,
                                'poundage' => config('userflow.poundage.withdrawal.credit'),
                            ]);
                        }
                        if ($integral > 0) {
                            (new \App\Service\UserFlow($user->id, $orderNo, $integral, 3, 7, $user->type, $user->id))->expend();

                            // 创建提现单据
                            Withdrawal::create([
                                'type' => 3,
                                'user_id' => $user->id,
                                'user_phone' => $user->phone,
                                'amount' => $integral,
                                'user_bank_card_id' => $card->id,
                                'voucher_id' => $orderNo,
                                'poundage' => config('userflow.poundage.withdrawal.integral'),
                            ]);
                        }

                    } catch (CustomException $exception) {
                        DB::rollBack();
                        return response()->json(responseFormat(0, $exception->getMessage()));
                    }
                    DB::commit();
                    return response()->json(responseFormat(1, '您的兑换申请已经提交，等待处理'));
                }
            }
        }
        return response()->json(responseFormat(0, 'error'));
    }

    /**
     * 我的下级
     * @param $userId
     * @return array
     */
    private function myLoweLevel($userId)
    {
        $oneLevel = User::where('parent_id', $userId)->get();

        if ($oneLevel) {
            foreach ($oneLevel as $item) {
                if ($item->type == 2) {
                    $this->loweLevel[1][] = $item->id;
                }
            }
        }
        // 购买人数
        foreach ($this->loweLevel as $key => $item) {
            $this->count[$key] = [
                'number' => count($item),
                'buy_number' => Order::whereIn('user_id', $item)->where('status', 4)->count()
            ];
        }
        return $this->count;
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
            cache()->add($phone . $request->type, $code, 10);
            $easySms->send($phone, [
                'content' => '',
                'template' => $this->smsTemplate[$request->type],
                'data' => [
                    'code' => $code
                ],
            ]);
            return response()->json(responseFormat(1, '短信已经发送至您手机，请注意查收'));
        } catch (NoGatewayAvailableException $e) {
            return response()->json(responseFormat(0, '发送失败，请稍后再试'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function qr(Request $request)
    {
        $user = User::where('token', $request->token)->first();

        return response()->json(responseFormat(1, '', $user));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function avatarUpload(Request $request)
    {
        $token = $request->token;

        if (strlen($token) ==  64) {
            $file = $request->file('avatar');
            $allowedExtensions = ["png", "jpg", "gif"];
            if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowedExtensions)) {
                return response()->json(responseFormat(0, '不允许的文件类类型'));
            }
            $destinationPath = public_path('avatar');
            $extension = $file->getClientOriginalExtension();
            $fileName = str_random(10) . '.' . $extension;
            $file->move($destinationPath, $fileName);

            $img = Image::make($destinationPath . '/' . $fileName);
            $img->resize(200, 200);
            $img->save($destinationPath . '/' . $fileName);

            User::where('token', $request->token)->update([
                'avatar' => $fileName,
            ]);

            return response()->json(responseFormat(1, '上传成功', $fileName));
        } else {
            return response()->json(responseFormat(0, '上传失败'));
        }

    }

    /**
     * 更新用户资料
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateInfo(Request $request)
    {
        $token = $request->token;
        if (strlen($token) ==  64) {
            $updateData = [];
            if ($request->gender) {
                $updateData['gender'] = $request->gender;
            }
            if ($request->weixi) {
                $updateData['weixi'] = $request->weixi;
            }
            if ($request->nickname) {
                $updateData['nickname'] = $request->nickname;
            }
            User::where('token', $token)->update($updateData);

            return response()->json(responseFormat(1, '修改成功'));
        } else {
            return response()->json(responseFormat(0, '修改失败'));
        }

    }

    /**
     * 修改密码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePassword(Request $request)
    {
        $token = $request->token;
        $password = $request->password;
        $oldPassword = $request->old_password;

        if (strlen($token) == 64) {
            $user = User::where('token', $token)->first();
            if (Auth::attempt(['phone' => $user->phone, 'password' => $oldPassword])) {
                $user->password = bcrypt(trim($password));
                $user->save();
                return response()->json(responseFormat(1, '密码修改成功'));
            } else {
                return response()->json(responseFormat(0, '原密码错误'));
            }
        } else {
            return response()->json(responseFormat(-1, '您的登录信息已过期，请重新登录'));
        }
    }

    /**
     * 账户流水明细
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountFlowDetail(Request $request)
    {
        $offset = ($request->page) * 30;
        $user = User::where('token', $request->token)->first();

        $flow = UserFlow::select('id','user_id', 'relation_user_id', 'date', 'amount', 'type', 'asset_type', 'trade_type', 'created_at', 'user_level')
            ->where('user_id', $user->id)
            ->with('relationUser')
            ->orderBy('id', 'desc')
            ->offset($offset)->limit(30)->get();
            //->paginate(30);

        $responseData = [];
        foreach ($flow as $item) {
            $responseData[] = [
                'id' => $item->id,
                'phone' => $item->relationUser->phone ?? 0,
                'trade_type' => config('userflow.trade_type')[in_array($item->trade_type, [1, 2]) ? $item->trade_type . $item->user_level : $item->trade_type],
                'amount' => $item->amount,
                'created_at' => $item->created_at->format('Y-m-d H:i:s')
            ];
        }
        return response()->json(responseFormat(1, 'success', $responseData));
    }

    /**
     * 话费充值
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recharge(Request $request)
    {
        $phone = $request->phone;
        $card = $request->card;
        $token = $request->token;

        if (strlen($token) == 64) {
            if ($phone && $card) {
                $user = User::where('token', $token)->first();
                if ($user) {
                    $result = (new Phone())->charge($phone, $card);
                    TelephoneFareRecharge::create([
                        'user_id' => $user->id,
                        'phone' => $phone,
                        'card' => $card,
                        'type' => 2,
                        'result_message' => $result['msg'],
                        'result_code' => $result['code'],
                    ]);
                    return response()->json(responseFormat($result['code'], $result['msg']));
                }
            }
        } else {
            return response()->json(responseFormat(-1, '您的登录信息已过期，请重新登录'));
        }
    }

    /**
     * 实名认证
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function idcardUpload(Request $request)
    {
        //header("Access-Control-Allow-Origin:*");
        //file_put_contents("a.txt", json_encode($_POST));
        $realname = $request->realname;
        $idcard = $request->idcard;
        $device = $request->device ? $request->device : "";
        $iccid = $request->iccid ? $request->iccid : "";
        $token = $request->token;
        if (strlen($token) == 64) {
            if ($idcard) {
                $user = User::where('token', $token)->first();
                if ($user) {
                    //$uc = DB::select('SELECT * FROM user_idcard WHERE user_id = :id', ['id' => $user->id]);
                    //if($uc){
                        //return response()->json(responseFormat(5, '此用户已经认证！'));
                    //}
                    //$card = DB::select('SELECT * FROM user_idcard WHERE no = :idno', ['idno' => $idcard]);
                    //if($card){
                        //return response()->json(responseFormat(6, '此身份证已经认证！'));
                    //}

                    $img_path1 = $user->qr_code."1";
                    $img_path2 = $user->qr_code."2";
                    $img_path3 = $user->qr_code."3";

                    $uc = DB::select('SELECT * FROM user_idcard WHERE user_id = :id', ['id' => $user->id]);
                    if($uc){
                        $res1 = DB::table("user_idcard")->where('user_id',$user->id)->update([
                            'realname' => $realname,
                            'no' => $idcard,
                            'status' => 0,
                            'device' => $device,
                            'iccid' => $iccid,
                            'img_path1' => $user->qr_code."1",
                            'img_path2' => $user->qr_code."2",
                            'img_path3' => $user->qr_code."3",
                        ]);
                    }else{
                        $res2 = DB::table('user_idcard')->insertGetId([
                            'user_id' => $user->id,
                            'realname' => $realname,
                            'no' => $idcard,
                            'status' => 0,
                            'device' => $device,
                            'iccid' => $iccid,
                            'img_path1' => $user->qr_code."1",
                            'img_path2' => $user->qr_code."2",
                            'img_path3' => $user->qr_code."3",
                        ]);
                    }
                    $res1 = isset($res1) ? $res1 : 0;
                    $res2 = isset($res2) ? $res2 : 0;
                    if($res1 || $res2){
                        if($request->img1){
                            $this->saveimg($request->img1,$img_path1);
                        }
                        if($request->img2){
                            $this->saveimg($request->img2,$img_path2);
                        }
                        if($request->img3){
                            $this->saveimg($request->img3,$img_path3);
                        }
                        return response()->json(responseFormat(0, ""));
                    }else{
                        return response()->json(responseFormat(1, '信息上传失败！'));
                    }
                }
                else{
                    return response()->json(responseFormat(2, '您的登录信息已过期，请重新登录'));
                }
            }else{
                return response()->json(responseFormat(3, '身份证号码为空'));
            }
        } else {
            return response()->json(responseFormat(2, '您的登录信息已过期，请重新登录'));
        }
    }
    /**
     * 图片上传
     */
    private function saveimg($str,$name){
        $stream = html_entity_decode($str);
        //拆分
        $stream = explode("base64",$stream);
        //文件类型
        $type = explode("/",$stream[0]);
        $type = trim($type[1],";");
        //if(!in_array($type,array("gif","png","jpg","jpeg","bmp"))){
            //return response()->json(responseFormat(4, '；图片格式不正确！'));
        //}
        //数据流
        $stream = $stream[1];
        $path = public_path()."/../storage/app/public/idcard/";
        //生成图片文件
        file_put_contents($path.$name.".jpg",base64_decode($stream));
        $this->water($name.".jpg");
    }

    public function water($file){
        $file = "/data/wwwroot/s.38sd.com/storage/app/public/idcard/".$file;
        if(!file_exists($file)){
            return;
        }
        $water_file = "/data/wwwroot/s.38sd.com/public/shui.png";
        $water = imagecreatefrompng($water_file);
        imagealphablending($water, false);
        imagesavealpha($water, true);
        $img = imagecreatefromjpeg($file);
        $img_w = imagesx($img)*0.3;
        $img_h = imagesy($img)*0.3;
        $water_w = imagesx($water);
        $water_h = imagesy($water);
        imagecopy($img, $water, $img_w, $img_h, 0, 0, $water_w, $water_h);
        imagejpeg($img, $file, 100);
        imagedestroy($img);
        imagedestroy($water);
        return;
    }

    /**
     * 实名认证检测
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function idcardCheck(Request $request){
        $img_server = "http://app.szsousou.com/storage/idcard/";
        $img_pix = ".jpg";
        $token = $request->token;
        if (strlen($token) != 64){
            return response()->json(responseFormat(-1, '您的登录信息已过期，请重新登录'));
        }
        $user = User::where('token', $token)->first();
        if(!$user){
            return response()->json(responseFormat(-1, '您的登录信息已过期，请重新登录'));
        }
        $card = DB::table('user_idcard')->where('user_id', $user->id)->first();
        if($card){
            $data = array(
                'user_id' => $card->user_id,
                'realname' => $card->realname,
                'no' => $card->no,
                'device' => $card->device,
                'iccid' => $card->iccid,
                'img1' => $img_server.$card->img_path1.$img_pix,
                'img2' => $img_server.$card->img_path2.$img_pix,
                'img3' => $img_server.$card->img_path3.$img_pix,
            );
            if($card->status == 0){
                return response()->json(responseFormat(0, '资料审核中', $data));
            }
            if($card->status == 1){
                return response()->json(responseFormat(1, '审核通过', $data));
            }
            if($card->status == 2){
                return response()->json(responseFormat(2, '审核未通过，请填写正确的资料！', $data));
            }
        }else{
            return response()->json(responseFormat(-1, '未绑定'));
        }
    }

    public function chars(Request $request)
    {
        print_r(3);exit;
        return view('backend.user.chars');
    }
}