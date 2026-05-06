<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('waypoints', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rota_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('ordem')->default(0);
            $table->timestamps();

            $table->foreign('rota_id')->references('id')->on('rotas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waypoints');
    }
};