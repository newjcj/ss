<?php
namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\User;
use DB;

class UserFlow
{
    const USER_ASSET_TYPE_CREDIT = 4; // 学分
    const USER_ASSET_TYPE_INTEGRAL = 3; // 积分

    /**
     * @var
     */
    protected $userId;

    /**
     * @var
     */
    protected $relationUserId;

    /**
     * @var
     * @param integer $type 流水类型  1 支出 2 收入 3 提现 4 冻结 5 解冻
     */
    protected $type;

    /**
     * @var null
     */
    protected $userAsset = null;

    protected $assetType;

    protected $beforeFrozen;

    protected $afterFrozen;

    protected $beforeBalance;

    protected $afterBalance;

    protected $poundage = 0;

    /**
     * UserFlow constructor.
     * @param integer $userId 操作那一个用户的资产
     * @param integer $relationUserId 关联的用户
     * @param string $orderNo 关联的订单号
     * @param integer $amount 金额
     * @param integer $assetType 操作的资产类型 1 支付宝 2 微信 3 积分 4 学分
     * @param integer $tradeType 交易类型 1 积分返利 2 运营商返利 3 购买商品 4 学分转赠 5 积分转赠 6 学分提现 7 积分提现
     * @param int $level 如是是返利则有用户级别
     */
    public function __construct($userId, $orderNo, $amount, $assetType, $tradeType, $level = 0, $relationUserId = 0)
    {
        $this->userId = $userId;
        $this->relationUserId = $relationUserId;
        $this->orderNo = $orderNo;
        $this->amount = $amount;
        $this->assetType = $assetType;
        $this->tradeType = $tradeType;
        $this->level = $level;
        $this->getUserAsset();
    }

    /**
     * 收入
     * @throws CustomException
     */
    public function income()
    {
        DB::beginTransaction();

        // 流水收入
        $this->type = 1;

        try {
            if ($this->userAsset == null) {
                throw new CustomException('用户资产不存在');
            }

            if ($this->assetType == self::USER_ASSET_TYPE_INTEGRAL) {
                $this->beforeBalance = $this->userAsset->integral;
                $this->beforeFrozen = $this->userAsset->integral_frozen;
                $this->afterFrozen = $this->userAsset->integral_frozen;

                $this->afterBalance =  bcadd($this->userAsset->integral, $this->amount);

                $this->userAsset->integral = $this->afterBalance;
                $this->userAsset->integral_frozen = $this->afterFrozen;

            } else if ($this->assetType == self::USER_ASSET_TYPE_CREDIT) {
                $this->beforeBalance = $this->userAsset->credit;
                $this->afterBalance =  bcadd($this->userAsset->credit, $this->amount);
                $this->beforeFrozen = $this->userAsset->credit_frozen;
                $this->afterFrozen = $this->userAsset->credit_frozen;

                $this->userAsset->credit = $this->afterBalance;
                $this->userAsset->credit_frozen = $this->afterFrozen;
            }

            if (!$this->userAsset->save()) {
                throw new CustomException('更新用户资产失败');
            }
            // 写入流水
            $this->createUserAmountFlow();
            // 如果是返利则扣手续费
            if (in_array($this->tradeType, [1, 2])) {
                $this->poundage = bcmul($this->amount, 0.08);
                (new UserFlow($this->userId, $this->orderNo, $this->poundage,  $this->assetType, 8, $this->level, $this->relationUserId))->expend();
            }
        } catch (CustomException $exception) {
            DB::rollBack();
            throw new CustomException($exception->getMessage());
        }
        DB::commit();
    }

    /**
     * 支出
     */
    public function expend()
    {
        DB::beginTransaction();

        // 支出
        $this->type = 2;

        try {
            if ($this->userAsset == null) {
                throw new CustomException('用户资产不存在');
            }

            if ($this->assetType == self::USER_ASSET_TYPE_INTEGRAL) {
                $this->beforeBalance = $this->userAsset->integral;
                $this->afterBalance =  bcsub($this->userAsset->integral, $this->amount);
                if ($this->afterBalance < 0) {
                    throw new CustomException('积分不足');
                }
                $this->beforeFrozen = $this->userAsset->integral_frozen;
                $this->afterFrozen = $this->userAsset->integral_frozen;
                $this->amount = -$this->amount;

                $this->userAsset->integral = $this->afterBalance;
                $this->userAsset->integral_frozen = $this->afterFrozen;

            } else if ($this->assetType == self::USER_ASSET_TYPE_CREDIT) {
                $this->beforeBalance = $this->userAsset->credit;
                $this->afterBalance =  bcsub($this->userAsset->credit, $this->amount);
                if ($this->afterBalance < 0) {
                    throw new CustomException('学分不足');
                }
                $this->beforeFrozen = $this->userAsset->credit_frozen;
                $this->afterFrozen = $this->userAsset->credit_frozen;
                $this->amount = -$this->amount;

                $this->userAsset->credit = $this->afterBalance;
                $this->userAsset->credit_frozen = $this->afterFrozen;
            }

            if (!$this->userAsset->save()) {
                throw new CustomException('更新用户资产失败');
            }
            // 写入流水
            $this->createUserAmountFlow();
        } catch (CustomException $exception) {
            DB::rollBack();
            throw new CustomException($exception->getMessage());
        }
        DB::commit();
        return true;
    }

    /**
     * 提现
     */
    public function withdraw()
    {

    }

    /**
     * @throws CustomException
     */
    public function getUserAsset()
    {
        $this->userAsset =  User::where('id', $this->userId)->lockForUpdate()->first();
        \Log::alert(json_encode([$this->userAsset, $this->userId]));
    }

    /**
     * 写入用户资产流水
     */
    public function createUserAmountFlow()
    {
        DB::beginTransaction();
        try {
            \App\Models\UserFlow::create([
               'user_id' => $this->userId,
               'user_level' => $this->level,
               'voucher_id' => $this->orderNo,
               'type' => $this->type,
               'asset_type' => $this->assetType,
               'trade_type' => $this->tradeType,
               'amount' => $this->amount,
               'before_frozen' => $this->beforeFrozen,
               'after_frozen' => $this->afterFrozen,
               'before_balance' => $this->beforeBalance,
               'after_balance' => $this->afterBalance,
               'relation_user_id' => $this->relationUserId,
               'date' => date('Y-m-d'),
               'poundage' => $this->poundage,
            ]);
        } catch (CustomException $exception) {
            DB::rollBack();
            throw new CustomException($exception->getMessage());
        }
        DB::commit();
        return true;
    }
}