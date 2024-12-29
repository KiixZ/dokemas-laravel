<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Destination;
use App\Policies\DestinationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Destination::class => DestinationPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}

