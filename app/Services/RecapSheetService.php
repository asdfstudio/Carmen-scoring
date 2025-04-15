<?php

namespace App\Services;

use App\Carmen\Scoreboard;
use App\Carmen\ScoringMethod;
use App\Competition;
use App\Division;
use DB;

class RecapSheetService
{
    protected static $listDivision = []; // Cache division data

    public static function generateRecap(Competition $competition)
    {
        $recapData = [];
        $rankedScores = self::calculateRankings($competition);

        $roundOrder = [
            "Traditional Choir",
            "Children’s Choir",
            "Gospel Choir",
            "Contemporary Vocal",
            "Show Choir",
            "Jazz Choir",
            "Bell Choir",
            "Concert Band",
            "Jazz Band",
            "Stage Band",
            "Orchestra",
            "Chamber Orchestra",
            "String Orchestra",
            "Full Orchestra",
            "Guitar Ensemble",
            "Percussion Ensemble",
            "Parade Band",
            "Marching Band",
            "Field Show Combined",
            "Field Show Drumline",
            "Field Show Auxiliary",
            "Field Show Drum Major",
            "Parade Combined",
            "Parade Drumline",
            "Parade Auxiliary",
            "Parade Drum Major"
        ];

        $divisionOrder = [
            "Concert Choir",
            "Chamber",
            "Madrigal",
            "Upper Voice",
            "Lower Voice",
            "Children",
            "Gospel",
            "Contemporary Vocal",
            "Show",
            "Vocal Jazz",
            "Bell",
            "Concert Band",
            "Jazz Band",
            "Stage Band",
            "Chamber Orchestra",
            "String Orchestra",
            "Full Orchestra",
            "Guitar",
            "Percussion",
            "Parade Band",
            "Marching Band",
            "Field Show Combined",
            "Field Show Drumline",
            "Field Show Auxiliary",
            "Field Show Drum Major",
            "Parade Combined",
            "Parade Drumline",
            "Parade Auxiliary",
            "Parade Drum Major"
        ];

        $subcategoryOrder = ["Concert", "Chamber", "Madrigal", "Upper", "Lower"];
        $divisionClassPartOrder = ["C", "J", "1A", "2A", "3A", "O"];

        $roundOrderMap = array_flip($roundOrder);
        $divisionOrderMap = array_flip($divisionOrder);
        $subcategoryMatchOrder = array_flip($subcategoryOrder);
        $divisionClassPartOrderMap = array_flip($divisionClassPartOrder);

        $normalizeName = function ($name, $type = 'round') {
            $name = preg_replace('/\b(Choir)s?\b/', 'Choir', $name);
            if ($type === 'subcategory') {
                $name = preg_replace('/\b(V[oice]+es?|V[oice]+)\b/', 'Voice', trim($name));
            }
            return $name;
        };

        foreach ($competition->divisions as $division) {
            $judges = $division->round ? $division->round->judges : collect();
            $round = $normalizeName($division->round->name);
            $divisionClass = $division->name;

            $divisionClassPart = collect(explode(' ', $divisionClass))->last();
            $subcategoryPart = str_replace($divisionClassPart, '', $divisionClass);
            $normalizedSubcategory = $normalizeName(trim($subcategoryPart), 'subcategory');

            $rawScores = DB::table('raw_scores')
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
                ->whereNull('raw_scores.deleted_at')
                ->where('raw_scores.division_id', $division->id)
                ->where('raw_scores.round_id', $division->round_id);

            if ($judges->isNotEmpty()) {
                $rawScores->whereIn('judge_id', $judges->pluck('id'));
            }

            $rawScores = $rawScores->get();

            $choirs = $division->choirs ? $division->choirs->map(function ($choir) use ($rawScores, $rankedScores, $judges, $division) {
                $scores = $rawScores->where('choir_id', $choir->id);
                $judgeScores = $scores->groupBy('judge_id')->sortKeysDesc();
                $totalScore = 0;
                $judgesCount = 0;

                $last3JudgeIds = $judgeScores->keys()->take(3);
                foreach ($last3JudgeIds as $judgeId) {
                    $judgeScoreSet = $judgeScores[$judgeId];
                    if ($judgeScoreSet->isNotEmpty()) {
                        $totalScore += $judgeScoreSet->sum('score');
                        $judgesCount++;
                    }
                }

                $averageScore = $judgesCount > 0 ? round($totalScore / $judgesCount, 1) : 'No scores available';

                $penalty = $choir->penalties->where('apply_per_judge', 0)->sum('amount');
                $judgePenalty = $judges->count() * $choir->penalties->where('apply_per_judge', 1)->sum('amount');
                $totalPenalty = $penalty + $judgePenalty;

                $adjustedTotalScore = $totalScore - $totalPenalty;
                $adjustedAverageScore = $judgesCount > 0 ? round($adjustedTotalScore / $judgesCount, 1) : 'No scores available';

                // $rank = isset($rankedScores[$choir->id]) ? $rankedScores[$choir->id] : 'No Rank';
                $rank = $choir->pivot->receives_rankings ? ($rankedScores[$choir->id] ?? 'No Rank') : 'No Rank';

                if ($division->choirs->count() === 1) {
                    $rank = '-';
                }

                // $rating = self::getRating($adjustedAverageScore);
                // $rating = $choir->pivot->receives_ratings ? self::getRating($adjustedAverageScore) : 'No Rating';
                $rating = $choir->pivot->receives_ratings ? self::getRatingOfChoir($adjustedAverageScore, $division->id) : 'No Rating';

                return [
                    'name' => $choir->name,
                    'school' => $choir->school->name,
                    'director' => $choir->directors->map(function ($director) {
                        return $director->first_name . ' ' . $director->last_name;
                    })->implode(', '),
                    'average_score' => $adjustedAverageScore,
                    'caption_averages' => [],
                    'ranking' => $rank,
                    'rating' => $rating,
                    'category' => $division->name ?? 'Unknown',
                    'choral_sweepstakes_checked' => $choir->pivot->choral_sweepstakes,
                    'instrumental_sweepstakes_checked' => $choir->pivot->instrumental_sweepstakes,
                    'festival_sweepstakes_checked' => $choir->pivot->festival_sweepstakes,
                    'rank_checked' => $choir->pivot->receives_rankings,
                    'rating_checked' => $choir->pivot->receives_ratings,
                    'adjusted_average_score' => $adjustedAverageScore
                ];
            })->toArray() : [];

            usort($choirs, function ($a, $b) {
                return floatval($b['adjusted_average_score']) <=> floatval($a['adjusted_average_score']);
            });

            $recapData[] = [
                'division' => $divisionClass,
                'division_class_part' => $divisionClassPart,
                'subcategory' => $normalizedSubcategory,
                'choirs' => $choirs,
                'judges' => $judges,
                'round' => $round
            ];
        }

        usort($recapData, function ($a, $b) use ($roundOrder, $divisionOrderMap, $subcategoryMatchOrder, $divisionClassPartOrderMap, $subcategoryOrder) {
            $roundA = $a['round'];
            $roundB = $b['round'];

            $getPosition = function ($roundName) use ($roundOrder) {
                foreach ($roundOrder as $index => $orderName) {
                    if (stripos($roundName, $orderName) !== false) {
                        return $index;
                    }
                }
                return PHP_INT_MAX;
            };

            $positionA = $getPosition($roundA);
            $positionB = $getPosition($roundB);

            if ($positionA !== $positionB) {
                return $positionA <=> $positionB;
            }

            $divisionComparison = ($divisionOrderMap[$a['division_class_part']] ?? PHP_INT_MAX) <=> ($divisionOrderMap[$b['division_class_part']] ?? PHP_INT_MAX);
            if ($divisionComparison !== 0) {
                return $divisionComparison;
            }

            if ($a['round'] === "Traditional Choir" && $b['round'] === "Traditional Choir") {
                $aSubcategory = explode(' ', $a['division'])[0];
                $bSubcategory = explode(' ', $b['division'])[0];

                $aSubcategoryIndex = array_search($aSubcategory, $subcategoryOrder);
                $bSubcategoryIndex = array_search($bSubcategory, $subcategoryOrder);

                if ($aSubcategoryIndex !== false && $bSubcategoryIndex !== false) {
                    if ($aSubcategoryIndex !== $bSubcategoryIndex) {
                        return $aSubcategoryIndex <=> $bSubcategoryIndex;
                    }
                } elseif ($aSubcategoryIndex !== false) {
                    return -1;
                } elseif ($bSubcategoryIndex !== false) {
                    return 1;
                }
            }

            $partComparison = ($divisionClassPartOrderMap[$a['division_class_part']] ?? PHP_INT_MAX) <=> ($divisionClassPartOrderMap[$b['division_class_part']] ?? PHP_INT_MAX);
            return $partComparison;
        });

        return $recapData;
    }

