<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Inertia\Inertia;

class MaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if (config('app.maintenance_mode', false)) {
            // Allow access to maintenance page itself to avoid redirect loops
            if ($request->is('maintenance')) {
                return $next($request);
            }
            
            // Redirect all other requests to maintenance page
            return redirect()->route('maintenance');
        } else {
            // If maintenance mode is disabled and user tries to access maintenance page
            if ($request->is('maintenance')) {
                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
