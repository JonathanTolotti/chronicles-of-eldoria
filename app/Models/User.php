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
     * Check if user is currently VIP.
     */
    public function isVip(): bool
    {
        return $this->is_vip && 
               ($this->vip_expires_at === null || $this->vip_expires_at->isFuture());
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordMedieval($token));
    }
}
