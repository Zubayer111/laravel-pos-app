<?php

namespace App\Http\Middleware;

use App\Helper\JWTtoken;
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
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie("token");
        // return $token;
        $result = JWTtoken::verifyToken($token);

        if($result == "unauthorized"){
            return redirect("/userLogin");
        }
        else{
            $request->headers->set("email",$result->userEmail);
            $request->headers->set("id",$result->userId);
            return $next($request);
        }
        
    }
}
