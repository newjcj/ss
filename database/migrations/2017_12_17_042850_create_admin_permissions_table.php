<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('route_name')->comment('路由文件中名称');
            $table->string('name')->comment('权限名称');
            $table->string('description')->comment('描述与备注');
            $table->tinyInteger('level')->comment('级别');
            $table->integer('admin_permissions_group_id')->comment('权限分组');
            $table->string('icon')->comment('图标');
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
        Schema::dropIfExists('admin_permissions');
    }
}
