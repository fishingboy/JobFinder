<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPostDateToJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
    	DB::statement("ALTER TABLE `job` ADD `post_date` DATETIME NULL DEFAULT CURRENT_TIMESTAMP COMMENT '張貼時間' AFTER `source_url`;");
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
