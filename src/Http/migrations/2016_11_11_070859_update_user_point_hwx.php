<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserPointHwx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::table('user_point', function($table)
        {
			$table->integer('coinid');    //全局配置关联的id
			$table->integer('classid'); //积分规则分类关联的id
			$table->integer('reward_bean'); //奖励豆
			$table->integer('deduct_bean'); //扣除豆
			$table->integer('enabled'); //启用状态
			$table->integer('addtime'); //添加时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
