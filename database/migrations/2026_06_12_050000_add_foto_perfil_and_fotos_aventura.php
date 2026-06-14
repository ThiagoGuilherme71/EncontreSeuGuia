<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('cpf');
        });

        Schema::table('guias', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('cpf');
        });

        Schema::create('fotos_aventura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agendamento_id')->constrained('agendamentos')->cascadeOnDelete();
            $table->enum('postado_por_type', ['user', 'guia']);
            $table->unsignedBigInteger('postado_por_id');
            $table->string('path');
            $table->string('thumb_path')->nullable();
            $table->string('legenda')->nullable();
            $table->timestamps();

            $table->index(['agendamento_id', 'postado_por_type', 'postado_por_id'], 'fotos_aventura_autor_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotos_aventura');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto');
        });

        Schema::table('guias', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
