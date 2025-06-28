<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
/*        Gate::define("index_user", function(User $user){
            return $user->id===2;
        });*/
/*        Gate::define("listar_user", function(User $user){
            return $user->id===2;
        });*/
/*
        Gate::define("store_user", function(User $user){
            return $user->id===2;
        });*/


/*        Gate::define("show_user", function(User $user){
            return $user->id===2;
        });*/

        //user=5
        //permiso=index_user

        Gate::before(function($user, $permiso){
            //permiso: index_user, store_user, update_user, delete_user
            return $user->permisos()->contains($permiso);        
        });

    }
}
