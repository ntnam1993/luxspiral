<?php

use Illuminate\Database\Seeder;
use App\Models\Sound;

class SoundSeeder extends Seeder
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
                'url'	 => $faker->url,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];
        };
        Sound::insert($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
