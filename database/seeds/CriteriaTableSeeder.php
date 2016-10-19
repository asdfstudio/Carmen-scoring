<?php

use Illuminate\Database\Seeder;

class CriteriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
				// Music
				$names = [
					'Tone & Technique',
					'Intonation',
					'Balance & Blend',
					'Clarity',
					'Dynamics',
					'Diction',
					'Rhythm & Precision',
					'Musicality & Interpretation',
					'Stylistic Authenticity',
					'Consistency',
					'Difficulty',
					'Innovation'
				];
				
				$rows = [];
				
				foreach($names as $name)
				{
					$rows[] = ['caption_id' => 1, 'name' => $name];
				}
				
				DB::table('criteria')->insert($rows);
				
				// Show
				$names = [
					'Staging',
					'Stylistic Authenticity & Choreographic Content',
					'Appearance',
					'Transitions & Pacing',
					'Precision & Execution',
					'Poise',
					'Communication & Expression',
					'Objectives & Atmosphere',
					'Entertainment Value',
					'Consistency',
					'Difficulty',
					'Innovation',
					// Novice only
					'Staging & Transitions',
					'Appearance & Poise'
				];
				
				
				$rows = [];
				
				foreach($names as $name)
				{
					$rows[] = ['caption_id' => 2, 'name' => $name];
				}
				
				DB::table('criteria')->insert($rows);
			
        
    }
}
