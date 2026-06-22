<?php

namespace App\Models;

use Database\Factories\CapacidadFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Capacidad extends Model
{
    /** @use HasFactory<CapacidadFactory> */
    use HasFactory;

    protected $table = 'capacidades';

    protected $fillable = [
        'competencia_id',
        'nombre',
    ];

    public function competencia(): BelongsTo
    {
        return $this->belongsTo(Competencia::class);
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class);
    }
}
