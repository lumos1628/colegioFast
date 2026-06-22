<?php

namespace App\Models;

use App\Enums\Calificacion;
use Database\Factories\NotaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Nota extends Model
{
    /** @use HasFactory<NotaFactory> */
    use HasFactory;

    protected $fillable = [
        'actividad_id',
        'alumno_id',
        'calificacion',
        'observacion',
        'visible_para_alumno',
    ];

    protected $casts = [
        'calificacion' => Calificacion::class,
        'visible_para_alumno' => 'boolean',
    ];

    public function actividad(): BelongsTo
    {
        return $this->belongsTo(Actividad::class);
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function auditoriaNotas(): HasMany
    {
        return $this->hasMany(AuditoriaNota::class);
    }
}
