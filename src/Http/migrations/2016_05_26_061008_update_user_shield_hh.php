<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserShieldHh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		if (Schema::hasColumn('user_shield', 'periodtime')){
		    
			Schema::table('user_shield', function($table){
						
				$table->dropColumn('periodtime');
					
			});
				
		}
				
				
		Schema::table('user_shield', function($table)
		{
					
			$table->string('periodtimestart',120);
			$table->string('periodtimend',120);
				
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
