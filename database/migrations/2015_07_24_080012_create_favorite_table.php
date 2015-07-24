<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite', function(Blueprint $table){
            $table->increments('favoriteID');
            $table->integer('type')->comment('類別: 1.工作 2.公司');
            $table->integer('resID')->comment('資源流水號，根據類別去對應 jobID 或 companyID');
            $table->integer('sn')->comment('順序');
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
        Schema::drop('favorite');
    }
}