    public static function getRatingOfChoir($score, $division_id)
    {
        try {
            $division = self::$listDivision[$division_id] ?? Division::find($division_id);
            self::$listDivision[$division_id] = $division;

            $ratingOptions = collect($division->rating_system)->sortByDesc('min_score')->toArray();
            $ratingName = '';

            foreach ($ratingOptions as $ratingOption) {
                if ($score >= $ratingOption['min_score']) {
                    $ratingName = $ratingOption['name'];
                    break;
                }
            }

            return $ratingName;
        } catch (\Exception $ex) {
            return '';
        }
    }



    protected static function calculateRankings(Competition $competition)
    {
        $rankings = [];

        foreach ($competition->divisions as $division) {
            $choirsForRanking = $division->choirs->filter(function ($choir) {
                return !$choir->opt_out_of_ranking && ($choir->pivot->receives_rankings ?? true);
            });

            if ($choirsForRanking->isNotEmpty()) {
                $choirScores = $choirsForRanking->mapWithKeys(function ($choir) {
                    $totalScore = $choir->rawScores ? $choir->rawScores->sum('score') : 0;
                    return [$choir->id => $totalScore];
                });

                $sortedChoirs = $choirScores->sortDesc();

                $currentRank = 1;
                foreach ($sortedChoirs as $choirId => $totalScore) {
                    // if ($currentRank <= 3) {
                        $rankings[$choirId] = $currentRank++;
                    // } else {
                    //     $rankings[$choirId] = 'No Rank';
                    // }
                }
            }
        }

        return $rankings;
    }



