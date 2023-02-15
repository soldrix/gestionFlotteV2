<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifRight
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ... $roles)
    {
        //vérifie dans la liste des roles donné si l'utilisateur a le role
        foreach ($roles as $role){
            //l'utilisateur a le role donc passe
            if(Auth::user()->hasRole($role)){
                return $next($request);
            }
        }
        //l'utilisateur n'a pas le role requis donc renvois à la page 404 (pas la view 404)
        return redirect('PagenotFound');
    }
}
