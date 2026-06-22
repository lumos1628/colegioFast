<?php

namespace App\Models;

use App\Enums\NotificacionTipo;
use Database\Factories\NotificacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacion extends Model
{
    /** @use HasFactory<NotificacionFactory> */
    use HasFactory;

    protected $table = 'notificaciones';

    protected $fillable = [
        'user_id',
        'tipo',
        'mensaje',
        'leido',
    ];

    protected $casts = [
        'tipo' => NotificacionTipo::class,
        'leido' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
