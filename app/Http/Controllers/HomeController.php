<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $stats = [
            'totalPlayers' => 0,
            'totalCharacters' => 0,
            'totalBattles' => 0,
            'highestLevel' => 0,
        ];

        return Inertia::render('Welcome', [
            'user' => Auth::user()?->load('characters'),
            'stats' => $stats,
        ]);
    }
}
