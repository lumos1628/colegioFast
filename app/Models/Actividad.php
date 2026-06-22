<?php

namespace App\Models;

use Database\Factories\ActividadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Actividad extends Model
{
    /** @use HasFactory<ActividadFactory> */
    use HasFactory;

    protected $table = 'actividades';

    protected $fillable = [
        'asignacion_id',
        'titulo',
        'descripcion',
        'fecha',
        'competencia_id',
        'capacidad_id',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(Asignacion::class);
    }

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class);
    }

    public function capacidad(): BelongsTo
    {
        return $this->belongsTo(Capacidad::class);
    }

    public function notas(): HasMany
    {
        return $this->hasMany(Nota::class);
    }
}
