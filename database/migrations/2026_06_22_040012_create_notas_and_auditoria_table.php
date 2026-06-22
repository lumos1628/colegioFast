<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auditoria_notas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nota_id');
            $table->string('calificacion_anterior');
            $table->string('calificacion_nueva');
            $table->timestamp('fecha_modificacion')->useCurrent();
        });

        Schema::create('notas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->string('calificacion');
            $table->text('observacion')->nullable();
            $table->boolean('visible_para_alumno')->default(true);
            $table->timestamps();

            $table->unique(['actividad_id', 'alumno_id']);
        });

        Schema::table('auditoria_notas', function (Blueprint $table) {
            $table->foreign('nota_id')->references('id')->on('notas')->onDelete('cascade');
        });

        DB::unprepared('
            CREATE OR REPLACE FUNCTION fn_auditoria_notas()
            RETURNS TRIGGER AS $$
            BEGIN
                IF OLD.calificacion IS DISTINCT FROM NEW.calificacion THEN
                    INSERT INTO auditoria_notas (nota_id, calificacion_anterior, calificacion_nueva, fecha_modificacion)
                    VALUES (OLD.id, OLD.calificacion, NEW.calificacion, NOW());
                END IF;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER trg_auditoria_notas
            AFTER UPDATE OF calificacion ON notas
            FOR EACH ROW
            EXECUTE FUNCTION fn_auditoria_notas();
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_auditoria_notas ON notas');
        DB::unprepared('DROP FUNCTION IF EXISTS fn_auditoria_notas');
        Schema::dropIfExists('auditoria_notas');
        Schema::dropIfExists('notas');
    }
};
