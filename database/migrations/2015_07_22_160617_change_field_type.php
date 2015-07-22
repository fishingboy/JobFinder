<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldType extends Migration
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
            $table->text('product')->change();
            $table->text('profile')->change();
            $table->text('welfare')->change();
            $table->bigInteger('capital')->change();
        });

        Schema::table('job', function ($table)
        {
            $table->text('description')->change();
            $table->text('pcskill_all_desc')->change();
            $table->text('others')->change();
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
            $table->string('product', '200')->change();
            $table->string('profile', '200')->change();
            $table->string('welfare', '200')->change();
            $table->integer('capital')->change();
        });

        Schema::table('job', function ($table)
        {
            $table->string('description', '200')->change();
            $table->string('pcskill_all_desc', '200')->change();
            $table->string('others', '200')->change();
        });
    }
}
