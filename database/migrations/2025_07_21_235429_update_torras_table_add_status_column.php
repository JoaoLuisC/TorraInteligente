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
        Schema::table('torras', function (Blueprint $table) {
            // Remover a coluna avaliada antiga
            $table->dropColumn('avaliada');

            // Adicionar a nova coluna status
            $table->enum('status', ['nao_avaliada', 'aguardando_avaliacao', 'avaliada'])
                  ->default('nao_avaliada')
                  ->after('finalidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torras', function (Blueprint $table) {
            // Remover a coluna status
            $table->dropColumn('status');

            // Restaurar a coluna avaliada
            $table->boolean('avaliada')->default(false)->after('finalidade');
        });
    }
};
