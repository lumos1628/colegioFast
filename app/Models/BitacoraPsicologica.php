<?php

namespace App\Models;

use Database\Factories\BitacoraPsicologicaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BitacoraPsicologica extends Model
{
    /** @use HasFactory<BitacoraPsicologicaFactory> */
    use HasFactory;

    protected $table = 'bitacora_psicologica';

    protected $fillable = [
        'alumno_id',
        'psicologo_id',
        'fecha',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function psicologo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'psicologo_id');
    }
}