    protected static function getRating($averageScore)
    {
        if ($averageScore >= 85) {
            return 'Gold';
        } elseif ($averageScore >= 75) {
            return 'Silver';
        } elseif ($averageScore >= 60) {
            return 'Bronze';
        } elseif ($averageScore >= 50) {
            return 'Merit';
        } else {
            return 'Festival';
        }
    }

    public static function getAdjudicatorAwardWinners(Competition $competition)
    {
        $choirs = collect(self::generateRecap($competition))->flatMap(function ($division) {
            $roundName = $division['division'] ?? 'Unknown Round';
    
            return collect($division['choirs'] ?? [])->map(function ($choir) use ($roundName) {
                $choir['round'] = $roundName;
                return $choir;
            });
        });
    
        $eligibleWinners = $choirs->filter(function ($choir) {
            return $choir['average_score'] >= 94.5;
        });
        $bandOrchestraWinners = $choirs->filter(function ($choir) {
            $roundName = $choir['round'];
            return (stripos($roundName, 'band') !== false || stripos($roundName, 'orchestra') !== false)
                && $choir['average_score'] >= 91.5;
        });
    
        return $eligibleWinners
            ->merge($bandOrchestraWinners)
            ->unique(function ($item) {
                return $item['name'] . '|' . $item['school'];
            })
            ->sortByDesc('average_score')
            ->values();
    }




    public static function getFOGInvitationWinners(Competition $competition)
    {
        $validChoralTypes = [
            'Choir',
            'Vocal',
            'Show Choir',
            'Vocal Jazz'
        ];
    
        $validInstrumentalTypes = [
            'Bell',
            'Band',
            'Orchestra',
            'Guitar',
            'Percussion',
            'Field Show',
            'Combined',
            'Drumline',
            'Auxiliary',
            'Drum',
            'Parade',
            'Drum Major'
        ];
    
        $excludedTypes = [
            'Vocal Jazz',
            'Show Choir',
            'Jazz Choir',
            'Contemporary Vocal Ensemble',
            'Bell Choir',
            'Jazz Band',
            'Stage Band',
            'Percussion Ensemble',
            'Guitar Ensemble',
            'Parade Band',
            'Marching Band',
            'Field Show Combined',
            'Field Show Drumline',
            'Field Show Auxiliary',
            'Field Show Drum Major',
            'Parade Combined',
            'Parade Drumline',
            'Parade Auxiliary',
            'Parade Drum Major',
        ];
    
        $choirs = collect(self::generateRecap($competition))->flatMap(function ($division) {
            return collect($division['choirs'])->map(function ($choir) use ($division) {
                $choir['round'] = $division['round'] ?? 'Unknown Round';
                return $choir;
            });
        });

        $choirs = $choirs->reject(function ($choir) use ($excludedTypes) {
            return collect($excludedTypes)->contains(function ($type) use ($choir) {
                return stripos($choir['round'], $type) !== false;
            });
        });
    
        // Filter choirs based on qualifications
        $qualifiedChoirs = $choirs->filter(function ($choir) {
            // return $choir['average_score'] >= 90 && $choir['ranking'] !== 'No Rank';
            return $choir['average_score'] >= 89.5;
        });

        $choralChoirs = $qualifiedChoirs->filter(function ($choir) use ($validChoralTypes) {
            return collect($validChoralTypes)->contains(function ($type) use ($choir) {
                return stripos($choir['round'], $type) !== false;
            });
        })->sortBy('school')->values();


        $instrumentalChoirs = $qualifiedChoirs->filter(function ($choir) use ($validInstrumentalTypes) {
            return collect($validInstrumentalTypes)->contains(function ($type) use ($choir) {
                return stripos($choir['round'], $type) !== false;
            });
        })->sortBy('school')->values();

    
        return [
            'choral' => $choralChoirs,
            'instrumental' => $instrumentalChoirs,
        ];
    } 


