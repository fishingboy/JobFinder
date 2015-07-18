<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('job', function(Blueprint $table){
			$table->increments('jobID');
			$table->integer('companyID')->nullable();
			$table->string('title','200');
			$table->string('j_code','200')->nullable();
			$table->string('job_addr_no_descript','200')->nullable();
			$table->string('job_address','200')->nullable();
			$table->string('jobcat_descript','200')->nullable();
			$table->string('description','200')->nullable();
			$table->integer('period')->nullable();
			$table->string('appear_date','200')->nullable();
			$table->integer('dis_role')->nullable();
			$table->integer('dis_level')->nullable();
			$table->integer('dis_role2')->nullable();
			$table->integer('dis_level2')->nullable();
			$table->integer('dis_role3')->nullable();
			$table->integer('dis_level3')->nullable();
			$table->string('driver','200')->nullable();
			$table->string('handicompendium','200')->nullable();
			$table->string('role','200')->nullable();
			$table->string('role_status','200')->nullable();
			$table->string('s2','200')->nullable();
			$table->string('s3','200')->nullable();
			$table->string('sal_month_low','200')->nullable();
			$table->string('sal_month_high','200')->nullable();
			$table->string('worktime','200')->nullable();
			$table->string('startby','200')->nullable();
			$table->string('cert_all_descript','200')->nullable();
			$table->string('jobskill_all_desc','200')->nullable();
			$table->string('pcskill_all_desc','200')->nullable();
			$table->string('language1','200')->nullable();
			$table->string('language2','200')->nullable();
			$table->string('language3','200')->nullable();
			$table->string('lat','200')->nullable();
			$table->string('lon','200')->nullable();
			$table->string('major_cat_descript','200')->nullable();
			$table->string('minbinary_edu','200')->nullable();
			$table->string('need_emp','200')->nullable();
			$table->string('need_emp1','200')->nullable();
			$table->string('ondutytime','200')->nullable();
			$table->string('offduty_time','200')->nullable();
			$table->string('others','200')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('job');
	}
}
