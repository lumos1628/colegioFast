<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->foreignId('asignacion_id')->constrained('asignaciones')->onDelete('cascade');
            $table->date('fecha_matricula');
            $table->string('estado')->default('activo');
            $table->timestamps();

            $table->unique(['alumno_id', 'asignacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