    public static function getOutstandingGroupWinners(Competition $competition)
    {
        $choirs = collect(self::generateRecap($competition))->flatMap(function ($division) {
            return collect($division['choirs'])->map(function ($choir) use ($division) {
                $choir['round'] = $division['round'] ?? 'Unknown Round';
                return $choir;
            });
        });
    
        $getTiedWinners = function ($filteredChoirs) {
            $highestScore = $filteredChoirs->max('average_score');
            $winners = $filteredChoirs->filter(function ($choir) use ($highestScore) {
                return $choir['average_score'] === $highestScore;
            });
            return $winners->map(function ($choir) use ($winners) {
                $choir['tied'] = $winners->count() > 1;
                return $choir;
            });
        };
    
        $outstandingChoral = $choirs->filter(function ($choir) {
            return stripos(strtolower($choir['round']), 'choir') !== false;
                // && $choir['ranking'] !== 'No Rank';
        });
        $outstandingChoralWinners = $getTiedWinners($outstandingChoral);
    
        $outstandingBand = $choirs->filter(function ($choir) {
            $roundName = strtolower($choir['category']);
            $excludedTypes = [
                'percussion ensemble',
                'guitar ensemble',
                'parade band',
                'marching band',
                'field show combined',
                'field show drumline',
                'field show auxiliary',
                'field show drum major',
                'parade combined',
                'parade drumline',
                'parade auxiliary',
                'parade drum major',
            ];
            foreach ($excludedTypes as $excluded) {
                if (stripos($roundName, $excluded) !== false) {
                    return false;
                }
            }
            return stripos($roundName, 'band') !== false;
        });
        $outstandingBandWinners = $getTiedWinners($outstandingBand);
    
        $outstandingOrchestra = $choirs->filter(function ($choir) {
            return stripos(strtolower($choir['round']), 'orchestra') !== false;
                // && $choir['ranking'] !== 'No Rank';
        });
        $outstandingOrchestraWinners = $getTiedWinners($outstandingOrchestra);
    
        return [
            'choral' => $outstandingChoralWinners,
            'band' => $outstandingBandWinners,
            'orchestra' => $outstandingOrchestraWinners,
        ];
    }
    
    


