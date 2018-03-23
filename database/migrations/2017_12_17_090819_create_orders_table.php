<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no', 22)->comment('订单号');
            $table->integer('users_id', false, true)->comment('用户ID');
            $table->integer('goods_id', false, true)->comment('商品ID');
            $table->string('goods_title')->comment('商品标题');
            $table->integer('quantity', false, true)->default(0)->comment('数量');
            $table->decimal('price', 10, 3)->default(0)->comment('订单单价');
            $table->decimal('amount', 10, 3)->default(0)->comment('订单金额');
            $table->tinyInteger('status')->default(1)->comment('订单状态 1 待支付 2 订单完成 3 订单取消');
            $table->tinyInteger('mode_of_payment', false, true)->default(0)->comment('支付方式 1 微信 2 支付宝 3 学分 4 积分');
            $table->decimal('payment', 10, 3)->default(0)->comment('支付金额或学分');
            $table->dateTime('time_of_payment')->comment('支付时间');
            $table->tinyInteger('logistics_mode', false, true)->comment('物流方式 1 自提 2 快递');
            $table->string('receiving_address')->nullable()->comment('收货人地址');
            $table->string('receiving_phone')->nullable()->comment('收货人手机');
            $table->string('receiving_name')->nullable()->comment('收货人名字');
            $table->string('remark')->nullable()->comment('客户备注');
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
        Schema::dropIfExists('orders');
    }
}
