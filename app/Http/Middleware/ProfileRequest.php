<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware de profiling para diagnosticar lentidão.
 *
 * Mede o tempo total da request, número de queries no banco e tempo
 * total gasto em queries. Loga tudo no canal padrão.
 *
 * Como ativar (uma rota específica):
 *   Route::post('/login', [LoginController::class, 'login'])
 *       ->middleware(\App\Http\Middleware\ProfileRequest::class);
 *
 * Como ativar globalmente (em bootstrap/app.php):
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->web(append: [\App\Http\Middleware\ProfileRequest::class]);
 *   })
 *
 * Os resultados aparecem no header HTTP X-Profile e no log:
 *   X-Profile: total=843ms queries=12 query_time=234ms
 *
 * Lembre de remover/desativar depois do diagnóstico — em produção
 * fica caro ligar listener de queries em todo request.
 */
class ProfileRequest
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $queryCount = 0;
        $queryTime = 0.0;
        $slowQueries = [];

        DB::listen(function ($query) use (&$queryCount, &$queryTime, &$slowQueries) {
            $queryCount++;
            $queryTime += $query->time;

            // Captura queries que sozinhas levaram mais de 50ms
            if ($query->time > 50) {
                $slowQueries[] = sprintf(
                    '[%dms] %s',
                    $query->time,
                    str_replace(["\n", '  '], [' ', ' '], $query->sql)
                );
            }
        });

        /** @var Response $response */
        $response = $next($request);

        $totalMs = (int) ((microtime(true) - $start) * 1000);
        $queryMs = (int) $queryTime;

        $summary = sprintf(
            'total=%dms queries=%d query_time=%dms',
            $totalMs, $queryCount, $queryMs
        );

        $response->headers->set('X-Profile', $summary);

        Log::info('PROFILE ' . $request->method() . ' ' . $request->path() . ' :: ' . $summary);

        if (!empty($slowQueries)) {
            Log::warning('PROFILE slow queries on ' . $request->path(), $slowQueries);
        }

        return $response;
    }
}
