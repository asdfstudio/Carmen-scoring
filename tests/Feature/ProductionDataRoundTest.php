<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductionDataRoundTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setup();
        // Only run this on the regular showchoir_db environment
        if (\App::environment() != 'prod-data') {
            $this->markTestSkipped();
        }
    }



    /**
     * Checking Kate Burns scores for Loveland Showfest 2020 Finals
     */
    public function testLovelandScores()
    {

        $division_id = 1103; // Finals Division
        $judge_id = 4229; // Kate Burns

        $music_diff_id = 11; // First Criterion
        $show_diff_id = 23; // Second Criterion

        // Check scores for Beavercreek
        $choir_id = 63;
        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $music_diff_id);
        $this->assertEquals(8.0, $score, 'Burns Score for Beavercreek (Music Difficulty) wrong');

        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $show_diff_id);
        $this->assertEquals(9.0, $score, 'Burns Score for Beavercreek (Show Difficulty) wrong');
        //
        // Check scores for Enterprise
        $choir_id = 582;
        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $music_diff_id);
        $this->assertEquals(8.0, $score, 'Burns Score for Enterprise (Music Difficulty) wrong');

        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $show_diff_id);
        $this->assertEquals(8.5, $score, 'Burns Score for Enterprise (Show Difficulty) wrong');

        // Check scores for Ross
        $choir_id = 72;
        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $music_diff_id);
        $this->assertEquals(8.0, $score, 'Burns Score for Ross (Music Difficulty) wrong');

        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $show_diff_id);
        $this->assertEquals(8.0, $score, 'Burns Score for Ross (Show Difficulty) wrong');
    }

    /**
     * Checking Shane Coe scores for Petal Show Choir Invitational 2020
     */
    public function testPetalScores()
    {
        $division_id = 1170; // Finals Division
        $judge_id = 189; // Shane Coe

        $vocal_style_id = 251; // First Criterion
        $show_design_id = 180; // Second Criterion

        // Check scores for Clinton
        $choir_id = 522;
        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $vocal_style_id);
        $this->assertEquals(6.5, $score, 'Coe Score for Clinton (Vocal Style) wrong');

        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $show_design_id);
        $this->assertEquals(9.5, $score, 'Coe Score for Clinton (Show Design) wrong');

        // Check scores for Wheaton Classics
        $choir_id = 88;
        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $vocal_style_id);
        $this->assertEquals(6.0, $score, 'Coe Score for Wheaton (Vocal Style) wrong');

        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $show_design_id);
        $this->assertEquals(10.0, $score, 'Coe Score for Wheaton (Show Design) wrong');

        // Check scores for Decatur
        $choir_id = 87;
        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $vocal_style_id);
        $this->assertEquals(6.5, $score, 'Coe Score for Decatur (Vocal Style) wrong');

        $score = $this->getJudgeScore($division_id, $choir_id, $judge_id, $show_design_id);
        $this->assertEquals(9.0, $score, 'Coe Score for Decatur (Show Design) wrong');

    }

    /**
     * Make sure this comment is still linked to the choir and judge
     *
     * https://homestead.carmen/organizer/competition/38/division/232/standing
     *
     * TODO: Move this to a better test. Rounds aren't really connected here in many
     * instances. Maybe that was added later?
     */
    public function testMarysvilleComments()
    {
        $judge_id = 1307; // Jeff Clark
        $choir_id = 65; // Vocal Impact

        $expected = 'Keep singing and dancing!  Listen to your director!';
        $comment = \App\Comment::where('judge_id', $judge_id)
            ->where('choir_id', $choir_id)
            ->first()->comments;

        $this->assertStringStartsWith($expected, $comment, 'Marysville comment wrong');
    }


    private function getJudgeScore($division_id, $choir_id, $judge_id, $criterion_id)
    {
        $rawScore = \App\RawScore::where('division_id', $division_id)
            ->where('choir_id', $choir_id)
            ->where('judge_id', $judge_id)
            ->where('criterion_id', $criterion_id)
            ->get()->first();

        if ($rawScore) {
            return $rawScore->score;
        } else {
            return -1;
        }
    }

}
