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
        Schema::table('solicitacoes_prova', function (Blueprint $table) {
            $table->unsignedBigInteger('analista_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solicitacoes_prova', function (Blueprint $table) {
            $table->unsignedBigInteger('analista_id')->nullable(false)->change();
        });
    }
};
