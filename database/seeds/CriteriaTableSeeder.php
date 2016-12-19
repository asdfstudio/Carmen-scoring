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
				// MusicDemonstration of support, release of tension, tone quality, and other techniques associated with trained singing
				$names = [
					'Tone & Technique' => 'Demonstration of support, release of tension, tone quality, and other techniques associated with trained singing',
					'Intonation' => 'Choir is in tune when singing in unison and in parts',
					'Balance & Clarity' => 'Relative volume of voice parts and individuals is well proportioned, unobtrusive, and is perceptible throughout venue, achieved by combination of tone, intensity, and articulation',
					'Blend' => 'Unification of vowels and tone quality are achieved with placement and conformity',
					'Dynamics' => 'Dynamic contrast is appropriate and used with control',
					'Diction' => 'Lyrics are understood; consonants and vowels are natural and appropriate',
					'Rhythm & Precision' => 'Choir demonstrates rhythmic integrity, synchronized onsets, and uniform releases',
					'Musicality & Interpretation' => 'Elements of phrasing, sensitivity, and nuance are present',
					'Stylistic Authenticity' => 'Ensemble uses distinctive sounds according to song genre choice',
					'Consistency' => 'Unification and conformity of good vocal technique is maintained throughout performance',
					'Difficulty' => 'Level of skill and work needed to successfully perform music',
					'Innovation' => 'Materials pertaining to Music are creative, effective, and original'
				];

				$rows = [];

				foreach($names as $name => $desc)
				{
          $rows[] = [
            'caption_id' => 1,
            'name' => $name,
            'description' => $desc
          ];
				}

				DB::table('criteria')->insert($rows);

				// Show
				$names = [
					'Staging' => 'Ensemble achieves spacing and layering that compliment visual effect of performance',
					'Stylistic Authenticity & Choreographic Content' => 'Ensemble fully performs distinctive aesthetic movements according to song genre choice',
					'Appearance' => "Ensemble’s costuming and grooming are assembled well and enhance presentation",
					'Transitions & Pacing' => 'Performance flow is uninterrupted and reaches a peak',
					'Precision & Execution' => 'Choreography and movements are presented with energy and commitment, and are rhythmically synchronized',
					'Poise' => 'Ensemble demonstrates professionalism and confidence',
					'Communication & Expression' => 'Emotion, characterization, and intent are clear, true, and believable',
					'Objectives & Atmosphere' => 'Performers create a cohesive environment that engages audience',
					'Entertainment Value' => "Ensemble’s presentation of choreography, costuming, accompaniment, and, if they are present, sets, props, and special effects combines to enhance performance",
					'Consistency' => 'Unification and conformity of quality dance technique is maintained throughout performance',
					'Difficulty' => 'Level of skill and work needed to successfully perform show',
					'Innovation' => 'Materials presented in Show are creative, effective, and original',
					// Novice only
					'Staging & Transitions' => 'Spacing and layering of the ensemble in relation to stage compliment visual effect, and performance flow is uninterrupted with climax',
					'Appearance & Poise' => 'Costuming and grooming are organized, and ensemble demonstrates professionalism and confidence'
				];


				$rows = [];

				foreach($names as $name => $desc)
				{
          $rows[] = [
            'caption_id' => 2,
            'name' => $name,
            'description' => $desc
          ];
				}

				DB::table('criteria')->insert($rows);


        // Add combo criteria for music and show
        $rows = [];

				$rows[] = [
          'caption_id' => 1,
          'name' => 'Accompaniment',
          'description' => 'Accompaniment facilitates and enhances all aspects of Music'
        ];
        $rows[] = [
          'caption_id' => 2,
          'name' => 'Accompaniment',
          'description' => 'Accompaniment facilitates and enhances all aspects of Show'
        ];

				DB::table('criteria')->insert($rows);

        // Combo only sheet criteria
				$names = [
					'Tone & Technique' => 'Demonstration of support, release of tension, tone quality, resonance and other techniques associated with trained playing',
					'Intonation' => 'Combo members are in tune both individually and collectively',
					'Balance & Blend' => 'Combo members are in tune both individually and collectively harmonious',
          'Stylistic Authenticity' => 'Combo uses distinctive sounds and techniques according to song genre choice',
					'Rhythm & Precision' => 'Combo demonstrates rhythmic integrity, synchronized onsets, and uniform releases',
					'Musicality & Interpretation' => 'Elements of phrasing, dynamics, sensitivity, and nuance are present',
					'Facilitates Vocals and Choreography' => 'Combo accommodates, enhances, and compliments the vocal sound and choreography'
				];

				$rows = [];

				foreach($names as $name => $desc)
				{
          $rows[] = [
            'caption_id' => 3,
            'name' => $name,
            'description' => $desc
          ];
				}

				DB::table('criteria')->insert($rows);



        // Additional show - 36, 37, 38
        $names = [
          'Stylistic Authenticity & Choreographic Content' => 'Ensemble uses distinctive movements according to song genre choice',
          'Precision & Execution' => 'Choreography and staging are presented with energy and commitment, and are rhythmically synchronized',
          'Entertainment Value' => 'Choreography, sets, props, costuming, accompaniment, and special effects combine to enhance performance'
				];

        $rows = [];

				foreach($names as $name => $desc)
				{
					$rows[] = [
            'caption_id' => 2,
            'name' => $name,
            'description' => $desc
          ];
				}

				DB::table('criteria')->insert($rows);

        // Additional music - 39, 40
        $names = [
          'Tone & Technique' => 'Demonstration of support, release of tension, tone quality, resonance and other techniques associated with trained singing',
          'Balance & Blend' => 'Relative volume of voice parts and individuals is well proportioned, unobtrusive, and harmonious'
				];

        $rows = [];

				foreach($names as $name => $desc)
				{
					$rows[] = [
            'caption_id' => 1,
            'name' => $name,
            'description' => $desc
          ];
				}

				DB::table('criteria')->insert($rows);



    }
}
