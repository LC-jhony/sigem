<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptimizePdfResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Solo optimizar respuestas PDF
        if ($response->headers->get('Content-Type') === 'application/pdf') {
            // Agregar headers de optimización
            $response->headers->set('Cache-Control', 'public, max-age=3600, must-revalidate');
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
            
            // Comprimir respuesta si es posible
            if (function_exists('gzencode') && !$response->headers->has('Content-Encoding')) {
                $content = $response->getContent();
                if (strlen($content) > 1024) { // Solo comprimir si es mayor a 1KB
                    $compressed = gzencode($content, 6);
                    if ($compressed !== false && strlen($compressed) < strlen($content)) {
                        $response->setContent($compressed);
                        $response->headers->set('Content-Encoding', 'gzip');
                        $response->headers->set('Content-Length', strlen($compressed));
                    }
                }
            }
        }

        return $response;
    }
}
