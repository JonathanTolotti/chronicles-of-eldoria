<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateCharacterRequest;
use App\Services\CharacterService;
use Illuminate\Http\RedirectResponse;
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

    public function store(CreateCharacterRequest $request): RedirectResponse
    {
        $character = $this->characterService->createCharacter(
            auth()->id(),
            $request->validated()
        );

        return redirect()->route('dashboard')
            ->with('success', "Personagem {$character->name} criado com sucesso!");
    }
}
