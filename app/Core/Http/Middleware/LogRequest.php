<?php

namespace App\Core\Http\Middleware;

use Closure;

class LogRequest
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
        $response = $next($request);
        $log = sprintf('IP: %s | endpoint: %s | Request: ', request()->ip(), request()->path());
        app('log')->info($log, $request->all());

        return $response;
    }
}
