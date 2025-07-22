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
        Schema::create('comunas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_comuna')->autoIncrement()->primary(); // Control explícito
            $table->string('nombre_comuna')->unique();
            $table->string('provincia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comunas');
    }
};
