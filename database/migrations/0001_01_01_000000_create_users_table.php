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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('rut_usuario')->unique(); // RUT del usuario, debe ser único
            $table->string('name');
            $table->string('lastname');
            $table->string('lastname2');
            $table->string('email')->unique();
            $table->string('phone');
            $table->date('birthday');
            $table->string('status')->default('activo'); // Ejemplo: 'activo', 'inactivo', 'bloqueado'
            // Nueva columna para el tipo de usuario
            $table->enum('tipo_usuario', ['admin', 'bibliotecario'])->default('bibliotecario'); // Ejemplo: '', 'bibliotecario', etc.            
            $table->string('created_by')->default('formulario de registro'); // Si se crea un usuario desde el admin, se registrará el nombre del admin
            $table->softDeletes(); // Para manejar eliminaciones lógicas
            $table->string('deleted_by')->nullable(); // Para registrar quién eliminó al usuario
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
