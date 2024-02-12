<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Inertia;
use App\Models\UserSwitch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserSwitchMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userSwitch = UserSwitch::where('user_id', auth()->id())->first();

        Inertia::share('user_switch_data', $userSwitch);
        return $next($request);
    }
}

// auth()->onceUsingId($userSwitch->user_id);
