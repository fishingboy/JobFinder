<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //protected $fillable = ['language', 'skill', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        //
        Schema::create('statistics', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('language');
            $table->string('skill');
            $table->integer('Jan');
            $table->integer('Feb');
            $table->integer('Mar');
            $table->integer('Apr');
            $table->integer('May');
            $table->integer('Jun');
            $table->integer('Jul');
            $table->integer('Aug');
            $table->integer('Sep');
            $table->integer('Oct');
            $table->integer('Nov');
            $table->integer('Dec');
            $table->timestamps();
            $table->index("language");
            $table->index("skill");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('statistics');
    }
}
