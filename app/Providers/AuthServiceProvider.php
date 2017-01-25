<?php

namespace App\Providers;

use App\Association;
use App\Club;
use App\Federation;
use App\Policies\AssociationPolicy;
use App\Policies\ClubPolicy;
use App\Policies\FederationPolicy;
use App\Policies\TeamPolicy;
use App\Policies\TournamentPolicy;
use App\Policies\TreePolicy;
use App\Policies\UserPolicy;
use App\Team;
use App\Tournament;
use App\Tree;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Tournament::class => TournamentPolicy::class,
        User::class => UserPolicy::class,
        Federation::class => FederationPolicy::class,
        Association::class => AssociationPolicy::class,
        Club::class => ClubPolicy::class,
        Team::class => TeamPolicy::class,
        Tree::class => TreePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
