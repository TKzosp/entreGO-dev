<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('rastreamento') && !Schema::hasTable('rastreamentos')) {
            Schema::rename('rastreamento', 'rastreamentos');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('rastreamentos') && !Schema::hasTable('rastreamento')) {
            Schema::rename('rastreamentos', 'rastreamento');
        }
    }
};
