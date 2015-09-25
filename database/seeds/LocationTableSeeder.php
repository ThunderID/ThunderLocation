<?php

use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        with(new \App\Location(['name' => 'Indonesia', 'level' => 'Country', 'longitude' => 0.8, 'latitude' => 0.3214]))->save();
    }
}
