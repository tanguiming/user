<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserShieldHwx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create('user_shield', function($table)
        { 
			$table->integer('id',true,false);     //表id--11位
			$table->string('token',50);    //token
			$table->string('periodtime',32);    //限制屏蔽时间段
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
