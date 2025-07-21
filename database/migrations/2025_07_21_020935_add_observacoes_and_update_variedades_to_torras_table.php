<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('torras', function (Blueprint $table) {
            // Adicionar campo observações
            $table->text('observacoes')->nullable()->after('finalidade');

            // Atualizar campo atualizado_em
            $table->timestamp('atualizado_em')->nullable()->after('criado_em');
        });

        // Para PostgreSQL, precisamos primeiro remover a constraint do enum existente
        DB::statement("ALTER TABLE torras DROP CONSTRAINT IF EXISTS torras_variedade_check");

        // Adicionar nova constraint com mais variedades
        DB::statement("ALTER TABLE torras ADD CONSTRAINT torras_variedade_check CHECK (variedade::text = ANY (ARRAY['Arábico'::character varying, 'Bourbon'::character varying, 'Catuaí'::character varying, 'Mundo Novo'::character varying]::text[]))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torras', function (Blueprint $table) {
            $table->dropColumn(['observacoes', 'atualizado_em']);
        });

        // Reverter o enum de variedades
        DB::statement("ALTER TABLE torras DROP CONSTRAINT IF EXISTS torras_variedade_check");
        DB::statement("ALTER TABLE torras ADD CONSTRAINT torras_variedade_check CHECK (variedade::text = ANY (ARRAY['Arábico'::character varying, 'Bourbon'::character varying]::text[]))");
    }
};
