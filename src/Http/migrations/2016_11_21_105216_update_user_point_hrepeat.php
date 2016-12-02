<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserPointHrepeat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		if (Schema::hasColumn('user_point', 'token')){
		    
			Schema::table('user_point', function($table){
						
				$table->dropColumn('token');
					
			});
				
		}
				
				
		Schema::table('user_point', function($table)
		{
			$table->string('token', 100);
				
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
