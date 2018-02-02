<?php

use Illuminate\Database\Seeder;

use App\CommentUrl;

class CommentUrlsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $commentUrls = CommentUrl::all();

      foreach ($commentUrls as $commentUrl) {
        $commentUrl->recipient_type = 'App\Choir';
        $commentUrl->recipient_id = $commentUrl->choir_id;
        $commentUrl->save();
      }
    }
}
