<?php

use Illuminate\Database\Seeder;
use App\Models\Delivery;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('delivery')->truncate();
        factory(Delivery::class, 40)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
