<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMedieval;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationController extends Controller
{
    public function verify(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('welcome')
                ->with('success', 'Email já verificado!');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }


        return redirect()->route('characters.create')
            ->with('success', 'Email verificado com sucesso! Agora crie seu personagem!');
    }

    public function resend(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('welcome');
        }

        // Enviar email de verificação personalizado
        $verificationUrl = URL::signedRoute('verification.verify', [
            'id' => $request->user()->id,
            'hash' => sha1($request->user()->email)
        ]);
        
        try {
            Mail::to($request->user()->email)->send(new EmailVerificationMedieval($verificationUrl));
            \Log::info('Email de verificação reenviado com sucesso', ['user_id' => $request->user()->id, 'email' => $request->user()->email]);
        } catch (\Exception $e) {
            \Log::error('Erro ao reenviar email de verificação personalizado', [
                'user_id' => $request->user()->id, 
                'email' => $request->user()->email,
                'error' => $e->getMessage()
            ]);
            // Não usar o sistema padrão do Laravel - apenas logar o erro
            return back()->with('error', 'Falha ao enviar email de verificação: ' . $e->getMessage());
        }

        return back()->with('message', 'Email de verificação reenviado!');
    }
}
