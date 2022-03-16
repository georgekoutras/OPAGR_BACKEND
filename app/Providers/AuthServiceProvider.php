<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        Passport::tokensCan([
            'see-dashboard' => 'access home page',
            'see-users' => 'access users page',
            'see-cultivations' => 'access cultivation page',
            'see-cultivation-types' => 'access cultivation types page',
            'see-devices' => 'access device page',
            'see-notifications' => 'access notification page'
        ]);
        Passport::tokensExpireIn(now()->addMinutes(60));
        Passport::refreshTokensExpireIn(now()->addMinutes(120));
    }
}
