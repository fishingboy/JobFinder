<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadedField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function ($table)
        {
            $table->tinyInteger('company_readed')->default(0)->comment('已讀狀態: 0 未讀 1 已讀');
        });

        Schema::table('job', function ($table)
        {
            $table->tinyInteger('job_readed')->default(0)->comment('已讀狀態: 0 未讀 1 已讀');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function ($table)
        {
            $table->dropColumn('company_readed');
        });

        Schema::table('job', function ($table)
        {
            $table->dropColumn('job_readed');
        });
    }
}
