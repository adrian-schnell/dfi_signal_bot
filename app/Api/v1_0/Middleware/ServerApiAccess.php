<?php

namespace App\Api\v1_0\Middleware;

use App\Models\Server;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServerApiAccess
{
    public function handle(Request $request, Closure $next)
    {
        $serverId = $request->header('server_id', null);
        $apiKey   = $request->header('api-key', null);
        $server = Server::where('id', $serverId)
            ->whereHas('user', function ($query) use ($apiKey) {
                $query->where('user_sync_key', $apiKey);
            })->first();

        if (is_null($server)) {
            return response()->json([
                'state'  => 'error',
                'reason' => 'not authorized',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $request->merge([
            'server' => $server,
        ]);
ray($request);
        return $next($request);
    }
}
