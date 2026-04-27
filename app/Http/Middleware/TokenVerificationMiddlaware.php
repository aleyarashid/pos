<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddlaware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');
        $result = JWTToken::VerifyToken($token);
        if($result=='unauthorized')
        {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized'
            ]);
        }
        else
        {
            $request->headers->set('userEmail', $result->userEmail);
            $request->headers->set('id', $result->userId);
            return $next($request);
        }

    }
}
