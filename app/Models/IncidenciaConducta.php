<?php

namespace App\Models;

use App\Enums\IncidenciaTipo;
use Database\Factories\IncidenciaConductaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidenciaConducta extends Model
{
    /** @use HasFactory<IncidenciaConductaFactory> */
    use HasFactory;

    protected $table = 'incidencias_conducta';

    protected $fillable = [
        'alumno_id',
        'docente_id',
        'tipo',
        'descripcion',
        'fecha',
    ];

    protected $casts = [
        'tipo' => IncidenciaTipo::class,
        'fecha' => 'date',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(Docente::class);
    }
}
