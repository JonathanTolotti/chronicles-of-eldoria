<?php

namespace App\Models;

use App\Notifications\ResetPasswordMedieval;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'is_vip',
        'vip_expires_at',
        'active_character_id',
        'coin',
        'is_staff',
        'last_seen_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_vip' => 'boolean',
            'vip_expires_at' => 'datetime',
            'active_character_id' => 'integer',
            'is_staff' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    /**
     * Get the characters for the user.
     */
    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    /**
     * Get the active character for the user.
     */
    public function activeCharacter()
    {
        return $this->belongsTo(Character::class, 'active_character_id');
    }

    /**
     * Check if user is currently VIP.
     */
    public function isVip(): bool
    {
        return $this->is_vip && 
               ($this->vip_expires_at === null || $this->vip_expires_at->isFuture());
    }

    /**
     * Check if user is staff member.
     */
    public function isStaff(): bool
    {
        return $this->is_staff;
    }

    /**
     * Roles do usuário
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withPivot(['assigned_at', 'assigned_by'])
            ->withTimestamps();
    }

    /**
     * Permissões do usuário (através dos roles)
     */
    public function permissions()
    {
        return $this->roles()
            ->join('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->join('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->select('permissions.*')
            ->distinct();
    }

    /**
     * Verifica se o usuário tem um role específico
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Verifica se o usuário tem uma permissão específica
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('permissions.name', $permission)->exists();
    }

    /**
     * Verifica se o usuário tem qualquer um dos roles especificados
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    /**
     * Verifica se o usuário tem qualquer uma das permissões especificadas
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()->whereIn('permissions.name', $permissions)->exists();
    }

    /**
     * Atribui um role ao usuário
     */
    public function assignRole(Role $role, ?User $assignedBy = null): void
    {
        $this->roles()->syncWithoutDetaching([
            $role->id => [
                'assigned_at' => now(),
                'assigned_by' => $assignedBy?->id,
            ]
        ]);
    }

    /**
     * Remove um role do usuário
     */
    public function removeRole(Role $role): void
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Verifica se o usuário é moderador ou superior
     */
    public function isModerator(): bool
    {
        return $this->hasAnyRole(['admin', 'moderator']);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordMedieval($token));
    }
}
