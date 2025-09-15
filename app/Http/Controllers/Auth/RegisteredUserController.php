<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use App\Mail\EmailVerificationMedieval;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'uuid' => Str::uuid()->toString(),
        ]);

        // Enviar email de verificação personalizado
        $verificationUrl = URL::signedRoute('verification.verify', [
            'id' => $user->id,
            'hash' => sha1($user->email)
        ]);
        
        try {
            Mail::to($user->email)->send(new EmailVerificationMedieval($verificationUrl));
            \Log::info('Email de verificação enviado com sucesso', ['user_id' => $user->id, 'email' => $user->email]);
        } catch (\Exception $e) {
            \Log::error('Erro ao enviar email de verificação personalizado', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            // Não usar o sistema padrão do Laravel - apenas logar o erro
            throw new \Exception('Falha ao enviar email de verificação: ' . $e->getMessage());
        }

        // Fazer login para acessar a página de verificação
        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('message', 'Verifique seu email para ativar sua conta!');
    }
}
