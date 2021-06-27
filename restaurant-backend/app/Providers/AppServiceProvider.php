<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {try {
        if(!Role::where(['nom_des_roles' =>'costumer'])->first())
        {$role = new Role();
            $role->Nom_des_roles='costumer';}
        if(!Role::where(['nom_des_roles' =>'admin'])->first())
        {$role_a = new Role();
            $role_a->Nom_des_roles='admin';
        }
        $role->save();$role_a->save();    }
    catch (Throwable $e) {
    }
        date_default_timezone_set('Europe/Paris');
        Schema::defaultStringLength(191);
    }
}
