<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\AsignacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Asignacion extends Model
{
    /** @use HasFactory<AsignacionFactory> */
    use HasFactory;

    protected $table = 'asignaciones';

    protected $fillable = [
        'docente_id',
        'curso_id',
        'periodo_academico_id',
        'dia_semana',
        'hora_inicio',
        'hora_fin',
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function periodoAcademico(): BelongsTo
    {
        return $this->belongsTo(PeriodoAcademico::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function proximasFechasClase(int $cantidad = 3): Collection
    {
        if (! $this->dia_semana || ! $this->periodoAcademico) {
            return collect();
        }

        $hoy = Carbon::today();
        $fechaInicio = $this->periodoAcademico->fecha_inicio->greaterThan($hoy)
            ? $this->periodoAcademico->fecha_inicio
            : $hoy;
        $fechaFin = $this->periodoAcademico->fecha_fin;

        $fechas = collect();
        $fecha = $fechaInicio->copy();

        while ($fecha->lessThanOrEqualTo($fechaFin) && $fechas->count() < $cantidad) {
            if ($fecha->dayOfWeekIso === $this->dia_semana) {
                $fechas->push($fecha->copy());
            }
            $fecha->addDay();
        }

        return $fechas;
    }
}
