<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\BaseController;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class AuthApi extends BaseController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = auth()->guard('api')->userOrFail();
            return $next($request);
        } catch (UserNotDefinedException $e) {
            return $this->responseError('You cannot access this system.',[
                'error' => 'error'
            ],400);
        }
    }
}
