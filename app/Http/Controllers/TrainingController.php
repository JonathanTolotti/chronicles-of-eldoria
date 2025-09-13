<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CharacterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TrainingController extends Controller
{
    public function __construct(
        private CharacterService $characterService
    ) {}

    public function index(): Response
    {
        $user = auth()->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para treinar.');
        }

        return Inertia::render('Training/Index', [
            'character' => $character,
        ]);
    }

    public function start(Request $request): RedirectResponse
    {
        $request->validate([
            'stat' => 'required|in:strength,dexterity,constitution,intelligence,luck',
            'duration' => 'required|integer|min:1|max:60'
        ]);

        $user = auth()->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem para treinar.');
        }

        if ($character->isTraining()) {
            return back()->with('error', 'Você já está treinando! Complete o treinamento atual primeiro.');
        }

        $this->characterService->startTraining(
            $character, 
            $request->stat, 
            (int) $request->duration
        );

        return back()->with('success', 'Treinamento iniciado com sucesso!');
    }

    public function complete(): RedirectResponse
    {
        $user = auth()->user();
        $character = $user->activeCharacter;
        
        if (!$character) {
            return redirect()->route('characters.select')
                ->with('error', 'Você precisa selecionar um personagem.');
        }

        if (!$character->isTraining()) {
            return back()->with('error', 'Você não está treinando no momento.');
        }

        $this->characterService->completeTraining($character);

        return back()->with('success', 'Treinamento concluído! Seus atributos foram melhorados.');
    }
}
