<?php

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory->create(App\Type::class)->create(['name' => 'App\Judge']);
      factory->create(App\Type::class)->create(['name' => 'App\Directory']);
      factory->create(App\Type::class)->create(['name' => 'App\Choreographer']);
    }
}
