<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->timestamp('pago_em')->nullable()->after('total_valor');
        });

        DB::statement("ALTER TABLE notificacoes MODIFY tipo ENUM('proposta_aceita', 'proposta_rejeitada', 'nova_mensagem', 'proposta_recebida', 'agendamento_cancelado')");
    }

    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('pago_em');
        });

        DB::statement("ALTER TABLE notificacoes MODIFY tipo ENUM('proposta_aceita', 'proposta_rejeitada', 'nova_mensagem', 'proposta_recebida')");
    }
};
