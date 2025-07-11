<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('email', 100)->unique();
            $table->string('senha', 255);
            $table->enum('tipo', ['cliente', 'motorista', 'administrador']);
            $table->string('cpf_cnpj', 20)->unique()->nullable();
            $table->string('telefone', 20)->nullable();
            $table->timestamp('data_cadastro')->useCurrent();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('usuarios');
    }
};