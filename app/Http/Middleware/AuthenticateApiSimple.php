<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiSimple
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');
        
        // Verificar primero si hay token Bearer válido
        if ($authHeader && strpos($authHeader, 'Bearer ') === 0) {
            return $next($request);
        }
        
        // Si no hay token Bearer, verificar rutas sensibles
        if ($this->isPagosRoute($request) || $this->isTratamientosRoute($request) || $this->isCitasRoute($request)) {
            return $this->handleSensitiveRoute($request, $next);
        }
        
        // Para otras rutas, requerir autenticación estricta
        if (!$authHeader) {
            return response()->json([
                'error' => 'No Authorization header'
            ], 401);
        }
        
        return response()->json([
            'error' => 'Invalid Authorization format'
        ], 401);
    }
    
    /**
     * Manejar rutas sensibles con verificación de sesión
     */
    private function handleSensitiveRoute(Request $request, Closure $next): Response
    {
        // Verificar si hay sesión de usuario válida
        $user = session('user');
        if ($user && isset($user['id'])) {
            // Log de acceso por sesión
            \Log::info('Acceso por sesión a ruta sensible', [
                'route' => $request->path(),
                'user_id' => $user['id'],
                'user_name' => $user['nombre'] ?? 'N/A',
                'ip' => $request->ip(),
                'method' => $request->method()
            ]);
            return $next($request);
        }
        
        // Verificar si es una ruta de consulta (GET) vs modificación (POST/PUT/DELETE)
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            // Operaciones de modificación requieren autenticación estricta
            \Log::warning('Intento de operación sin autenticación', [
                'route' => $request->path(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            return response()->json([
                'error' => 'Autenticación requerida para operaciones de modificación',
                'required' => 'Por favor inicie sesión para continuar'
            ], 401);
        }
        
        // Para consultas (GET), permitir con log de advertencia
        \Log::warning('Acceso sin autenticación a consulta', [
            'route' => $request->path(),
            'method' => $request->method(),
            'ip' => $request->ip()
        ]);
        
        return $next($request);
    }
    
    /**
     * Verificar si la ruta es de pagos
     */
    private function isPagosRoute(Request $request): bool
    {
        return strpos($request->path(), 'api/pagos') === 0;
    }
    
    /**
     * Verificar si la ruta es de tratamientos
     */
    private function isTratamientosRoute(Request $request): bool
    {
        return strpos($request->path(), 'api/tratamientos') === 0;
    }
    
    /**
     * Verificar si la ruta es de citas
     */
    private function isCitasRoute(Request $request): bool
    {
        return strpos($request->path(), 'api/citas') === 0;
    }
}
