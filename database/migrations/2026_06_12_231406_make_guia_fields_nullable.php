<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guias', function (Blueprint $table) {
            $table->string('doc_frente')->nullable()->change();
            $table->string('doc_verso')->nullable()->change();
            $table->string('cep')->nullable()->change();
            $table->string('endereco')->nullable()->change();
            $table->integer('anos_experiencia')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('guias', function (Blueprint $table) {
            $table->string('doc_frente')->nullable(false)->change();
            $table->string('doc_verso')->nullable(false)->change();
            $table->string('cep')->nullable(false)->change();
            $table->string('endereco')->nullable(false)->change();
            $table->integer('anos_experiencia')->nullable(false)->change();
        });
    }
};
