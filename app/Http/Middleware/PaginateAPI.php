<?php

namespace App\Http\Middleware;

use BadMethodCallException;
use Closure;
use Illuminate\Http\Request;

class PaginateAPI
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $data = $response->getData(true);
        } catch (BadMethodCallException $e) {
            return $response;
        }

        if (isset($data['meta']['links'])) {
            unset($data['meta']['links']);
        }

        $response->setData($data);

        return $response;
    }
}
