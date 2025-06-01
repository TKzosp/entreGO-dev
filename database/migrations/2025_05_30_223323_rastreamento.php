<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('rastreamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rota_id')->constrained('rotas')->onDelete('cascade');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('data_hora')->useCurrent();
            $table->timestamps();

            $table->index('rota_id', 'idx_rastreamento_rota');
        });
    }

    public function down(): void {
        Schema::dropIfExists('rastreamento');
    }
};