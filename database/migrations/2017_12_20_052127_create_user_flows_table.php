<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('user_level')->comment('用级的级别');
            $table->string('voucher_id', 22)->comment('单据号');
            $table->tinyInteger('type')->comment('流水类型 1 支出 2 收入 3 提现 4 冻结 5 解冻');
            $table->tinyInteger('asset_type')->comment('资产类型 1 积分 2 学分 3 人民币');
            $table->tinyInteger('trade_type')->comment('交易类型');
            $table->decimal('amount', 10, 2)->comment('本条流水发生的金额');
            $table->decimal('before_frozen', 10, 2)->comment('本条流水发生前冻结余额');
            $table->decimal('after_frozen', 10, 2)->comment('本条流水发生后冻结余额');
            $table->decimal('before_balance', 10, 2)->comment('本条流水发生后余额');
            $table->decimal('after_balance', 10, 2)->comment('本条流水发生后余额');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_flows');
    }
}
