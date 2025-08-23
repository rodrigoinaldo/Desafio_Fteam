<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IntegracaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->hasHeader('X-Client-Id')) {
            return response()->json([
                'message' => 'Integração não autorizada'
            ], 400);

        }

        //pegar o inicio da execução
        $start = microtime(true);
        $response = $next($request);
        $daration = round((microtime(true) - $start) * 1000,2);

        Log::info('Requisição processada',(
            [
                'client_id' => $request->header('X-Client-Id'),
                'rota' => $request->path(),
                'status' => $response->getStatusCode(),
                'duration' => $daration
            ]
            ));


        return $response;
    }
}
