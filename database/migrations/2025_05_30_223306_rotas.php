<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('motorista_id')->constrained('usuarios');
            $table->foreignId('veiculo_id')->constrained('veiculos');
            $table->decimal('distancia', 10, 2)->nullable()->comment('em km');
            $table->integer('tempo_estimado')->nullable()->comment('em minutos');
            $table->text('polyline')->nullable()->comment('para traçar a rota no mapa');
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_fim')->nullable();
            $table->enum('status', ['planejada', 'iniciada', 'concluida', 'cancelada'])->default('planejada');
            $table->timestamps();

            $table->index('motorista_id', 'idx_rotas_motorista');
        });
    }

    public function down(): void {
        Schema::dropIfExists('rotas');
    }
};