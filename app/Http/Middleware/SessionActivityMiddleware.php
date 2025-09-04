<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class SessionActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Solo aplicar en desarrollo y cuando hay sesión de usuario
        if (config('app.env') === 'local' || config('app.debug') === true) {
            $user = session('user');
            
            if ($user && isset($user['logged_in']) && $user['logged_in'] === true) {
                // Verificar si la sesión ha expirado
                $lastActivity = isset($user['last_activity']) ? 
                    Carbon::parse($user['last_activity']) : 
                    Carbon::now()->subHours(2); // Si no hay last_activity, asumir expirado
                
                if ($lastActivity->diffInHours(now()) > 1) {
                    // Sesión expirada - limpiar
                    session()->forget('user');
                    Auth::logout();
                    
                    \Log::info('Sesión expirada limpiada', [
                        'user_id' => $user['id'],
                        'last_activity' => $lastActivity,
                        'hours_inactive' => $lastActivity->diffInHours(now())
                    ]);
                } else {
                    // Actualizar última actividad en la sesión
                    $user['last_activity'] = now();
                    session(['user' => $user]);
                    
                    // Actualizar en BD cada 5 minutos para no sobrecargar
                    if ($lastActivity->diffInMinutes(now()) > 5) {
                        try {
                            \DB::table('usuarios')
                                ->where('id', $user['id'])
                                ->update(['last_activity' => now()]);
                        } catch (\Exception $e) {
                            \Log::warning('Error actualizando last_activity', [
                                'user_id' => $user['id'],
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                }
            }
        }
        
        return $next($request);
    }
}
