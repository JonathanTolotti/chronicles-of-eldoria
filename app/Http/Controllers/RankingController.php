<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RankingController extends Controller
{
    /**
     * Mostra a página de ranking
     */
    public function index(): Response
    {
        $levelRanking = $this->getLevelRanking();
        $powerRanking = $this->getPowerRanking();
        
        return Inertia::render('Ranking', [
            'levelRanking' => $levelRanking,
            'powerRanking' => $powerRanking,
        ]);
    }
    
    /**
     * Retorna o ranking por nível (limitado a 100)
     */
    public function getLevelRanking(): array
    {
        return Character::select('id', 'name', 'level', 'power', 'class')
            ->orderBy('level', 'desc')
            ->orderBy('power', 'desc')
            ->orderBy('name', 'asc')
            ->limit(100)
            ->get()
            ->map(function ($character, $index) {
                return [
                    'position' => $index + 1,
                    'id' => $character->id,
                    'name' => $character->name,
                    'level' => $character->level,
                    'power' => $character->power,
                    'class' => $character->class,
                ];
            })
            ->toArray();
    }
    
    /**
     * Retorna o ranking por CP (limitado a 100)
     */
    public function getPowerRanking(): array
    {
        return Character::select('id', 'name', 'level', 'power', 'class')
            ->orderBy('power', 'desc')
            ->orderBy('level', 'desc')
            ->orderBy('name', 'asc')
            ->limit(100)
            ->get()
            ->map(function ($character, $index) {
                return [
                    'position' => $index + 1,
                    'id' => $character->id,
                    'name' => $character->name,
                    'level' => $character->level,
                    'power' => $character->power,
                    'class' => $character->class,
                ];
            })
            ->toArray();
    }
    
    /**
     * API endpoint para buscar rankings via AJAX
     */
    public function getRankings(Request $request): \Illuminate\Http\JsonResponse
    {
        $type = $request->get('type', 'level');
        
        if ($type === 'power') {
            $ranking = $this->getPowerRanking();
        } else {
            $ranking = $this->getLevelRanking();
        }
        
        return response()->json([
            'ranking' => $ranking,
            'type' => $type,
        ]);
    }
}
