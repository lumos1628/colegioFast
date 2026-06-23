<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function hasRole(UserRole $role): bool
    {
        return $this->role === $role;
    }

    public function redirectUrl(): string
    {
        return $this->role instanceof UserRole
            ? route($this->role->redirectRoute(), absolute: false)
            : route('dashboard', absolute: false);
    }

    public function docente(): HasOne
    {
        return $this->hasOne(Docente::class);
    }

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }

    public function padre(): HasOne
    {
        return $this->hasOne(Padre::class);
    }
}
