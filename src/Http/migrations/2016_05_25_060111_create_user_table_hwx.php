<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTableHwx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('user_table', function($table)
        { 
			$table->integer('id',true,false);     //表id--11位
			$table->integer('activityid'); //增积分用户所在应用中的id
			$table->string('token',50);    //token
			$table->string('openid',32);    //该用户openid
			$table->integer('time'); //添加积分时间
			$table->string('integral',50);    //关联积分规则的拼音
			$table->mediumText('describe'); //描述
			$table->integer('periodtime'); //限制屏蔽时间段
			$table->engine = 'InnoDB';
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
