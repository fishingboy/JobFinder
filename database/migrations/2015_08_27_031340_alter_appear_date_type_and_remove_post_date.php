<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAppearDateTypeAndRemovePostDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::statement("ALTER TABLE `job` CHANGE `appear_date` `appear_date` DATE NULL DEFAULT NULL COMMENT '職務更新日期 格式為YYYYMMDD (年月日)';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::statement("ALTER TABLE `job` DROP `post_date`;");
    }
}
