<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('sound_id')->unsigned();
            $table->foreign('sound_id')->references('id')->on('sound')->onDelete('cascade');
            $table->integer('sound_id_no_answer')->unsigned();
            $table->foreign('sound_id_no_answer')->references('id')->on('sound')->onDelete('cascade');
            $table->dateTime('schedule');
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
        Schema::dropIfExists('delivery');
    }
}
