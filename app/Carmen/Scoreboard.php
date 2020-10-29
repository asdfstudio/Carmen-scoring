<?php

namespace App\Carmen;

use DB;
use App\RawScore;
use App\Division;
use App\Round;
use App\Penalty;
use App\ChoirRoundPenalty;
use App\Carmen\WeightedScores;
use App\Carmen\RankedScores;
use App\Carmen\CondorcetScores;
use App\Carmen\ConsensusOrdinalRankScores;

class Scoreboard {

    const SKIP_EPOCH = '2020-02-06';

    public $penalties;
    public $rawScores;
    public $extendedRawScores;
    public $weightedScores;
    public $rankedScoresForCurrentMethod;

    protected $division_id;
    protected $round_id;
    protected $division;
    protected $round;
    protected $rounds;
    protected $judge_id;


    public function __construct($parameters = [])
    {
        foreach($parameters as $key => $value) {
            $this->$key = $value;
        }

        $this->getRawScores();
        $this->getWeightedScores();
        $this->getPenalties();
        $this->getRankedScoresForCurrentMethod();
    }

    protected function getRawScores()
    {
        $query = DB::table('raw_scores')
            ->join('criteria', 'raw_scores.criterion_id', '=', 'criteria.id')
            ->select([
                'raw_scores.id',
                'raw_scores.division_id',
                'raw_scores.round_id',
                'raw_scores.choir_id',
                'raw_scores.judge_id',
                'raw_scores.criterion_id',
                'raw_scores.score',
                'criteria.caption_id as criterion_caption_id'
            ])
            ->whereNull('raw_scores.deleted_at');

        if($this->division_id) {
            $query->where('division_id', $this->division_id);
        }

        if($this->round_id) {
            if(is_array($this->round_id)) {
                $query->whereIn('round_id', $this->round_id);
            } else {
                $query->where('round_id', $this->round_id);
            }
        }

        // Added 2018-01-04 to speed up scoreboard/reduce memory usage
        if($this->judge_id) {
            $query->where('judge_id', $this->judge_id);
        }

        return $this->rawScores = $query->get();
        // return $this->rawScores = collect($query->get());
    }


    protected function getWeightedScores()
    {
        $weightedScoresClass = new WeightedScores($this->rawScores, $this->getRound()->captionWeighting->id);

        $this->weightedScores = $weightedScoresClass->all();
        $this->extendedRawScores = $this->weightedScores;
        return $this->weightedScores;
    }

    protected function getPenalties()
    {
        $query = ChoirRoundPenalty::with('penalty');

        if($this->round_id)
        {
            if(is_array($this->round_id))
                $query->whereIn('round_id', $this->round_id);
            else
                $query->where('round_id', $this->round_id);
        }

        $penalties_raw = $query->get();

        $penalties = collect();

        $penalties_raw->each(function($item, $key) use ($penalties){

            if ($item->penalty) {
                $penalties->put($key, [
                    'choir_id' => $item->choir_id,
                    'amount' => $item->penalty->amount,
                    'apply_per_judge' => $item->penalty->apply_per_judge
                ]);
            }

        });

        return $this->penalties = $penalties;
    }

    protected function getRound()
    {
        if ($this->round_id) {
            return $this->round = Round::find($this->round_id);
        } elseif ($this->division_id) {
            return $this->round = Division::find($this->division_id)->round;
        }
    }

    protected function getDivision()
    {
        if($this->division_id) {
            return $this->division = Division::find($this->division_id);
        }
    }

    protected function getRankedScores()
    {
        return new RankedScores($this->extendedRawScores, $this->penalties);
    }

    protected function getBordaCountScores()
    {
        return new BordaCountScores($this->extendedRawScores, $this->penalties);
    }

    protected function getCondorcetScoresSchulze()
    {
        return new CondorcetScoresSchulze($this->extendedRawScores, $this->penalties);
    }

    protected function getCondorcetScoresRankedPairs()
    {
        return CondorcetScoresRankedPairs($this->extendedRawScores, $this->penalties);
    }

    protected function getConsensusOrdinalRankScores()
    {
        return ConsensusOrdinalRankScores($this->extendedRawScores, $this->penalties);
    }

    public function getRankedScoresForCurrentMethod()
    {
        // Calculate whether to skip tied ranks based on when change was made.
        $competition = $this->round->competition;
        $competition_skip_epoch = empty($competition->begin_date) || $competition->begin_date >= self::SKIP_EPOCH;

        switch($this->round->scoringMethod->name) {
        case 'Raw Scores':
        case 'Ranked Scores':
            $this->rankedScoresForCurrentMethod = new RankedScores($this->extendedRawScores, $this->penalties, $competition_skip_epoch);
            break;
        case 'Condorcet - Ranked Pairs Winning':
            $this->rankedScoresForCurrentMethod = new CondorcetScoresRankedPairs($this->extendedRawScores, $this->penalties, $competition_skip_epoch);
            break;
        case 'Condorcet - Schultze Winning':
            $this->rankedScoresForCurrentMethod = new CondorcetScoresSchulze($this->extendedRawScores, $this->penalties, $competition_skip_epoch);
            break;
        case 'Consensus Ordinal Rank':
            $this->rankedScoresForCurrentMethod = new ConsensusOrdinalRankScores($this->extendedRawScores, $this->penalties, $competition_skip_epoch);
            break;
        case 'Borda Count':
            $this->rankedScoresForCurrentMethod = new BordaCountScores($this->extendedRawScores, $this->penalties, $competition_skip_epoch);
            break;
        default:
            $this->rankedScoresForCurrentMethod = null;
        }
    }
}
