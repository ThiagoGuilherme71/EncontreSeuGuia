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
        Schema::create('guias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('email')->unique();
            $table->string('telefone');
            $table->string('cep')->nullable();
            $table->string('endereco')->nullable();
            $table->string('linkInstagram')->nullable();
            $table->string('linkFacebook')->nullable();
            $table->string('doc_frente')->nullable();
            $table->string('doc_verso')->nullable();
            $table->string('password');
            $table->date('data_nascimento');
            $table->string('cpf')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guias');
    }
};
