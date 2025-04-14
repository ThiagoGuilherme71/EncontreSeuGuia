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
            $table->string('email');
            $table->string('telefone');
            $table->string('cep');
            $table->string('endereco');
            $table->string('link_instagram')->nullable();
            $table->string('link_facebook')->nullable();
            $table->string('doc_frente');
            $table->string('doc_verso');
            $table->string('password');
            $table->string('cpf');
            $table->date('data_nascimento');
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
