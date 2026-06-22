<?php

namespace Database\Seeders;

use App\Enums\NotificacionTipo;
use App\Enums\UserRole;
use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificacionSeeder extends Seeder
{
    public function run(): void
    {
        $padres = User::where('role', UserRole::Padre)->get();

        foreach ($padres as $padre) {
            for ($i = 0; $i < rand(2, 4); $i++) {
                Notificacion::create([
                    'user_id' => $padre->id,
                    'tipo' => fake()->randomElement(NotificacionTipo::cases()),
                    'mensaje' => fake()->sentence(),
                    'leido' => fake()->boolean(),
                ]);
            }
        }
    }
}
