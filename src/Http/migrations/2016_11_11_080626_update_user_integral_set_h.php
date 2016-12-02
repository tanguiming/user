<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserIntegralSetH extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		if (Schema::hasColumn('user_integral_set', 'coinid')){
		    
			Schema::table('user_integral_set', function($table){
						
				$table->dropColumn('coinid');
					
			});
				
		}
				
				
		Schema::table('user_integral_set', function($table)
		{
			$table->integer('coinid',true,false);     //表id--11位
				
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
