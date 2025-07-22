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
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id('id_prestamo'); // Clave primaria
            $table->foreignId('id_ejemplar');
            $table->foreign('id_ejemplar', 'fk_prestamos_ejemplares')
                ->references('id_ejemplar')
                ->on('ejemplares')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('rut_estudiante');
            $table->foreign('rut_estudiante')->references('rut_estudiante')->on('estudiantes')->onDelete('cascade');
            $table->foreignId('id_bibliotecario');
            $table->foreign('id_bibliotecario', 'fk_prestamos_users')
                ->references('id')
                ->on('users') // apunta a tabla users
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->date('fecha_prestamo');
            $table->date('fecha_devolucion_esperada')->nullable();
            $table->date('fecha_devolucion_real')->nullable();
            $table->enum('estado', ['activo', 'retrasado', 'devuelto_al_dia', 'devuelto_con_retraso'])->default('activo');
            $table->text('observaciones')->nullable();
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
        Schema::dropIfExists('prestamos');
    }
};
