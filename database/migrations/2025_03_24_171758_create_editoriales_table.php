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
        Schema::create('editoriales', function (Blueprint $table) {
            $table->id('id_editorial'); // Clave primaria
            $table->string('nombre_editorial')->unique();
            $table->softDeletes(); // Campo para eliminar lógicamente
            $table->string('deleted_by')->nullable(); // Usuario que eliminó lógicamente;
            $table->string('created_by')->nullable(); // Usuario que creó el registro
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('editoriales');
    }
};
