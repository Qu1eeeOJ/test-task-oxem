<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\Controller;
use App\User;

class ApiAccessible extends Controller
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
        if (User::where('api_token', $request->token)->count() == 0) {
            return $this->eApi();
        }

        return $next($request);
    }
}
