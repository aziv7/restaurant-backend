<?php


namespace App\Http\Middleware;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsCostumer
{  /**
 * Handle an incoming request.
 *
 * @param \Illuminate\Http\Request $request
 * @param \Closure $next
 * @return mixed
 */
    public function handle(Request $request, Closure $next)
    {         if (Auth::user()) {
        // print_r(Auth::id());
        $roles=User::find(Auth::id())->roles;
        foreach ($roles as $role)
        {//print_r($role);
            if(strcmp($role->Nom_des_roles,'costumer')==0)
                return $next($request);}
    }

        return response('You are not a costumer', 403);
    }

}
