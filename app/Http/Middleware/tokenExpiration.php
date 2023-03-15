<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class tokenExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->wantsJson()){
            if(!Auth('sanctum')->check()){
                return $next($request);
            }
            $userToken = Auth('sanctum')->user()->tokens->first();
            if($userToken === null){
                Auth::guard()->logout();
                return $next($request);
            }
        }else{
            if (!auth()->check()) {
                return $next($request);
            }
            $userToken = Auth::user()->tokens->first();
            if($userToken === null){
                Auth::logout();
                $request->session()->invalidate();
                return $next($request);
            }
        }

        $token = PersonalAccessToken::find($userToken->id);
        $now =  Carbon::parse(Carbon::now()->addHour());

        $last_seen = Carbon::parse($token->expires_at);

        // If user has been inactivity longer than the allowed inactivity period
        if ($now->greaterThanOrEqualTo($last_seen)) {
            $request->user()->tokens()->delete();
            if(!$request->wantsJson()){
                Auth::guard()->logout();
                return redirect('login');
            }
            return $next($request);
        }
        $token->expires_at = $now->addMinutes(intval(env('SESSION_LIFETIME')));
        $token->save();

        return $next($request);
    }
}
