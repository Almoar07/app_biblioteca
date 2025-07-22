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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id('id_categoria'); // Clave primaria
            $table->string('nombre_categoria')->unique();
            $table->string('descripcion_categoria')->nullable();
            $table->string('created_by')->nullable(); // Para registrar quién creó la categoría
            $table->softDeletes(); // Soporte para borrado suave
            $table->string('deleted_by')->nullable(); // Para registrar quién eliminó la categoría
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
