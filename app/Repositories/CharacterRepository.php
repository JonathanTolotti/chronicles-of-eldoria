<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class CharacterRepository
{
    public function create(array $data): Character
    {
        return Character::create($data);
    }

    public function findById(int $id): ?Character
    {
        return Character::find($id);
    }

    public function findByUuid(string $uuid): ?Character
    {
        return Character::where('uuid', $uuid)->first();
    }

    public function findByUser(User $user): Collection
    {
        return Character::where('user_id', $user->id)->get();
    }

    public function findByName(string $name): ?Character
    {
        return Character::where('name', $name)->first();
    }

    public function update(Character $character, array $data): bool
    {
        return $character->update($data);
    }

    public function delete(Character $character): bool
    {
        return $character->delete();
    }

    public function getTopCharactersByLevel(int $limit = 10): Collection
    {
        return Character::orderBy('level', 'desc')
            ->orderBy('power', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getCharactersInTraining(): Collection
    {
        return Character::whereNotNull('training_stat')
            ->where('training_ends_at', '<=', now())
            ->get();
    }

    public function getTotalCharactersCount(): int
    {
        return Character::count();
    }

    public function getHighestLevel(): int
    {
        return Character::max('level') ?? 0;
    }
}
