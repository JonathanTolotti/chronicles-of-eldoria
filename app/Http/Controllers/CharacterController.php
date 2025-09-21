<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateCharacterRequest;
use App\Services\CharacterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CharacterController extends Controller
{
    public function __construct(
        private CharacterService $characterService
    ) {}

    public function create(): Response
    {
        return Inertia::render('Character/Create');
    }

    public function select(): Response
    {
        $user = auth()->user();
        $characters = $user->characters()->with('activeFrame')->get();
        
        return Inertia::render('Character/Select', [
            'characters' => $characters,
            'user' => $user
        ]);
    }

    public function store(CreateCharacterRequest $request): RedirectResponse
    {
        $character = $this->characterService->createCharacter(
            auth()->user(),
            $request->validated()
        );

        return redirect()->route('characters.select')
            ->with('success', "Personagem {$character->name} criado com sucesso!");
    }

    public function confirmSelection(Request $request): RedirectResponse
    {
        $request->validate([
            'character_id' => 'required|exists:characters,id'
        ]);

        $user = auth()->user();
        $character = $user->characters()->findOrFail($request->character_id);
        
        $user->update(['active_character_id' => $character->id]);

        return redirect()->route('dashboard')
            ->with('success', "Agora você está jogando com {$character->name}!");
    }
}
