<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\ResponseTrait;

class token
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if(!$token)
        {
            return $this->errors(errors: ["error" => "login failed"], code: 401);
        }

        $user = User::where('token', $token)->first();

        if(!$user)
        {
            return $this->errors(errors: ["error" => "login failed"], code: 401);
        }

        auth()->login($user);

        return $next($request);
    }
}
