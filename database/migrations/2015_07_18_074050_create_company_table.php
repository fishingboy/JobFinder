<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company', function(Blueprint $table){
			$table->increments('companyID');
			$table->string('c_code','200');
			$table->string('name','200');
			$table->string('addr_no_descript','200')->nullable();
			$table->string('address','200')->nullable();
			$table->string('addr_indzone','200')->nullable();
			$table->string('indcat','200')->nullable();
			$table->string('link','200')->nullable();
			$table->text('product')->nullable();
			$table->text('profile')->nullable();
			$table->text('welfare')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company');
	}
}
