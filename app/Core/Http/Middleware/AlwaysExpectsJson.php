<?php

namespace App\Core\Http\Middleware;

class AlwaysExpectsJson
{
    /**
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $request->headers->add(['Accept' => 'application/json']);

        return $next($request);
    }
}
