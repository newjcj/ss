<?php
namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\Goods;
use App\Models\User;
use DB;

class Rebate
{
    public $agent = [];

    /**
     * 推广员返利计算
     * @param $buyUserId
     * @param $orderNo
     * @param $goodsId
     * @return bool
     * @throws CustomException
     */
    public function promotionSpecialist($buyUserId, $orderNo, $goodsId, $quantity)
    {
        if( Goods::find($goodsId)->vocational == '' ){
            return true;
        }
        DB::beginTransaction();

        try {
            // 获取商品的返利设置
            $goods = Goods::where('id', $goodsId)->lockForUpdate()->first();
            // 查找购买用户的上级
            $one = User::where('id', $buyUserId)->first();
            if ($one && $one->parent_id != 0) {
                // 添加流水给当前用户加上对应的积分
                (new UserFlow($one->parent_id, $orderNo, bcmul($goods->seller_one_integral, $quantity),  3, 1, 1, $buyUserId))
                    ->income();

                $two = User::where('id', $one->parent_id)->first();
                if ($two && $two->parent_id != 0) {
                    // 添加流水给当前用户加上对应的积分
                    (new UserFlow($two->parent_id, $orderNo, bcmul($goods->seller_two_integral, $quantity), 3, 1, 2, $buyUserId))
                        ->income();

                    $three = User::where('id', $two->parent_id)->first();
                    if ($two && $three->parent_id != 0) {
                        // 添加流水给当前用户加上对应的积分
                        (new UserFlow($three->parent_id, $orderNo, bcmul($goods->seller_three_integral, $quantity), 3, 1, 3, $buyUserId))
                            ->income();
                    }
                }
            }
        } catch (CustomException $exception) {
            DB::rollBack();
            throw new CustomException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 运营商返利计算
     * @param $buyUserId
     * @param $orderNo
     * @param $goodsId
     * @param $quantity
     * @return bool
     * @throws CustomException
     */
    public function operators($buyUserId, $orderNo, $goodsId, $quantity)
    {
        if( Goods::find($goodsId)->vocational == ''){
            return true;
        }
        DB::beginTransaction();

        try {
            // 获取商品的返利设置
            $goods = Goods::where('id', $goodsId)->lockForUpdate()->first();

            $one = $goods->agent_one_integral;
            $two = $goods->agent_two_integral;
            $three = $goods->agent_three_integral;
            $total = $one + $two + $three;

            $buyUserParent = User::find($buyUserId);
            // 如果自己也是代理商则从自己开始算积分
            if ($buyUserParent->special_type != 0) {
                $this->findParent($buyUserParent->id);
            } else {
                $this->findParent($buyUserParent->parent_id);
            }
            // 如果一二级都有则按照商品设置加分
            if(isset($this->agent[1]) && isset($this->agent[2])) {
                (new UserFlow($this->agent[1], $orderNo, bcmul($one, $quantity), 3, 2, 1, $buyUserId))->income();
                (new UserFlow($this->agent[2], $orderNo, bcmul($two, $quantity), 3, 2, 2, $buyUserId))->income();
                (new UserFlow($this->agent[3], $orderNo, bcmul($three, $quantity), 3, 2, 3, $buyUserId))->income();
            }
            // 没有二级有一级则一级拿自己与二级的和，运营商拿自己那部分
            if(!isset($this->agent[2]) && isset($this->agent[1]) && isset($this->agent[3])) {
                (new UserFlow($this->agent[1], $orderNo, bcmul(bcadd($one, $two) , $quantity), 3, 2, 1, $buyUserId))->income();
                (new UserFlow($this->agent[3], $orderNo, bcmul($three, $quantity), 3, 2, 3, $buyUserId))->income();
            }
            // 只有二级没有一级则二级拿自己的部分，运营商拿一级与自己那部分
            if (isset($this->agent[2]) && !isset($this->agent[1])) {
                (new UserFlow($this->agent[2], $orderNo, bcmul($two , $quantity), 3, 2, 2, $buyUserId))->income();
                (new UserFlow($this->agent[3], $orderNo, bcmul(bcadd($one, $three), $quantity), 3, 2, 3, $buyUserId))->income();
            }
            // 一二级都没有，则所有都归运营商
            if (!isset($this->agent[1]) && !isset($this->agent[2])) {
                (new UserFlow($this->agent[3], $orderNo, bcmul($total, $quantity), 3, 2, 3, $buyUserId))->income();
            }
        } catch (CustomException $exception) {
            DB::rollBack();
            throw new CustomException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function findParent($userId)
    {
        // 获取上级的用户信息
        $parent = User::where('id', $userId)->first();
        if ($parent->special_type == 3) {
            $this->agent[3] = $parent->id;
        }
        // 如果先找到一级则忽略二级
        if ($parent->special_type == 1 && !isset($this->agent[1]) && !isset($this->agent[2])) {
            $this->agent[1] = $parent->id;
        }
        if ($parent->special_type == 2 && !isset($this->agent[2]) && !isset($this->agent[1])) {
            $this->agent[2] = $parent->id;
        }
        if ($parent->special_type == 1 && isset($this->agent[2]) && !isset($this->agent[1])) {
            $this->agent[1] = $parent->id;
        }
        if ($parent->parent_id != 0 && $parent->special_type != 3) {
            $this->findParent($parent->parent_id);
        }
    }
}