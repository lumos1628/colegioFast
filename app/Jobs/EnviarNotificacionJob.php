<?php

namespace App\Jobs;

use App\Models\Alumno;
use App\Models\Asistencia;
use App\Models\IncidenciaConducta;
use App\Models\Nota;
use App\Services\NotificacionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarNotificacionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $tipo,
        public int $alumnoId,
        public ?int $asistenciaId = null,
        public ?int $notaId = null,
        public ?int $incidenciaId = null
    ) {}

    public function handle(NotificacionService $service): void
    {
        $alumno = Alumno::find($this->alumnoId);

        if (! $alumno) {
            return;
        }

        match ($this->tipo) {
            'inasistencia' => $this->procesarInasistencia($service, $alumno),
            'nota_critica' => $this->procesarNotaCritica($service, $alumno),
            'incidencia' => $this->procesarIncidencia($service, $alumno),
            default => null,
        };
    }

    private function procesarInasistencia(NotificacionService $service, Alumno $alumno): void
    {
        if (! $this->asistenciaId) {
            return;
        }

        $asistencia = Asistencia::find($this->asistenciaId);

        if ($asistencia) {
            $service->notificarInasistencia($alumno, $asistencia);
        }
    }

    private function procesarNotaCritica(NotificacionService $service, Alumno $alumno): void
    {
        if (! $this->notaId) {
            return;
        }

        $nota = Nota::find($this->notaId);

        if ($nota) {
            $service->notificarNotaCritica($alumno, $nota);
        }
    }

    private function procesarIncidencia(NotificacionService $service, Alumno $alumno): void
    {
        if (! $this->incidenciaId) {
            return;
        }

        $incidencia = IncidenciaConducta::find($this->incidenciaId);

        if ($incidencia) {
            $service->notificarIncidencia($alumno, $incidencia);
        }
    }
}
