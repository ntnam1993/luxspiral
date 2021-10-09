<?php

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotifySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('notify')->truncate();
        $faker = Faker\Factory::create();
        $data = [];
        for ($i=0; $i < 50; $i++) { 
        	$data[] = [
        		'title'	 => $faker->title,
        		'description' => $faker->text,
        		'schedule' => $faker->dateTime($max = 'now', $timezone = null),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
        	];
        };
        Notification::insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
