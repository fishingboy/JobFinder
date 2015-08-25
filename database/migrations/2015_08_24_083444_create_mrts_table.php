<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMrtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('mrts', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('uid');
            $table->string('line', 64);
            $table->string('name', 128);
            $table->string('address');
            $table->string('area', 128);
            $table->float('lat');
            $table->float('lng');
            $table->timestamps();
            $table->unique(['uid','area']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mrts');
    }
}
