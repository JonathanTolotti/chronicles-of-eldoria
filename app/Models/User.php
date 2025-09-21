<?php

namespace App\Models;

use App\Notifications\ResetPasswordMedieval;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordMedieval($token));
    }
}
