<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DecodeUserFromJWT
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Take the bearer token from the header, decode the payload, and take user's id
        $headers = getallheaders();
        throw_if(!array_key_exists('Authorization', $headers), new HttpException(401));
        $token = $headers['Authorization'];
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);
        $request['uid'] = $jwtPayload->sub;

        return $next($request);
    }
}
