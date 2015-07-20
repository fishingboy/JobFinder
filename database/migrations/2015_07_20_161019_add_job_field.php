<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job', function ($table)
        {
            $table->index('companyID');
            $table->string('source')->nullable()->comment('資料來源 ex:104, ptt');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job', function ($table)
        {
            $table->dropIndex('job_companyid_index');
            $table->dropColumn('source');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
