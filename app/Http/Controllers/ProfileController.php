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
        $character = $user->activeCharacter?->load('activeFrame');
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para acessar o perfil.');
        }
        
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'is_staff' => $user->is_staff,
                'is_vip' => $user->isVip(),
                'created_at' => $user->created_at,
                'last_seen_at' => $user->last_seen_at,
                'characters_count' => $user->characters()->count(),
                'highest_level_character' => $user->characters()->max('level'),
            ],
            'character' => [
                'id' => $character->id,
                'name' => $character->name,
                'class' => $character->class,
                'level' => $character->level,
                'experience' => $character->experience,
                'experience_to_next_level' => $character->experience_to_next_level,
                'strength' => $character->strength,
                'dexterity' => $character->dexterity,
                'constitution' => $character->constitution,
                'intelligence' => $character->intelligence,
                'luck' => $character->luck,
                'max_hp' => $character->max_hp,
                'current_hp' => $character->current_hp,
                'max_stamina' => $character->max_stamina,
                'current_stamina' => $character->current_stamina,
                'power' => $character->power,
                'gold' => $character->gold,
                'stat_points' => $character->stat_points,
                'avatar' => $character->avatar,
                'biography' => $character->biography,
                'profile_public' => $character->profile_public,
                'avatar_url' => $character->getAvatarUrl(),
                'active_frame_id' => $character->active_frame_id,
                'active_frame' => $character->activeFrame ? [
                    'id' => $character->activeFrame->id,
                    'name' => $character->activeFrame->name,
                    'image_path' => $character->activeFrame->image_path,
                ] : null,
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

        return back()->with('status', 'profile-updated');
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
     * Update character profile information (avatar, biography, etc.)
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['nullable', 'string'],
            'biography' => ['nullable', 'string', 'max:2000'],
            'profile_public' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para atualizar o perfil.');
        }
        
        // Preparar dados para atualização
        $updateData = [];
        
        if ($request->has('avatar')) {
            $updateData['avatar'] = $request->avatar;
        }
        
        if ($request->has('biography')) {
            $updateData['biography'] = $request->biography ? $this->sanitizeHtml($request->biography) : null;
        }
        
        if ($request->has('profile_public')) {
            $updateData['profile_public'] = $request->boolean('profile_public');
        }
        
        $character->update($updateData);

        return back()->with('status', 'profile-updated');
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

        return back()->with('status', 'password-updated');
    }

    /**
     * Show public profile
     */
    public function show(Request $request, $characterName): Response
    {
        $character = \App\Models\Character::where('name', $characterName)->with('activeFrame')->firstOrFail();
        $user = $character->user;
        
        // Verificar se o perfil é público
        if (!$character->profile_public && $user->id !== $request->user()?->id) {
            abort(403, 'Este perfil é privado.');
        }

        // Buscar equipamentos equipados (mesmo sistema do dashboard)
        $equippedItems = \App\Models\CharacterEquipment::where('character_id', $character->id)
            ->where('is_equipped', true)
            ->with('equipment')
            ->get()
            ->keyBy('slot');
        
        
        return Inertia::render('Profile/Show', [
            'profile' => [
                'id' => $character->id,
                'name' => $character->name,
                'class' => $character->class,
                'level' => $character->level,
                'experience' => $character->experience,
                'experience_to_next_level' => $character->experience_to_next_level,
                'strength' => $character->strength,
                'dexterity' => $character->dexterity,
                'constitution' => $character->constitution,
                'intelligence' => $character->intelligence,
                'luck' => $character->luck,
                'max_hp' => $character->max_hp,
                'current_hp' => $character->current_hp,
                'max_stamina' => $character->max_stamina,
                'current_stamina' => $character->current_stamina,
                'power' => $character->power,
                'gold' => $character->gold,
                'stat_points' => $character->stat_points,
                'avatar' => $character->avatar,
                'avatar_url' => $character->getAvatarUrl(),
                'biography' => $character->getSanitizedBiography(),
                'active_frame_id' => $character->active_frame_id,
                'active_frame' => $character->activeFrame ? [
                    'id' => $character->activeFrame->id,
                    'name' => $character->activeFrame->name,
                    'image_path' => $character->activeFrame->image_path,
                ] : null,
                'is_staff' => $user->is_staff,
                'is_vip' => $user->isVip(),
                'created_at' => $user->created_at,
                'last_seen_at' => $user->last_seen_at,
                'characters_count' => $user->characters()->count(),
                'highest_level_character' => $user->characters()->max('level'),
                'equipment' => [
                    'equipped' => $equippedItems->toArray()
                ],
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
            '1' => 'Avatar 1',
            '2' => 'Avatar 2',
            '3' => 'Avatar 3',
            '4' => 'Avatar 4',
            '5' => 'Avatar 5',
            '6' => 'Avatar 6',
            '7' => 'Avatar 7',
            '8' => 'Avatar 8',
            '9' => 'Avatar 9',
            '10' => 'Avatar 10',
            '11' => 'Avatar 11',
            '12' => 'Avatar 12',
            '13' => 'Avatar 13',
            '14' => 'Avatar 14',
            '15' => 'Avatar 15',
            '16' => 'Avatar 16',
            '17' => 'Avatar 17',
            '18' => 'Avatar 18',
            '19' => 'Avatar 19',
            '20' => 'Avatar 20',
            '21' => 'Avatar 21',
            '22' => 'Avatar 22',
            '23' => 'Avatar 23',
            '24' => 'Avatar 24',
            '25' => 'Avatar 25',
            '26' => 'Avatar 26',
            '27' => 'Avatar 27',
            '28' => 'Avatar 28',
            '29' => 'Avatar 29',
            '30' => 'Avatar 30',
            '31' => 'Avatar 31',
            '32' => 'Avatar 32',
            '33' => 'Avatar 33',
            '34' => 'Avatar 34',
            '35' => 'Avatar 35',
            '36' => 'Avatar 36',
            '37' => 'Avatar 37',
            '38' => 'Avatar 38',
            '39' => 'Avatar 39',
            '40' => 'Avatar 40',
            '41' => 'Avatar 41',
            '42' => 'Avatar 42',
            '43' => 'Avatar 43',
            '44' => 'Avatar 44',
            '45' => 'Avatar 45',
            '46' => 'Avatar 46',
            '47' => 'Avatar 47',
            '48' => 'Avatar 48',
            '49' => 'Avatar 49',
            '50' => 'Avatar 50',
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
