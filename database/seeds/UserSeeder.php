<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $demo = App\Organization::where('name', 'Demo Organization')->first();

      factory(App\User::class)->create([
        'username' => 'afjennings',
        'email' => 'afjennings@gmail.com',
        'password' => bcrypt('afjennings'),
        'organization_id' => $demo->id,
        'is_admin' => TRUE
      ]);

      factory(App\User::class)->create([
        'email' => 'carmenshowchoir@gmail.com',
        'password' => bcrypt('carmen'),
        'organization_id' => $demo->id,
        'is_admin' => TRUE
      ]);

      factory(App\User::class, 10)->create();
    }
}
