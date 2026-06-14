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
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->string('notificavel_type'); // 'user' ou 'guia'
            $table->unsignedBigInteger('notificavel_id');
            $table->enum('tipo', ['proposta_aceita', 'proposta_rejeitada', 'nova_mensagem', 'proposta_recebida']);
            $table->string('titulo');
            $table->text('mensagem');
            $table->json('data')->nullable();
            $table->timestamp('lida_em')->nullable();
            $table->timestamps();

            $table->index(['notificavel_type', 'notificavel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notificacoes');
    }
};
