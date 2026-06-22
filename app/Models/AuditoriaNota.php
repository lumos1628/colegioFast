<?php

namespace App\Models;

use Database\Factories\AuditoriaNotaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditoriaNota extends Model
{
    /** @use HasFactory<AuditoriaNotaFactory> */
    use HasFactory;

    protected $fillable = [
        'nota_id',
        'calificacion_anterior',
        'calificacion_nueva',
        'fecha_modificacion',
    ];

    protected $casts = [
        'fecha_modificacion' => 'datetime',
    ];

    public $timestamps = false;

    public function nota(): BelongsTo
    {
        return $this->belongsTo(Nota::class);
    }
}