    public static function getSweepstakesWinners(Competition $competition)
    {
        $choirs = collect(self::generateRecap($competition))->flatMap(function ($division) {
            $roundName = $division['division'] ?? 'Unknown division';
            return collect($division['choirs'] ?? [])->map(function ($choir) use ($roundName) {
                $choir['category'] = $roundName;
                return $choir;
            });
        });
    
        $excludedTypes = [
            'Guitar Ensemble',
            'Percussion Ensemble',
            'Parade Band/Marching Band',
            'Field Show Combined',
            'Field Show Drumline',
            'Field Show Auxiliary',
            'Field Show Drum Major',
            'Parade Combined',
            'Parade Drumline',
            'Parade Auxiliary',
            'Parade Drum Major',
        ];
    
        $choirs = $choirs->reject(function ($choir) use ($excludedTypes) {
            return collect($excludedTypes)->contains(function ($type) use ($choir) {
                return stripos($choir['category'], $type) !== false;
            });
        });
    
        $schools = $choirs->groupBy('school');
        $sweepstakesWinners = [
            'choral' => null,
            'instrumental' => null,
            'festival' => null,
        ];
    
        $validChoralTypes = [
            'Choir',
            'Vocal',
            'Show Choir',
            'Vocal Jazz',
            'Concert',
            'Chamber',
            'Upper',
            'Lower'
        ];
        $validInstrumentalTypes = [
            'Bell',
            'Band',
            'Orchestra',
            'Guitar',
            'Percussion',
            'Field Show',
            'Combined',
            'Drumline',
            'Auxiliary',
            'Drum',
            'Parade',
            'Drum Major'
        ];
    
        foreach ($schools as $schoolName => $schoolChoirs) {
            // **Choral Sweepstakes**
            $choralChoirs = $schoolChoirs->filter(function ($choir) {
                return isset($choir['choral_sweepstakes_checked']) &&
                    $choir['choral_sweepstakes_checked'] == 1;
                // $choir['ranking'] !== "No Rank";
            });

            $validChoralChoirs = $choralChoirs->filter(function ($choir) use ($validChoralTypes) {
                return collect($validChoralTypes)->contains(function ($type) use ($choir) {
                    return stripos($choir['category'], $type) !== false;
                });
            });

            $traditionalChoral = $choralChoirs->filter(function ($choir) {
                return preg_match('/Concert|Chamber|Upper|Lower/i', $choir['category']);
            })->sortByDesc('average_score')->first();

            $secondChoral = $validChoralChoirs->reject(function ($choir) use ($traditionalChoral) {
                return $traditionalChoral && $choir['name'] === $traditionalChoral['name'];
            })->sortByDesc('average_score')->first();

            // Ensure traditionalChoral and secondChoral are not null before using them
            if ($traditionalChoral && $secondChoral) {
                // Safely access average_score with a fallback value of 0 if not set
                $traditionalChoralScore = isset($traditionalChoral['average_score']) ? (float) $traditionalChoral['average_score'] : 0;
                $secondChoralScore = isset($secondChoral['average_score']) ? (float) $secondChoral['average_score'] : 0;

                $totalChoralScore = $traditionalChoralScore + $secondChoralScore;
                $isInstrumentalTied = $traditionalChoralScore === $secondChoralScore;

                if (!isset($sweepstakesWinners['choral']) || $totalChoralScore > (float) $sweepstakesWinners['choral']['total_score']) {
                    $sweepstakesWinners['choral'] = [
                        'school_name' => $schoolName,
                        'choirs' => [$traditionalChoral['name'], $secondChoral['name']],
                        'average_score' => [$traditionalChoralScore, $secondChoralScore],
                        'total_score' => $totalChoralScore,
                        'is_tied' => $isInstrumentalTied, // Flag for tied scores
                    ];
                }
            }
    
            // **Instrumental Sweepstakes**
            $instrumentalChoirs = $schoolChoirs->filter(function ($choir) {
                return isset($choir['instrumental_sweepstakes_checked']) &&
                    $choir['instrumental_sweepstakes_checked'] == 1;
                    // $choir['ranking'] !== "No Rank";
            });
    
            $validInstrumentalChoirs = $instrumentalChoirs->filter(function ($choir) use ($validInstrumentalTypes) {
                return collect($validInstrumentalTypes)->contains(function ($type) use ($choir) {
                    return stripos($choir['category'], $type) !== false;
                });
            });
    
            $concertOrOrchestra = $instrumentalChoirs->filter(function ($choir) {
                return preg_match('/Orchestra|Concert Band/i', $choir['category']);
            })->sortByDesc('average_score')->first();
    
            $secondInstrumental = $validInstrumentalChoirs->reject(function ($choir) use ($concertOrOrchestra) {
                return $concertOrOrchestra && $choir['name'] === $concertOrOrchestra['name'];
            })->sortByDesc('average_score')->first();

            if ($concertOrOrchestra && $secondInstrumental) {
                $totalInstrumentalScore = (float) $concertOrOrchestra['average_score'] + (float) $secondInstrumental['average_score'];
                $isInstrumentalTied = (float) $concertOrOrchestra['average_score'] === (float) $secondInstrumental['average_score'];
            
                if (!isset($sweepstakesWinners['instrumental']) || $totalInstrumentalScore > (float) $sweepstakesWinners['instrumental']['total_score']) {
                    $sweepstakesWinners['instrumental'] = [
                        'school_name' => $schoolName,
                        'choirs' => [$concertOrOrchestra['name'], $secondInstrumental['name']],
                        'average_score' => [(float) $concertOrOrchestra['average_score'], (float) $secondInstrumental['average_score']],
                        'total_score' => $totalInstrumentalScore,
                        'is_tied' => $isInstrumentalTied, // Flag for tied scores
                    ];
                }
            }
            
                
            // **Festival Sweepstakes Calculation**
            if ($schoolChoirs->isNotEmpty()) {
                // Filter ensembles that qualify for Festival Sweepstakes
                $eligibleChoirs = $schoolChoirs->filter(function ($choir) {
                    return isset($choir['festival_sweepstakes_checked']) &&
                        $choir['festival_sweepstakes_checked'] == 1 &&
                        $choir['ranking'] !== "No Rank";
                });

                // Get the highest scoring Choir group (Traditional, Jazz, or Show)
                $highestChoral = $eligibleChoirs->filter(function ($choir) {
                    return preg_match('/Concert|Chamber|Upper|Lower|Vocal Jazz|Vocal|Jazz Choir|Show/i', $choir['category']);
                })->sortByDesc('average_score')->first();

                // === Step 2: Get Highest Scoring INSTRUMENTAL group ===
                $highestInstrumental = $eligibleChoirs->filter(function ($choir) use ($highestChoral) {
                    return !empty($choir['category']) &&
                        preg_match('/Orchestra|Band|Jazz Band/i', $choir['category']) &&
                        !preg_match('/Vocal|Choir|Show/i', $choir['category']) && // Exclude choir-like groups
                        $choir['name'] !== ($highestChoral['name'] ?? null);
                })->sortByDesc('average_score')->first();

                // Get the next highest scoring group (excluding Percussion, Guitar, Drum Line, Parade, Auxiliary)
                $thirdEnsemble = $eligibleChoirs->filter(function ($choir) use ($highestChoral, $highestInstrumental) {
                    $excludedNames = [
                        $highestChoral['name'] ?? '',
                        $highestInstrumental['name'] ?? ''
                    ];
                    return !in_array($choir['name'], $excludedNames) &&
                        !preg_match('/Percussion|Guitar|Drumline|Parade|Auxiliary/i', $choir['category']);
                })->sortByDesc('average_score')->first();

                // If all three groups exist, calculate Festival Sweepstakes score
                $totalScoreMap = $totalScoreMap ?? []; // Initialize only once globally

                if ($highestChoral && $highestInstrumental && $thirdEnsemble) {
                    $scores = [
                        $highestChoral['average_score'] ?? 0,
                        $highestInstrumental['average_score'] ?? 0,
                        $thirdEnsemble['average_score'] ?? 0
                    ];
                    
                    $totalFestivalScore = array_sum($scores);
                
                    // Check if this total score already exists in the map (indicating a tie)
                    $isTiedWithAnotherSchool = in_array($totalFestivalScore, $totalScoreMap);
                
                    // Always track the score for later comparisons
                    $totalScoreMap[] = $totalFestivalScore;
                
                    if (!isset($sweepstakesWinners['festival'])) {
                        // First school to be recorded
                        $sweepstakesWinners['festival'] = [
                            'school_name' => $schoolName,
                            'choirs' => [$highestChoral['name'], $highestInstrumental['name'], $thirdEnsemble['name']],
                            'average_score' => $scores,
                            'total_score' => $totalFestivalScore,
                            'is_tied' => false
                        ];
                    } elseif ($totalFestivalScore > (float) $sweepstakesWinners['festival']['total_score']) {
                        // New high score — replace previous winner
                        $sweepstakesWinners['festival'] = [
                            'school_name' => $schoolName,
                            'choirs' => [$highestChoral['name'], $highestInstrumental['name'], $thirdEnsemble['name']],
                            'average_score' => $scores,
                            'total_score' => $totalFestivalScore,
                            'is_tied' => false
                        ];
                    } elseif ($totalFestivalScore === (float) $sweepstakesWinners['festival']['total_score']) {
                        // Exact tie with previous winner — mark both as tied
                        $sweepstakesWinners['festival']['is_tied'] = true;
                        $isTiedWithAnotherSchool = true;
                    }
                }                
            }

        }
    
        return $sweepstakesWinners;
    }
    



}
