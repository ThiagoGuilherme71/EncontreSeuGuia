<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trilhas_guias', function (Blueprint $table) {
            $table->decimal('preco_por_pessoa', 8, 2)->nullable()->after('congelada');
        });
    }

    public function down(): void
    {
        Schema::table('trilhas_guias', function (Blueprint $table) {
            $table->dropColumn('preco_por_pessoa');
        });
    }
};
