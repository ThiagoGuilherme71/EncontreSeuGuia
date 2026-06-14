<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notificacoes MODIFY tipo ENUM('proposta_aceita', 'proposta_rejeitada', 'nova_mensagem', 'proposta_recebida', 'agendamento_cancelado', 'avaliacao_recebida')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notificacoes MODIFY tipo ENUM('proposta_aceita', 'proposta_rejeitada', 'nova_mensagem', 'proposta_recebida', 'agendamento_cancelado')");
    }
};
