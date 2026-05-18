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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('plano_id')->constrained('planos')->onDelete('restrict');
            $table->foreignId('assinatura_id')->nullable()->constrained('assinaturas')->onDelete('set null');
            $table->decimal('valor', 10, 2);
            $table->enum('status', ['pendente', 'aprovado', 'recusado'])->default('pendente');
            $table->string('metodo', 50)->default('simulado');
            $table->string('referencia_externa', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
