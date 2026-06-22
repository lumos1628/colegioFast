<?php

namespace App\Models;

use Database\Factories\CompetenciaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Competencia extends Model
{
    /** @use HasFactory<CompetenciaFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'area_curricular',
    ];

    public function capacidades(): HasMany
    {
        return $this->hasMany(Capacidad::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
}
