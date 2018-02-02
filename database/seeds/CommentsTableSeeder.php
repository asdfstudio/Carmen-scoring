<?php

use Illuminate\Database\Seeder;

use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = Comment::all();

        foreach ($comments as $comment) {
          $comment->recipient_type = 'App\Choir';
          $comment->recipient_id = $comment->choir_id;
          $comment->save();
        }
    }
}
