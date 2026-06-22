<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumno_padre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->foreignId('padre_id')->constrained()->onDelete('cascade');
            $table->string('parentesco')->default('padre');
            $table->timestamps();

            $table->unique(['alumno_id', 'padre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumno_padre');
    }
};
