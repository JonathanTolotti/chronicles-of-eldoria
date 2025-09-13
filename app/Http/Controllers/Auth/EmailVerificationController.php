<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Email de verificação reenviado!');
    }
}
