<?php

namespace App\Models;

use Database\Factories\CursoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    /** @use HasFactory<CursoFactory> */
    use HasFactory;

    protected $fillable = [
        'nombre',
        'area_curricular',
        'grado',
        'seccion',
    ];

    public function asignaciones(): HasMany
    {
        return $this->hasMany(Asignacion::class);
    }
}
