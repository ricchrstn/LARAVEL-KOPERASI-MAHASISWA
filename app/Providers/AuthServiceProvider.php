<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Simpanan;
use App\Models\Pinjaman;
use App\Policies\SimpananPolicy;
use App\Policies\PinjamanPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Simpanan::class => SimpananPolicy::class,
        Pinjaman::class => PinjamanPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('isAdmin', function($user) {
            return $user->isAdmin();
        });
    }
}