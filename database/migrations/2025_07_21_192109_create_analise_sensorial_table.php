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
        Schema::create('analise_sensorial', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitacao_id');
            $table->decimal('aroma_po', 3, 1);
            $table->decimal('fragrancia_cafe', 3, 1);
            $table->decimal('sabor', 3, 1);
            $table->decimal('acidez', 3, 1);
            $table->decimal('corpo', 3, 1);
            $table->decimal('retro_gosto', 3, 1);
            $table->decimal('equilibrio', 3, 1);
            $table->decimal('docura', 3, 1);
            $table->decimal('uniformidade', 3, 1);
            $table->decimal('defeitos', 3, 1);
            $table->decimal('balanceamento', 3, 1);
            $table->decimal('nota_final', 5, 2)->nullable();
            $table->timestamps();

            $table->foreign('solicitacao_id')->references('id')->on('solicitacoes_prova');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analise_sensorial');
    }
};
