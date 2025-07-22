<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->string('rut_estudiante')->primary();
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion');

            $table->unsignedBigInteger('comuna_estudiante');
            $table->foreign('comuna_estudiante')->references('id_comuna')->on('comunas');

            $table->string('curso', 40)->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'egresado', 'bloqueado']);
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->softDeletes(); // Agrega la columna deleted_at para soft deletes
            $table->string('created_by')->nullable(); // Columna para registrar quién creó el registro
            $table->string('deleted_by')->nullable(); // Columna para registrar quién eliminó el registro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
