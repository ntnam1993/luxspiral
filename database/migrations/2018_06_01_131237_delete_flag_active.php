<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteFlagActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('answer', function (Blueprint $table) {
                $table->dropColumn('flag_active');
        });
        Schema::table('delivery', function (Blueprint $table) {
                $table->dropColumn('flag_active');
        });
        Schema::table('notify', function (Blueprint $table) {
                $table->dropColumn('flag_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
