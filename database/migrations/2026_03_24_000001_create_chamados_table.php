<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('nome', 100);
            $table->string('email', 100);
            $table->string('telefone', 20)->nullable();
            $table->string('assunto', 150);
            $table->string('categoria', 50); // assistencia_tecnica | comercial
            $table->string('prioridade', 20)->default('media'); // baixa | media | alta
            $table->text('mensagem');
            $table->string('status', 30)->default('aberto'); // aberto | em_andamento | fechado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};