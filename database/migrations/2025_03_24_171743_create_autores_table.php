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
        Schema::create('autores', function (Blueprint $table) {
            $table->id('id_autor'); // Clave primaria
            $table->softDeletes(); // Soporte para borrado suave
            $table->string('nombre');
            $table->string('apellido_paterno')->nullable();
            $table->string('apellido_materno')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('nacionalidad', 100)->nullable();
            $table->string('deleted_by')->nullable(); // Para registrar quién eliminó al autor
            $table->string('created_by')->nullable(); // Para registrar quién creó al autor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autores');
    }
};
