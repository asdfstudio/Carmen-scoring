<?php

use Illuminate\Database\Seeder;

class CriterionSheetSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir');
    $criteria = collect([
      ['caption' => 'Music', 'name' =>'Intonation'],
      ['caption' => 'Music', 'name' =>'Diction'],
      ['caption' => 'Music', 'name' =>'Rhythm & Precision'],
      ['caption' => 'Music', 'name' =>'Musicality & Interpretation'],
      ['caption' => 'Music', 'name' =>'Consistency'],
      ['caption' => 'Music', 'name' =>'Balance & Blend'],
      ['caption' => 'Music', 'name' =>'Tone & Technique'],
      ['caption' => 'Show ', 'name' =>'Stylistic Authenticity & Choreographic Content'],
      ['caption' => 'Show ', 'name' =>'Communication & Expression'],
      ['caption' => 'Show ', 'name' =>'Consistency'],
      ['caption' => 'Show ', 'name' =>'Staging & Transitions'],
      ['caption' => 'Show ', 'name' =>'Appearance & Poise'],
      ['caption' => 'Show ', 'name' =>'Precision & Execution'],
      ['caption' => 'Show ', 'name' =>'Entertainment Value']
    ]);
    $this->linkCriterionSheet($criteria, $sheet);

    $criteria->concat([
      ['caption' => 'Music ', 'name' =>'Accompaniment'],
      ['caption' => 'Show ', 'name' =>'Accompaniment']
    ]);
    $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir with Accompaniment');
    $this->linkCriterionSheet($criteria, $sheet);

    $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir Advanced');
    $criteria = collect([
      ['caption' => 'Music', 'name' =>'Intonation'],
      ['caption' => 'Music', 'name' =>'Balance & Clarity'],
      ['caption' => 'Music', 'name' =>'Blend'],
      ['caption' => 'Music', 'name' =>'Dynamics'],
      ['caption' => 'Music', 'name' =>'Diction'],
      ['caption' => 'Music', 'name' =>'Rhythm & Precision'],
      ['caption' => 'Music', 'name' =>'Musicality & Interpretation'],
      ['caption' => 'Music', 'name' =>'Stylistic Authenticity'],
      ['caption' => 'Music', 'name' =>'Consistency'],
      ['caption' => 'Music', 'name' =>'Difficulty'],
      ['caption' => 'Music', 'name' =>'Innovation'],
      ['caption' => 'Music', 'name' =>'Tone & Technique'],
      ['caption' => 'Show ', 'name' =>'Staging'],
      ['caption' => 'Show ', 'name' =>'Stylistic Authenticity & Choreographic Content'],
      ['caption' => 'Show ', 'name' =>'Appearance'],
      ['caption' => 'Show ', 'name' =>'Transitions & Pacing'],
      ['caption' => 'Show ', 'name' =>'Precision & Execution'],
      ['caption' => 'Show ', 'name' =>'Poise'],
      ['caption' => 'Show ', 'name' =>'Communication & Expression'],
      ['caption' => 'Show ', 'name' =>'Objectives & Atmosphere'],
      ['caption' => 'Show ', 'name' =>'Entertainment Value'],
      ['caption' => 'Show ', 'name' =>'Consistency'],
      ['caption' => 'Show ', 'name' =>'Difficulty'],
      ['caption' => 'Show ', 'name' =>'Innovation']
    ]);
    $this->linkCriterionSheet($criteria, $sheet);

    $criteria->concat([
      ['caption' => 'Music ', 'name' =>'Accompaniment'],
      ['caption' => 'Show ', 'name' =>'Accompaniment']
    ]);
    $sheet = App\Sheet::firstWhere('name', 'Carmen Showchoir - Advanced with Accompaniment');
    $this->linkCriterionSheet($criteria, $sheet);
  }

  private function linkCriterionSheet($criteria, $sheet)
  {
    foreach ($criteria as $criterion) {

      $entity = App\Criterion::where('criteria.name', $criterion['name'])
        ->leftJoin('captions', 'criteria.caption_id', '=', 'captions.id')
        ->where('captions.name', $criterion['caption'])
        ->get('criteria.*')
        ->first();

      $entity->sheets()->save($sheet);
    }
  }
}
