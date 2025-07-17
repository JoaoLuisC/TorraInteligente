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
        Schema::table('torradors', function (Blueprint $table) {
            // Verificar se a coluna user_id existe e renomear para usuario_id
            if (Schema::hasColumn('torradors', 'user_id')) {
                $table->renameColumn('user_id', 'usuario_id');
            }

            // Verificar se não existe a coluna usuario_id e criá-la
            if (!Schema::hasColumn('torradors', 'usuario_id')) {
                $table->unsignedBigInteger('usuario_id')->after('codigo_conexao');
            }

            // Adicionar coluna criado_em se não existir
            if (!Schema::hasColumn('torradors', 'criado_em')) {
                $table->timestamp('criado_em')->useCurrent()->after('usuario_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torradors', function (Blueprint $table) {
            if (Schema::hasColumn('torradors', 'usuario_id')) {
                $table->renameColumn('usuario_id', 'user_id');
            }
            if (Schema::hasColumn('torradors', 'criado_em')) {
                $table->dropColumn('criado_em');
            }
        });
    }
};
