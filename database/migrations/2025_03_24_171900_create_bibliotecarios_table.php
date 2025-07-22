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
        Schema::create('bibliotecarios', function (Blueprint $table) {
            $table->id('id_bibliotecario'); // Clave primaria
            $table->string('nombre');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('funcion')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bibliotecarios');
    }
};
