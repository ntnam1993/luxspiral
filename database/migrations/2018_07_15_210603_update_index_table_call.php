<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIndexTableCall extends Migration
{
    protected $table = '`call`';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->table, function() {
            $table = $this->table;
            DB::statement( "ALTER TABLE $table ADD INDEX `twilio_index` (`twilio_call_id`)" );
            DB::statement( "ALTER TABLE $table ADD INDEX `status_index` (`status`)" );
            DB::statement( "ALTER TABLE $table ADD INDEX `time_call_index` (`time_call`)" );
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
