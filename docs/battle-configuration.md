# Battle Configuration

Este documento explica como configurar o sistema de batalha e eventos.

## Arquivo de Configuração

As configurações estão em `config/battle.php` e podem ser sobrescritas por variáveis de ambiente.

## Variáveis de Ambiente

### Dano

```env
BATTLE_ATTACK_MULTIPLIER=0.3      # Multiplicador do poder de ataque (30%)
BATTLE_DEFENSE_REDUCTION=0.5      # Redução de dano por defesa (50%)
BATTLE_DAMAGE_MIN=0.85           # Dano mínimo (85%)
BATTLE_DAMAGE_MAX=1.15           # Dano máximo (115%)
BATTLE_MIN_DAMAGE=1              # Dano mínimo garantido
```

### Crítico

```env
BATTLE_CRIT_MULTIPLIER=1.3       # Multiplicador de crítico (1.3x)
BATTLE_CRIT_LUCK_MULTIPLIER=0.01 # Chance de crítico por sorte (1%)
BATTLE_CRIT_MAX_CHANCE=0.5       # Chance máxima de crítico (50%)
BATTLE_MONSTER_CRIT_CHANCE=0.05  # Chance de crítico do monstro (5%)
```

### Chance de Acerto

```env
BATTLE_HIT_BASE_CHANCE=0.8       # Chance base de acerto (80%)
BATTLE_HIT_SPEED_MULTIPLIER=0.05 # Multiplicador de velocidade (5%)
BATTLE_HIT_MIN_CHANCE=0.1        # Chance mínima de acerto (10%)
BATTLE_HIT_MAX_CHANCE=0.95       # Chance máxima de acerto (95%)
```

### Eventos

```env
EVENT_EXP_MULTIPLIER=1.0         # Multiplicador de EXP (1.0x = normal)
EVENT_GOLD_MULTIPLIER=1.0        # Multiplicador de Ouro (1.0x = normal)
EVENT_DAMAGE_MULTIPLIER=1.0      # Multiplicador de Dano (1.0x = normal)
EVENT_DROP_RATE_MULTIPLIER=1.0   # Multiplicador de Drops (1.0x = normal)
```

## Exemplos de Eventos

### Evento 2x EXP

```env
EVENT_EXP_MULTIPLIER=2.0
```

### Evento 2x Ouro

```env
EVENT_GOLD_MULTIPLIER=2.0
```

### Evento de Dano Aumentado

```env
EVENT_DAMAGE_MULTIPLIER=1.5
```

### Evento Completo (2x tudo)

```env
EVENT_EXP_MULTIPLIER=2.0
EVENT_GOLD_MULTIPLIER=2.0
EVENT_DAMAGE_MULTIPLIER=2.0
EVENT_DROP_RATE_MULTIPLIER=2.0
```

## Como Usar

1. **Para ajustar balanceamento:** Modifique as variáveis de dano/crítico
2. **Para criar eventos:** Modifique as variáveis de evento
3. **Para aplicar mudanças:** Reinicie o servidor ou use cache:clear

## Sistema de Eventos

O `EventService` permite:

-   Verificar eventos ativos
-   Aplicar multiplicadores automaticamente
-   Gerenciar eventos dinamicamente (futuro)

## Exemplo de Uso no Código

```php
// No BattleService
$damage = $this->eventService->applyMultiplier($damage, 'damage');

// No BattleController
$goldReward = $this->eventService->applyMultiplier($monster->gold_reward, 'gold');
$expReward = $this->eventService->applyMultiplier($monster->exp_reward, 'exp');
```
