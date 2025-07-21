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
        Schema::create('solicitacoes_prova', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante_id'); // Quem está solicitando
            $table->unsignedBigInteger('analista_id'); // Analista escolhido
            $table->unsignedBigInteger('torra_id'); // Torra a ser analisada
            $table->text('notas')->nullable(); // Notas adicionais
            $table->enum('status', ['Pendente', 'Em Análise', 'Concluída', 'Cancelada'])->default('Pendente');
            $table->timestamp('criado_em')->useCurrent();
            $table->timestamp('atualizado_em')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('solicitante_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('analista_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('torra_id')->references('id')->on('torras')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes_prova');
    }
};
