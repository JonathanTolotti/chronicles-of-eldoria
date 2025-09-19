<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Character;
use App\Models\CharacterEquipment;
use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class EquipmentService
{
    /**
     * Equipar um item em um slot específico
     */
    public function equipItem(Character $character, int $equipmentId, string $slot): array
    {
        return DB::transaction(function () use ($character, $equipmentId, $slot) {
            // Verificar se o equipamento existe
            $equipment = Equipment::findOrFail($equipmentId);
            
            // Verificar se o slot é válido
            $validSlots = ['helmet', 'armor', 'weapon', 'shield', 'boots', 'pants'];
            if (!in_array($slot, $validSlots)) {
                throw new \InvalidArgumentException('Slot inválido');
            }

            // Verificar se o tipo do equipamento corresponde ao slot
            if ($equipment->type !== $slot) {
                throw new \InvalidArgumentException('Tipo de equipamento não corresponde ao slot');
            }

            // Desequipar qualquer equipamento atual neste slot
            CharacterEquipment::where('character_id', $character->id)
                ->where('slot', $slot)
                ->where('is_equipped', true)
                ->update(['is_equipped' => false]);

            // Buscar ou criar o equipamento do personagem
            $characterEquipment = CharacterEquipment::firstOrCreate(
                ['character_id' => $character->id, 'equipment_id' => $equipmentId],
                ['slot' => $slot, 'current_tier' => 0, 'is_equipped' => true]
            );

            // Se já existe, apenas equipar (manter tier)
            if (!$characterEquipment->is_equipped) {
                $characterEquipment->update([
                    'is_equipped' => true,
                    'slot' => $slot
                ]);
            }

            // Recalcular stats do personagem
            $this->recalculateCharacterStats($character);

            return [
                'success' => true,
                'message' => "Equipamento {$equipment->name} equipado com sucesso!",
                'character' => $character->fresh()
            ];
        });
    }

    /**
     * Desequipar um item de um slot específico
     */
    public function unequipItem(Character $character, string $slot): array
    {
        return DB::transaction(function () use ($character, $slot) {
            // Buscar o equipamento equipado no slot
            $characterEquipment = CharacterEquipment::where('character_id', $character->id)
                ->where('slot', $slot)
                ->where('is_equipped', true)
                ->first();

            if (!$characterEquipment || !$characterEquipment->equipment_id) {
                throw new \InvalidArgumentException('Nenhum equipamento equipado encontrado neste slot');
            }

            $equipmentName = $characterEquipment->equipment->name;

            // Desequipar o equipamento (manter no inventário)
            $characterEquipment->update(['is_equipped' => false]);

            // Recalcular stats do personagem
            $this->recalculateCharacterStats($character);

            return [
                'success' => true,
                'message' => "Equipamento {$equipmentName} desequipado com sucesso!",
                'character' => $character->fresh()
            ];
        });
    }

    /**
     * Recalcular todos os stats do personagem baseado nos equipamentos
     */
    public function recalculateCharacterStats(Character $character): void
    {
        // Obter bônus de todos os equipamentos primeiro
        $equipmentBonuses = $this->getTotalEquipmentBonuses($character);

        // Obter stats base do personagem (sem equipamentos)
        $baseStats = $this->getBaseCharacterStats($character);

        // Calcular stats finais
        $finalStats = [
            'strength' => $baseStats['strength'] + $equipmentBonuses['strength'],
            'dexterity' => $baseStats['dexterity'] + $equipmentBonuses['dexterity'],
            'constitution' => $baseStats['constitution'] + $equipmentBonuses['constitution'],
            'intelligence' => $baseStats['intelligence'] + $equipmentBonuses['intelligence'],
            'luck' => $baseStats['luck'] + $equipmentBonuses['luck'],
        ];

        // Calcular HP e MP baseado nos stats
        $finalStats['max_hp'] = $this->calculateMaxHP($finalStats['constitution'], $character->level);
        $finalStats['max_stamina'] = $this->calculateMaxStamina($finalStats['intelligence'], $character->level);

        // Adicionar bônus diretos de HP e MP dos equipamentos
        $finalStats['max_hp'] += $equipmentBonuses['hp'];
        $finalStats['max_stamina'] += $equipmentBonuses['mp'];

        // Calcular CP (Poder de Combate)
        $finalStats['power'] = $this->calculatePower($finalStats, $character->level, $equipmentBonuses);

        // Atualizar o personagem
        $character->update($finalStats);

        // Garantir que HP atual não exceda HP máximo
        if ($character->current_hp > $character->max_hp) {
            $character->update(['current_hp' => $character->max_hp]);
        }

        // Garantir que Stamina atual não exceda Stamina máxima
        if ($character->current_stamina > $character->max_stamina) {
            $character->update(['current_stamina' => $character->max_stamina]);
        }
    }

    /**
     * Obter stats base do personagem (sem equipamentos)
     * Usa os stats atuais do personagem, removendo apenas os bônus de equipamentos
     */
    private function getBaseCharacterStats(Character $character): array
    {
        // Obter bônus atuais de equipamentos
        $currentEquipmentBonuses = $this->getTotalEquipmentBonuses($character);

        // Calcular stats base removendo os bônus de equipamentos
        return [
            'strength' => $character->strength - $currentEquipmentBonuses['strength'],
            'dexterity' => $character->dexterity - $currentEquipmentBonuses['dexterity'],
            'constitution' => $character->constitution - $currentEquipmentBonuses['constitution'],
            'intelligence' => $character->intelligence - $currentEquipmentBonuses['intelligence'],
            'luck' => $character->luck - $currentEquipmentBonuses['luck'],
        ];
    }

    /**
     * Obter total de bônus de todos os equipamentos
     */
    private function getTotalEquipmentBonuses(Character $character): array
    {
        $bonuses = [
            'strength' => 0,
            'dexterity' => 0,
            'constitution' => 0,
            'intelligence' => 0,
            'luck' => 0,
            'hp' => 0,
            'mp' => 0,
        ];

        $equipments = CharacterEquipment::where('character_id', $character->id)
            ->where('is_equipped', true)
            ->whereNotNull('equipment_id')
            ->with('equipment')
            ->get();

        foreach ($equipments as $charEquipment) {
            $equipmentStats = $charEquipment->equipment->getStatsForTier($charEquipment->current_tier);
            
            $bonuses['strength'] += $equipmentStats['strength'];
            $bonuses['dexterity'] += $equipmentStats['dexterity'];
            $bonuses['constitution'] += $equipmentStats['constitution'];
            $bonuses['intelligence'] += $equipmentStats['intelligence'];
            $bonuses['luck'] += $equipmentStats['luck'];
            $bonuses['hp'] += $equipmentStats['hp'];
            $bonuses['mp'] += $equipmentStats['mp'];
        }

        return $bonuses;
    }

    /**
     * Calcular HP máximo baseado em constituição e nível
     */
    private function calculateMaxHP(int $constitution, int $level): int
    {
        return (int) round(($constitution * 2) + ($level * 5) + 50);
    }

    /**
     * Calcular Stamina máxima baseado em inteligência e nível
     */
    private function calculateMaxStamina(int $intelligence, int $level): int
    {
        return (int) round(($intelligence * 1.5) + ($level * 3) + 30);
    }

    /**
     * Calcular Poder de Combate (CP)
     */
    private function calculatePower(array $stats, int $level, array $equipmentBonuses): int
    {
        $equipmentPower = array_sum($equipmentBonuses);
        
        return (int) round(
            ($stats['strength'] * 2) +
            ($stats['dexterity'] * 1.5) +
            ($stats['constitution'] * 1.8) +
            ($stats['intelligence'] * 2.2) +
            ($level * 10) +
            $equipmentPower
        );
    }

    /**
     * Obter equipamentos disponíveis para um slot específico
     */
    public function getAvailableEquipmentForSlot(string $slot): array
    {
        return Equipment::where('type', $slot)->get()->toArray();
    }

    /**
     * Obter equipamentos atuais do personagem
     */
    public function getCharacterEquipment(Character $character): array
    {
        // Equipamentos equipados (por slot)
        $equipped = CharacterEquipment::where('character_id', $character->id)
            ->where('is_equipped', true)
            ->with('equipment')
            ->get()
            ->keyBy('slot')
            ->toArray();

        // Todos os equipamentos do personagem (inventário)
        $allEquipment = CharacterEquipment::where('character_id', $character->id)
            ->with('equipment')
            ->get()
            ->groupBy('slot')
            ->toArray();

        return [
            'equipped' => $equipped,
            'inventory' => $allEquipment
        ];
    }
}
