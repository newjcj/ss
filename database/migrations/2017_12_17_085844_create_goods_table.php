<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('商品标题');
            $table->decimal('price', 2)->comment('售价');
            $table->decimal('original_price', 2)->comment('原价');
            $table->decimal('carriage', 2)->default(0)->comment('运费');
            $table->integer('stock_quantity')->default(999)->comment('库存数');
            $table->integer('sale_quantity')->default(0)->comment('已经销售');
            $table->decimal('seller_one_integral', 2)->default(0)->comment('一级销售员可得激励积分');
            $table->decimal('seller_two_integral', 2)->default(0)->comment('二级销售员可得激励积分');
            $table->decimal('seller_three_integral', 2)->default(0)->comment('三级销售员可得激励积分');
            $table->decimal('agent_one_integral', 2)->default(0)->comment('一级代理可得激励积分');
            $table->decimal('agent_two_integral', 2)->default(0)->comment('二级代理员可得激励积分');
            $table->decimal('agent_three_integral', 2)->default(0)->comment('二级代理员可得激励积分');
            $table->integer('sort')->default(999)->comment('排序');
            $table->integer('status')->default(1)->comment('状态 1 没生效 2 生效');
            $table->text('desc')->comment('商品介绍');
            $table->text('detail')->comment('商品详情');
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
        Schema::dropIfExists('goods');
    }
}
