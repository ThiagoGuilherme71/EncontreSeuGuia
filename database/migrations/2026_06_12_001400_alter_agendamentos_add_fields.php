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
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled', 'completed'])
                ->default('pending')
                ->after('id');
            $table->integer('num_pessoas')->default(1)->after('data');
            $table->text('observacoes')->nullable()->after('num_pessoas');
            $table->text('motivo_rejeicao')->nullable()->after('observacoes');
            $table->decimal('total_valor', 10, 2)->nullable()->after('motivo_rejeicao');
        });
    }

    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn(['status', 'num_pessoas', 'observacoes', 'motivo_rejeicao', 'total_valor']);
        });
    }
};
