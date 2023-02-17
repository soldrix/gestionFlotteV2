<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {

        if($request->wantsJson()){
            if (!auth('sanctum')->check()) {
                return $next($request);
            }
            $userToken = Auth('sanctum')->user()->tokens->first();

        }else{
            if (!auth()->check()) {
                return $next($request);
            }
            $userToken = Auth::user()->tokens->first();
        }
        // If user is not logged in...



        $token = PersonalAccessToken::find($userToken->id);
        $now = Carbon::now();

        $last_seen = Carbon::parse($token->updated_at);

        $absence = $now->diffInMinutes($last_seen);

        // If user has been inactivity longer than the allowed inactivity period
        error_log(json_encode([
            "debut" => date($userToken->updated_at),
            "fin" => date('Y-m-d H:i:s'),
            "other" => config('session.lifetime')
            ]),JSON_PRETTY_PRINT);
        if ($absence >= config('session.lifetime')) {
            $request->user()->tokens()->delete();
            $request->session()->invalidate();
            Auth::guard()->logout();
            return ($request->wantsJson())? $next($request) : redirect('login');
        }
        if(!$request->wantsJson()){
            $token->updated_at = $now;
            $token->save();
        }

        return $next($request);
    }
}
