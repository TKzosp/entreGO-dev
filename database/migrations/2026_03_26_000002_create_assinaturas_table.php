<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('plano_id')->constrained('planos')->onDelete('cascade');
            $table->enum('status', ['ativa', 'cancelada', 'expirada'])->default('ativa');
            $table->timestamp('data_inicio')->useCurrent();
            $table->timestamp('data_fim')->nullable();
            $table->boolean('renovacao_automatica')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};