<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Battle Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all battle-related configuration values that can be
    | easily modified for balancing and future events.
    |
    */

    'damage' => [
        // Base damage calculation: (attack_power * multiplier) - (defense * reduction)
        'attack_multiplier' => env('BATTLE_ATTACK_MULTIPLIER', 0.3),
        'defense_reduction' => env('BATTLE_DEFENSE_REDUCTION', 0.5),
        
        // Randomization factor (±percentage)
        'randomization_min' => env('BATTLE_DAMAGE_MIN', 0.85),
        'randomization_max' => env('BATTLE_DAMAGE_MAX', 1.15),
        
        // Minimum damage guaranteed
        'minimum_damage' => env('BATTLE_MIN_DAMAGE', 1),
    ],

    'critical_hit' => [
        // Critical hit multiplier
        'multiplier' => env('BATTLE_CRIT_MULTIPLIER', 1.3),
        
        // Base critical chance per luck point
        'luck_multiplier' => env('BATTLE_CRIT_LUCK_MULTIPLIER', 0.01),
        
        // Maximum critical chance (50%)
        'max_chance' => env('BATTLE_CRIT_MAX_CHANCE', 0.5),
        
        // Monster critical chance (5%)
        'monster_chance' => env('BATTLE_MONSTER_CRIT_CHANCE', 0.05),
    ],

    'hit_chance' => [
        // Base hit chance (80%)
        'base_chance' => env('BATTLE_HIT_BASE_CHANCE', 0.8),
        
        // Speed difference multiplier per point
        'speed_multiplier' => env('BATTLE_HIT_SPEED_MULTIPLIER', 0.05),
        
        // Hit chance limits
        'min_chance' => env('BATTLE_HIT_MIN_CHANCE', 0.1),
        'max_chance' => env('BATTLE_HIT_MAX_CHANCE', 0.95),
    ],

    'events' => [
        // Event multipliers (for future implementation)
        'exp_multiplier' => env('EVENT_EXP_MULTIPLIER', 1.0),
        'gold_multiplier' => env('EVENT_GOLD_MULTIPLIER', 1.0),
        'damage_multiplier' => env('EVENT_DAMAGE_MULTIPLIER', 1.0),
        'drop_rate_multiplier' => env('EVENT_DROP_RATE_MULTIPLIER', 1.0),
    ],

    'monster_defense' => [
        // Character defense against monsters (currently 0, but configurable)
        'character_defense' => env('BATTLE_CHARACTER_DEFENSE', 0),
    ],
];
