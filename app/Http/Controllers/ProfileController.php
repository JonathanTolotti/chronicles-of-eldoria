<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'biography' => $user->biography,
                'profile_public' => $user->profile_public,
                'is_staff' => $user->is_staff,
                'is_vip' => $user->isVip(),
                'avatar_url' => $user->getAvatarUrl(),
                'created_at' => $user->created_at,
                'last_seen_at' => $user->last_seen_at,
                'characters_count' => $user->characters()->count(),
                'highest_level_character' => $user->characters()->max('level'),
            ],
            'available_avatars' => $this->getAvailableAvatars(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update user profile information (avatar, biography, etc.)
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'string', 'in:' . implode(',', array_keys($this->getAvailableAvatars()))],
            'biography' => ['nullable', 'string', 'max:2000'],
            'profile_public' => ['boolean'],
        ]);

        $user = $request->user();
        
        // Sanitizar biografia HTML
        $biography = $request->biography ? $this->sanitizeHtml($request->biography) : null;
        
        $user->update([
            'avatar' => $request->avatar,
            'biography' => $biography,
            'profile_public' => $request->boolean('profile_public'),
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Show public profile
     */
    public function show(Request $request, $userId): Response
    {
        $user = \App\Models\User::findOrFail($userId);
        
        // Verificar se o perfil é público
        if (!$user->profile_public && $user->id !== $request->user()?->id) {
            abort(403, 'Este perfil é privado.');
        }

        return Inertia::render('Profile/Show', [
            'profile' => [
                'id' => $user->id,
                'name' => $user->name,
                'avatar' => $user->avatar,
                'avatar_url' => $user->getAvatarUrl(),
                'biography' => $user->getSanitizedBiography(),
                'is_staff' => $user->is_staff,
                'is_vip' => $user->isVip(),
                'created_at' => $user->created_at,
                'last_seen_at' => $user->last_seen_at,
                'characters_count' => $user->characters()->count(),
                'highest_level_character' => $user->characters()->max('level'),
            ],
        ]);
    }

    /**
     * Get available avatars
     */
    private function getAvailableAvatars(): array
    {
        return [
            'default' => 'Avatar Padrão',
            'warrior' => 'Guerreiro',
            'mage' => 'Mago',
            'archer' => 'Arqueiro',
            'rogue' => 'Ladino',
            'knight' => 'Cavaleiro',
            'priest' => 'Sacerdote',
            'druid' => 'Druida',
            'paladin' => 'Paladino',
            'necromancer' => 'Necromante',
        ];
    }

    /**
     * Sanitize HTML content
     */
    private function sanitizeHtml(string $html): string
    {
        // Permitir apenas tags seguras
        $allowedTags = '<p><br><strong><em><u><ol><ul><li><h1><h2><h3><h4><h5><h6>';
        
        // Remover scripts e outros elementos perigosos
        $html = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $html);
        $html = preg_replace('/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi', '', $html);
        $html = preg_replace('/on\w+="[^"]*"/i', '', $html);
        $html = preg_replace('/on\w+=\'[^\']*\'/i', '', $html);
        
        return strip_tags($html, $allowedTags);
    }
}
