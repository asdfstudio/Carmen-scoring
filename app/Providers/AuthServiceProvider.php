<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
				'App\Organization' => 'App\Policies\OrganizationPolicy',
				'App\Competition' => 'App\Policies\CompetitionPolicy',
				'App\Judge' => 'App\Policies\JudgePolicy',
				'App\School' => 'App\Policies\SchoolPolicy',
				'App\Choir' => 'App\Policies\ChoirPolicy',
				'App\User' => 'App\Policies\UserPolicy',
				'App\Person' => 'App\Policies\PersonPolicy',
        'App\Penalty' => 'App\Policies\PenaltyPolicy',
        'App\Award' => 'App\Policies\AwardPolicy',
        'App\Round' => 'App\Policies\RoundPolicy',
        'App\Division' => 'App\Policies\DivisionPolicy',
        'App\Standing' => 'App\Policies\StandingPolicy',
        'App\SoloDivision' => 'App\Policies\SoloDivisionPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        //
    }
}
