<?php

use Illuminate\Database\Seeder;

class CaptionWeightingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(App\CaptionWeighting::class)->create(['id' => 1, 'name' => '60/40']);
      factory(App\CaptionWeighting::class)->create(['id' => 2, 'name' => '50/50']);
    }
}
