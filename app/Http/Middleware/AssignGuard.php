<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Exception;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($guard != null)
        {
            auth()->shouldUse($guard);
        }

        $user = auth()->user(); //user authenticated

        if (!$request->has("token"))
        {
            return response()->json(['status' => 498, 'message' => 'Authorization Token not found'], 200);
        }
        
        if ($user == null)
        {
            return response()->json(['status' => 498, 'message' => 'Invalid or expired token'], 200);
        }

        return $next($request);
    }
}