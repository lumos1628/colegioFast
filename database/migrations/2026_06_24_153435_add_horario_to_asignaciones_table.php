<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->integer('dia_semana')->nullable()->after('periodo_academico_id');
            $table->time('hora_inicio')->nullable()->after('dia_semana');
            $table->time('hora_fin')->nullable()->after('hora_inicio');
            $table->index(['docente_id', 'dia_semana', 'hora_inicio'], 'idx_docente_horario');
        });
    }

    public function down(): void
    {
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->dropIndex('idx_docente_horario');
            $table->dropColumn(['dia_semana', 'hora_inicio', 'hora_fin']);
        });
    }
};
