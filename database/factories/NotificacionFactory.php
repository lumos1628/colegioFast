<?php

namespace Database\Factories;

use App\Enums\NotificacionTipo;
use App\Enums\UserRole;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notificacion>
 */
class NotificacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->withRole(UserRole::Padre),
            'tipo' => $this->faker->randomElement(NotificacionTipo::cases()),
            'mensaje' => $this->faker->sentence(),
            'leido' => $this->faker->boolean(),
        ];
    }

    public function leido(): static
    {
        return $this->state(fn () => ['leido' => true]);
    }

    public function noLeido(): static
    {
        return $this->state(fn () => ['leido' => false]);
    }
}
