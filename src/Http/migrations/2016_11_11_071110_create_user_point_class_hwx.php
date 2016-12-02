<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPointClassHwx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //11.11新家表：积分规则分类表。不同分类表下是对应的积分规则
		Schema::create('user_point_class', function($table)
        {
			$table->integer('classid',true,false);     //表id--11位
			$table->string('token',100);    //token
			$table->string('classtitle',100);    //规则分类名
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
