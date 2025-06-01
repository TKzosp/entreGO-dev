<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('usuarios');
            $table->foreignId('endereco_coleta_id')->constrained('enderecos');
            $table->foreignId('endereco_entrega_id')->constrained('enderecos');
            $table->string('descricao', 255)->nullable();
            $table->decimal('peso', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('valor', 10, 2)->nullable();
            $table->dateTime('data_coleta');
            $table->dateTime('data_entrega_estimada')->nullable();
            $table->enum('status', ['pendente', 'aceito', 'coletado', 'transito', 'entregue', 'cancelado'])->default('pendente');
            $table->timestamp('data_criacao')->useCurrent();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->index('cliente_id', 'idx_pedidos_cliente');
            $table->index('status', 'idx_pedidos_status');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pedidos');
    }
};