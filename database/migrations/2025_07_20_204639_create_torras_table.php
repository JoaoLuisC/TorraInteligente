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
        Schema::create('torras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->string('nome', 100);
            $table->enum('variedade', ['Arábico', 'Bourbon']);
            $table->float('densidade');
            $table->enum('fermentacao', ['Natural', 'Fermentado', 'CD']);
            $table->enum('finalidade', ['Espresso', 'Filtro', 'Amostra']);
            $table->boolean('avaliada')->default(false);
            $table->unsignedBigInteger('avaliador_id')->nullable();
            $table->timestamp('avaliada_em')->nullable();
            $table->timestamp('criado_em')->useCurrent();

            // Foreign keys
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('avaliador_id')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('torras');
    }
};
