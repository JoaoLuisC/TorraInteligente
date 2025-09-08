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
        Schema::create('dados_sensores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('torrador_id')->constrained('torradores')->onDelete('cascade');
            $table->decimal('temperatura', 5, 2); // Ex: 250.50°C
            $table->integer('tempo')->default(0); // Tempo em segundos
            $table->bigInteger('timestamp_esp'); // Timestamp do ESP8266
            $table->integer('rssi')->nullable(); // Força do sinal WiFi
            $table->integer('uptime')->nullable(); // Tempo ligado em segundos
            $table->integer('free_heap')->nullable(); // Memória livre
            $table->string('version', 10)->nullable(); // Versão do firmware
            $table->timestamps();

            // Índices para otimizar consultas
            $table->index(['torrador_id', 'created_at']);
            $table->index('timestamp_esp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dados_sensores');
    }
};
