<?php

namespace App\Http\Controllers\Backend;

use App\Exceptions\CustomException;
use App\Models\User;
use App\Service\UserFlow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

/**
 * Class OrderController
 * @package App\Http\Controllers\Backend
 */
class UserController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $phone = $request->phone;
        $role = $request->role;
        $type = $request->type;

        $users = User::filter(compact('type', 'role', 'phone'))->with('parent')->orderBy('id', 'desc')->paginate(20);

        return view('backend.user.index', compact('users', 'type', 'role', 'phone'));
    }

    /**
     * 用户列表
     * @param Request $request
     * @return mixed
     */
    public function userList(Request $request)
    {
        $pageSize = $request->limit;

        $goods = User::with('parent')->orderBy('id', 'desc')->paginate($pageSize ?? 20);

        return response()->json([
            "code" => 0,
            "msg" => "",
            "count" => $goods->total(),
            "data" => $goods->items()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeRole(Request $request)
    {
        $updateData = [
            'special_type' => $request->special_type,
            'type' => $request->type,
        ];

        if ($request->parent_phone != '') {
            $parentId = User::where('phone', $request->parent_phone)->value('id');
            if ($parentId) {
                $updateData['parent_id'] = $parentId;
            } else {
                return response()->json(responseFormat(0, '上级不存在'));
            }
        }
        User::where('id', $request->id)->update($updateData);
        return response()->json(responseFormat(1, '修改成功'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeAsset(Request $request)
    {
        $userId = $request->id;
        $assetType = $request->asset_type;
        $type = $request->type;
        $amount = $request->amount;

        try {
            if ($assetType == 3 && $type == 1) { // 增加积分

                (new UserFlow($userId, generateOrderNo(), $amount, $assetType, 24))->income();
            } else if ($assetType == 3 && $type == 2) { // 减少积分

                (new UserFlow($userId, generateOrderNo(), $amount, $assetType, 25))->expend();
            } else if($assetType == 4 && $type == 1) { // 增加学分

                (new UserFlow($userId, generateOrderNo(), $amount, $assetType, 26))->income();
            } else if($assetType == 4 && $type == 2) { // 减少学分

                (new UserFlow($userId, generateOrderNo(), $amount, $assetType, 27))->expend();
            }

        } catch (CustomException $customException) {
            return response()->json(responseFormat(0, $customException->getMessage()));
        }
        return response()->json(responseFormat(1, '修改成功'));
    }

    public function idcardCheck(Request $request){
        $type = isset($request->type) ? $request->type : 4;
        if($type == 4){
            $cards = DB::table('user_idcard as ui')->select('ui.*','u.phone')
            ->leftJoin('users as u', function($join){
                $join->on('ui.user_id', '=', 'u.id');
            })->orderBy('ui.id', 'desc')->paginate(10);;
            //$cards = DB::table('user_idcard')->orderBy('id', 'desc')->paginate(10);
        }else{
            $cards = DB::table('user_idcard as ui')->select('ui.*','u.phone')
            ->leftJoin('users as u', function($join){
                $join->on('ui.user_id', '=', 'u.id');
            })->where("ui.status",$type)->orderBy('ui.id', 'desc')->paginate(10);;
            //$cards = DB::table('user_idcard')->where("status",$type)->orderBy('id', 'desc')->paginate(10);
        }
        return view('backend.user.idcard', compact('cards','type'));
    }

    public function iddocheck(Request $request){
        $id = $request->id;
        $status = $request->status;

        $res = DB::table('user_idcard')
            ->where('id', $id)
            ->update(['status' => $status]);
        if($res){
            echo json_encode(array(
            "status"=>0));
        }else{
            echo json_encode(array(
            "status"=>1,
            "msg"=>"操作失败"));
        }
    }
}
