<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\RoundScoringActivated' => [
          'App\Listeners\SyncRoundChoirsFromSources'
        ],
        'App\Events\RoundScoringCompleted' => [
          'App\Listeners\SyncRoundChoirsToTarget',
          'App\Listeners\ProduceFinalStandings'
        ],
        'App\Events\DivisionChoirCreated' => [
          'App\Listeners\AddChoirToRound'
        ],
        'App\Events\DivisionChoirRemoved' => [
          'App\Listeners\RemoveChoirFromRound'
        ],
        'App\Events\RoundSaved' => [
          'App\Listeners\SyncRoundChoirsFromDivision'
        ],
        'App\Events\DivisionScoringFinalized' => [
          'App\Listeners\EmailDivisionResultsLink',
          'App\Listeners\SendSMSDivisionResultsLink'
        ],
        'App\Events\CommentSaved' => [
          'App\Listeners\CreateCommentsUrlIfNonexistent'
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
