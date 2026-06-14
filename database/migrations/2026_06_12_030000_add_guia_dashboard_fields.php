<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trilhas', function (Blueprint $table) {
            // null = trilha do sistema (seed); preenchido = criada por um guia
            $table->foreignId('criado_por_guia')->nullable()->after('foto')
                ->constrained('guias')->nullOnDelete();
        });

        Schema::table('trilhas_guias', function (Blueprint $table) {
            // inscrição congelada: guia não aparece como disponível na trilha
            $table->boolean('congelada')->default(false)->after('guia_id');
        });
    }

    public function down(): void
    {
        Schema::table('trilhas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('criado_por_guia');
        });

        Schema::table('trilhas_guias', function (Blueprint $table) {
            $table->dropColumn('congelada');
        });
    }
};
