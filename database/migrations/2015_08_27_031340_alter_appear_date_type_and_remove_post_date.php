<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\Statement;

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
        DB:Statement("ALTER TABLE `job` CHANGE `appear_date` `appear_date` DATE NULL DEFAULT NULL COMMENT '職務更新日期 格式為YYYYMMDD (年月日)';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB:Statement("ALTER TABLE `job` DROP `post_date`;");
    }
}
