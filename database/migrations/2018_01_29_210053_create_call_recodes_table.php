<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallRecodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_recodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account')->comment('计费帐号，格式为："注册的号码" ');
            $table->string('leg')->comment('aleg为主叫话单，bleg为被叫话单，若被叫未来得及建立而被挂断，会没有被叫话单生成 ');
            $table->string('caller')->comment('显示的号码');
            $table->string('callee')->comment('被叫号码');
            $table->string('create_time')->comment('请求时间，此时间仅供数据参考，不参与话单逻辑，时间可能会与通话系统时间稍微不同 ');
            $table->string('ring_time')->comment('振铃开始时间, 未响铃挂断时间格式： 0001-01-01 00:00:00 ');
            $table->string('answer_time')->comment(' 接通时间，未接通时间格式： 0001-01-01 00:00:00 ');
            $table->string('end_time')->comment('结束时间，未接通时间格式: 0001-01-01 00:00:00');
            $table->string('bill_sec')->comment('计费时长, 不足60秒按60秒计费');
            $table->string('bill_rate')->comment('计费费率');
            $table->string('bill_total')->comment('总计费');
            $table->string('hangup')->comment('// 挂断事件值，"DISCONNECTED" 用户接听后成功挂断；"FAILED" 呼叫失败；"CANCEL"用户取消。');
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
        Schema::dropIfExists('call_recodes');
    }
}
