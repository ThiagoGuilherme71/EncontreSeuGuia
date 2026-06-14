<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trilhas', function (Blueprint $table) {
            $table->decimal('distancia_km', 6, 1)->nullable()->after('cidade');
            $table->decimal('tempo_estimado_horas', 5, 1)->nullable()->after('distancia_km');
            $table->decimal('ponto_encontro_lat', 10, 7)->nullable()->after('tempo_estimado_horas');
            $table->decimal('ponto_encontro_lng', 11, 7)->nullable()->after('ponto_encontro_lat');
            $table->string('ponto_encontro_descricao')->nullable()->after('ponto_encontro_lng');
            $table->json('o_que_levar')->nullable()->after('ponto_encontro_descricao');
        });
    }

    public function down(): void
    {
        Schema::table('trilhas', function (Blueprint $table) {
            $table->dropColumn([
                'distancia_km', 'tempo_estimado_horas',
                'ponto_encontro_lat', 'ponto_encontro_lng', 'ponto_encontro_descricao',
                'o_que_levar',
            ]);
        });
    }
};
