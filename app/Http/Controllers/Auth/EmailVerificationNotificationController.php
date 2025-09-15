<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationMedieval;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Usar o sistema personalizado de email
        $verificationUrl = URL::signedRoute('verification.verify', [
            'id' => $request->user()->id,
            'hash' => sha1($request->user()->email)
        ]);
        
        try {
            Mail::to($request->user()->email)->send(new EmailVerificationMedieval($verificationUrl));
            \Log::info('Email de verificação enviado via notification controller', ['user_id' => $request->user()->id, 'email' => $request->user()->email]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de verificação via notification controller', [
                'user_id' => $request->user()->id, 
                'email' => $request->user()->email,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Falha ao enviar email de verificação: ' . $e->getMessage());
        }

        return back()->with('status', 'verification-link-sent');
    }
}
