<?php

use Illuminate\Database\Seeder;

class CaptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Caption::class)->create(['name' => 'Music']);
        factory(App\Caption::class)->create(['name' => 'Show']);
        factory(App\Caption::class)->create(['name' => 'Combo']);
    }
}
