<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Se não estiver autenticado, deixar passar
        if (!Auth::check()) {
            return $next($request);
        }

        // Se estiver autenticado mas não verificou email
        if (!Auth::user()->hasVerifiedEmail()) {
            // Permitir acesso apenas às rotas de verificação e logout
            $allowedRoutes = [
                'verification.notice',
                'verification.verify',
                'verification.send',
                'verification.resend',
                'email.verified',
                'logout',
                'profile.edit',
                'profile.update',
                'profile.destroy',
            ];

            // Se não for uma rota permitida, redirecionar para verificação
            if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                return redirect()->route('verification.notice')
                    ->with('message', 'Você precisa verificar seu email para acessar o jogo!');
            }
        }

        return $next($request);
    }
}