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
        Schema::create('trilhas_guias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trilha_id')->constrained('trilhas')->onDelete('cascade');
            $table->foreignId('guia_id')->constrained('guias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trilhas_guias');
    }
};
