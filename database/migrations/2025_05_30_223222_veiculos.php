<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('veiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->enum('tipo', ['moto', 'carro', 'caminhao', 'van']);
            $table->string('placa', 10)->unique();
            $table->string('modelo', 50)->nullable();
            $table->decimal('capacidade', 10, 2)->nullable()->comment('em kg');
            $table->integer('ano')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('veiculos');
    }
};