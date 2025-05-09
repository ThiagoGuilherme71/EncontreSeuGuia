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
        Schema::create('idiomas_guias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guia_id')->constrained('guias')->onDelete('cascade');
            $table->foreignId('idioma_id')->constrained('idiomas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('idiomas_guias');
    }
};
