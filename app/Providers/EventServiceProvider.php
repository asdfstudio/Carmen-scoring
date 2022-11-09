<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
          SendEmailVerificationNotification::class,
        ],
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\DivisionScoringCompleted' => [
          'App\Listeners\ProduceFinalStandings',
        ],
        'App\Events\StandingRefreshNeeded' => [
          'App\Listeners\ProduceFinalStandings',
        ],
        'App\Events\DivisionChoirCreated' => [
          'App\Listeners\AddChoirToRound',
          'App\Listeners\UpdatePerformanceOrder'
        ],
        'App\Events\DivisionChoirRemoved' => [
          'App\Listeners\RemoveChoirFromRound',
          'App\Listeners\RemoveChoirDivisionRawScores',
          'App\Listeners\UpdatePerformanceOrder'
        ],
        'App\Events\DivisionScoringFinalized' => [
          'App\Listeners\EmailFeedbackLink',
          'App\Listeners\SendSMSFeedbackLink',
        //   'App\Listeners\EmailDivisionResultsLink',
        //   'App\Listeners\SendSMSDivisionResultsLink'
        ],
        'App\Events\SoloDivisionScoringFinalized' => [
        //   'App\Listeners\EmailSoloDivisionResultsLink',
        //   'App\Listeners\SendSMSSoloDivisionResultsLink',
          'App\Listeners\EmailSoloDivisionFeedbackLink',
          'App\Listeners\SendSMSSoloDivisionFeedbackLink',
        ],
        'App\Events\CommentSaved' => [
          'App\Listeners\CreateCommentsUrlIfNonexistent'
        ],
        'App\Events\PerformanceOrderChanged' => [
            'App\Listeners\UpdatePerformanceOrder'
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
