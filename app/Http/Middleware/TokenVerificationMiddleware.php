<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

     /* Make Middleware : 
     php artisan make:middleware TokenVerificationMiddleware 
     */
    public function handle(Request $request, Closure $next): Response
    {

        // $token = $request->header('token');
        $token = $request->cookie('token');
        $result = JWTToken::verifyToken($token);
        if($result == 'unauthorized'){
            /*
            return response()->json([
                'status' => 'failed',
                'message' => 'unauthorized'
            ], 401);
            */
            return redirect('/userLogin');
        }else{
            /*
            object থেকে data পেতে হলে $result->userEmail এবং $result->userID দিতে হবে। header এ set করার জন্য।
            */ 
            $request->headers->set('email', $result->userEmail);
            $request->headers->set('id', $result->userID);
            return $next($request);
        }
    }
    
}
