<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserIntegralSetHwx extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		if (Schema::hasColumn('user_integral_set', 'id')){
		    
			Schema::table('user_integral_set', function($table){
						
				$table->dropColumn('id');
					
			});
				
		}
				
				
		Schema::table('user_integral_set', function($table)
		{
					
			$table->integer('coinid');//全局配置的id
			$table->integer('coinway');//全局配置的方式
				
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
