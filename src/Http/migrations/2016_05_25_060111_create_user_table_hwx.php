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
			$table->integer('id',true,false);     //��id--11λ
			$table->integer('activityid'); //�������û�����Ӧ���е�id
			$table->string('token',50);    //token
			$table->string('openid',32);    //���û�openid
			$table->integer('time'); //��ӻ���ʱ��
			$table->string('integral',50);    //�������ֹ����ƴ��
			$table->mediumText('describe'); //����
			$table->integer('periodtime'); //��������ʱ���
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
