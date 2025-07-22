<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libros', function (Blueprint $table) {
            $table->id('id_libro'); // Clave primaria
            $table->string('titulo', 255)->index();
            $table->string('isbn', 13)->unique();
            $table->foreignId('id_autor')
                ->constrained('autores', 'id_autor')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('id_editorial')
                ->constrained('editoriales', 'id_editorial')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->year('anio_publicacion')->nullable()->index();
            $table->foreignId('id_categoria')
                ->constrained('categorias', 'id_categoria')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('sinopsis')->nullable();
            $table->string('portada')->nullable();
            $table->integer('dias_maximos_prestamo')->default(15);
            $table->timestamps();
            $table->softDeletes(); // Para soft deletes
            $table->string('created_by')->nullable();
            $table->string('deleted_by')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
