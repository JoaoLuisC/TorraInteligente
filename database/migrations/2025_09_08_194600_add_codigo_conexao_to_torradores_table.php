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
        Schema::table('torradores', function (Blueprint $table) {
            $table->string('codigo_conexao', 32)->unique()->nullable()->after('nome');
            $table->enum('status', ['ativo', 'inativo', 'manutencao'])->default('ativo')->after('codigo_conexao');
            $table->text('descricao')->nullable()->after('status');
            $table->timestamp('ultima_conexao')->nullable()->after('descricao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('torradores', function (Blueprint $table) {
            $table->dropColumn(['codigo_conexao', 'status', 'descricao', 'ultima_conexao']);
        });
    }
};
