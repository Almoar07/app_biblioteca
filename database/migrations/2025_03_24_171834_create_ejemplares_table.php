<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ejemplares', function (Blueprint $table) {
            $table->id('id_ejemplar'); // Clave primaria
            $table->foreignId('id_libro')
                ->constrained('libros', 'id_libro')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('codigo_barras', 100)->unique();
            $table->string('ubicacion_estante', 50)->index();
            $table->enum('status', ['disponible', 'prestado', 'mantenimiento'])->default('disponible');
            //$table->date('fecha_prestamo')->nullable();
            //$table->date('fecha_devolucion_esperada')->nullable();
            // Días máximos de préstamo, por defecto 15 días
            // Puedes ajustar este valor según tus necesidades
            // Si necesitas un valor diferente, puedes cambiarlo aquí
            // o hacerlo configurable en el futuro.            

            $table->date('fecha_ingreso')->nullable();
            $table->string('created_by')->nullable(); // Usuario que creó el registro
            $table->string('deleted_by')->nullable(); // Usuario que eliminó el registro (
            $table->string('created_batch')->nullable(); // Batch de creación
            $table->timestamps();
            $table->softDeletes(); // Para soft deletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ejemplares');
    }
};
