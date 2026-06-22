<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('
            CREATE VIEW notas_bimestrales AS
            SELECT
                n.alumno_id,
                a.asignacion_id,
                a.competencia_id,
                asig.periodo_academico_id,
                COUNT(*) as total_notas,
                ROUND(AVG(
                    CASE n.calificacion
                        WHEN \'AD\' THEN 4
                        WHEN \'A\' THEN 3
                        WHEN \'B\' THEN 2
                        WHEN \'C\' THEN 1
                    END
                ), 2) as promedio_numerico
            FROM notas n
            JOIN actividades a ON n.actividad_id = a.id
            JOIN asignaciones asig ON a.asignacion_id = asig.id
            GROUP BY n.alumno_id, a.asignacion_id, a.competencia_id, asig.periodo_academico_id
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP VIEW IF EXISTS notas_bimestrales');
    }
};
