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
        // If user is not logged in...
        if (!Auth::check()) {
            return $next($request);
        }

        $userToken = Auth::user()->tokens->first();

        $token = PersonalAccessToken::find($userToken->id);

        $now = Carbon::now();

        $last_seen = Carbon::parse($token->updated_at);

        $absence = $now->diffInMinutes($last_seen);

        // If user has been inactivity longer than the allowed inactivity period
        if ($absence > config('session.lifetime')) {
            $request->user()->tokens()->delete();
            $request->session()->invalidate();
            Auth::guard()->logout();
            return redirect('login');
        }

        $token->updated_at = $now;
        $token->save();

        return $next($request);
    }
}
