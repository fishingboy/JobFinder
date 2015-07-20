<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyField extends Migration
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
            $table->string('employees')->nullable()->comment('員工人數');
            $table->string('capital')->nullable()->comment('資本額');
            $table->string('url')->nullable()->comment('公司網址');
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
        Schema::table('company', function ($table)
        {
            $table->dropColumn('employees');
            $table->dropColumn('capital');
            $table->dropColumn('url');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
