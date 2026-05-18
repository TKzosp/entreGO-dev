<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('endereco_faturamento_id')->nullable()->after('telefone');
            $table->foreign('endereco_faturamento_id')
                ->references('id')->on('enderecos')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['endereco_faturamento_id']);
            $table->dropColumn('endereco_faturamento_id');
        });
    }
};
