<?php

namespace Tests\Feature;

use App\Models\Agendamento;
use App\Models\Avaliacao;
use App\Models\ChatMessage;
use App\Models\Dificuldade;
use App\Models\FotoAventura;
use App\Models\Guia;
use App\Models\Trilha;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Auditoria funcional da aplicacao.
 *
 * Roda dentro de uma transacao (DatabaseTransactions) contra o banco de
 * desenvolvimento: tudo o que cada teste cria e desfeito ao final, entao os
 * dados de seed sao preservados. Testes que falham apontam pontos de erro.
 */
class AuditoriaTest extends TestCase
{
    use DatabaseTransactions;

    private function novoUser(array $over = []): User
    {
        $sufixo = uniqid();

        return User::create(array_merge([
            'nome' => 'Trilheiro Teste',
            'email' => "user_{$sufixo}@teste.com",
            'telefone' => '(71) 90000-0000',
            'data_nascimento' => '2000-01-01',
            'cpf' => substr("999{$sufixo}", 0, 14),
            'password' => Hash::make('123456'),
        ], $over));
    }

    private function novoGuia(array $over = []): Guia
    {
        $sufixo = uniqid();

        return Guia::create(array_merge([
            'nome' => 'Guia Teste',
            'email' => "guia_{$sufixo}@teste.com",
            'telefone' => '(75) 90000-0000',
            'data_nascimento' => '1990-01-01',
            'cpf' => substr("888{$sufixo}", 0, 14),
            'anos_experiencia' => 5,
            'password' => Hash::make('123456'),
        ], $over));
    }

    private function novaTrilha(?Guia $criador = null, array $over = []): Trilha
    {
        $dif = Dificuldade::firstOrCreate(['descricao' => 'Moderado']);

        return Trilha::create(array_merge([
            'nome' => 'Trilha ' . uniqid(),
            'descricao' => 'Descricao da trilha de teste com tamanho suficiente.',
            'id_dificuldade' => $dif->id,
            'estado' => 'BA',
            'cidade' => 'Lencois',
            'tempo_estimado_horas' => 3,
            'criado_por_guia' => $criador?->id,
        ], $over));
    }

    private function inscrever(Guia $guia, Trilha $trilha, array $pivot = []): void
    {
        $guia->trilhas()->syncWithoutDetaching([
            $trilha->id => array_merge(['congelada' => false, 'preco_por_pessoa' => 100], $pivot),
        ]);
    }

    private function novoAgendamento(User $user, Guia $guia, Trilha $trilha, array $over = []): Agendamento
    {
        return Agendamento::create(array_merge([
            'id_users' => $user->id,
            'id_guia' => $guia->id,
            'id_trilha' => $trilha->id,
            'status' => 'pending',
            'data' => now()->addDays(5)->toDateString(),
            'horario' => '08:00',
            'num_pessoas' => 2,
            'total_valor' => 200,
        ], $over));
    }

    // ---------------------------------------------------------------------
    // Autenticacao
    // ---------------------------------------------------------------------

    /** Trilheiro com credenciais validas e redirecionado para a landing. */
    public function test_login_trilheiro_ok(): void
    {
        $user = $this->novoUser(['password' => Hash::make('segredo123')]);

        $this->post('/login', ['email' => $user->email, 'password' => 'segredo123'])
            ->assertRedirect(route('landing-page'));

        $this->assertAuthenticatedAs($user, 'web');
    }

    /** Guia com credenciais validas e redirecionado para o dashboard. */
    public function test_login_guia_ok(): void
    {
        $guia = $this->novoGuia(['password' => Hash::make('segredo123')]);

        $this->post('/login', ['email' => $guia->email, 'password' => 'segredo123'])
            ->assertRedirect(route('guia-dash'));

        $this->assertAuthenticatedAs($guia, 'guia');
    }

    /** Credenciais invalidas voltam com erro e sem autenticar. */
    public function test_login_invalido(): void
    {
        $this->novoUser(['password' => Hash::make('segredo123')]);

        $this->from('/login')
            ->post('/login', ['email' => 'inexistente@teste.com', 'password' => 'errado1'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('login');

        $this->assertGuest('web');
        $this->assertGuest('guia');
    }

    /** Cadastro de trilheiro cria registro e ja autentica. */
    public function test_signup_trilheiro(): void
    {
        $sufixo = uniqid();

        $this->post('/signup', [
            'nome' => 'Novo Trilheiro',
            'email' => "novo_{$sufixo}@teste.com",
            'telefone' => '(71) 91111-1111',
            'data_nascimento' => '1995-05-05',
            'cpf' => substr("777{$sufixo}", 0, 14),
            'password' => 'senha123',
            'password_confirmation' => 'senha123',
        ])->assertRedirect(route('landing-page'));

        $this->assertDatabaseHas('users', ['email' => "novo_{$sufixo}@teste.com"]);
    }

    // ---------------------------------------------------------------------
    // Trilhas
    // ---------------------------------------------------------------------

    /** Guia autenticado cria trilha com foto. */
    public function test_guia_cria_trilha(): void
    {
        Storage::fake('public');
        $guia = $this->novoGuia();
        $dif = Dificuldade::firstOrCreate(['descricao' => 'Facil']);

        $this->actingAs($guia, 'guia')->post('/trilhas', [
            'nome' => 'Trilha Nova ' . uniqid(),
            'descricao' => 'Uma descricao valida para a trilha de teste.',
            'id_dificuldade' => $dif->id,
            'estado' => 'BA',
            'cidade' => 'Mucuge',
            'foto' => UploadedFile::fake()->image('trilha.jpg', 800, 600),
        ])->assertRedirect(route('guia-dash'));
    }

    /**
     * PONTO DE ERRO ESPERADO: qualquer guia inscrito consegue editar uma
     * trilha que NAO criou (incluindo trilhas do sistema). O esperado seria
     * que apenas o criador pudesse alterar nome/foto/dificuldade.
     */
    public function test_guia_nao_criador_nao_deveria_editar_trilha_alheia(): void
    {
        $criador = $this->novoGuia();
        $intruso = $this->novoGuia();
        $trilha = $this->novaTrilha($criador);
        $this->inscrever($intruso, $trilha);

        $dif = Dificuldade::firstOrCreate(['descricao' => 'Dificil']);

        $resp = $this->actingAs($intruso, 'guia')->post("/trilhas/{$trilha->id}", [
            'nome' => 'NOME SEQUESTRADO',
            'descricao' => 'Conteudo alterado por quem nao e o dono da trilha.',
            'id_dificuldade' => $dif->id,
            'estado' => 'BA',
            'cidade' => 'Outra Cidade',
        ]);

        // Esperado: bloqueio (403). Atual: edicao e aplicada.
        $this->assertDatabaseMissing('trilhas', [
            'id' => $trilha->id,
            'nome' => 'NOME SEQUESTRADO',
        ]);
    }

    // ---------------------------------------------------------------------
    // Agendamentos
    // ---------------------------------------------------------------------

    /** Fluxo feliz: proposta -> aceite -> pagamento -> conclusao -> avaliacao. */
    public function test_fluxo_completo_agendamento(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha, ['preco_por_pessoa' => 150]);

        $this->actingAs($user, 'web')->post('/agendamentos', [
            'id_trilha' => $trilha->id,
            'id_guia' => $guia->id,
            'data' => now()->addDays(7)->toDateString(),
            'horario' => '09:00',
            'num_pessoas' => 3,
        ])->assertRedirect();

        $ag = Agendamento::where('id_users', $user->id)->latest('id')->first();
        $this->assertNotNull($ag, 'agendamento nao foi criado');
        $this->assertSame('pending', $ag->status);
        $this->assertEquals(450, (float) $ag->total_valor, 'total_valor deveria ser 3 x 150');

        $this->actingAs($guia, 'guia')->patch("/agendamentos/{$ag->id}/aceitar")->assertRedirect();
        $this->assertSame('accepted', $ag->fresh()->status);

        $this->actingAs($user, 'web')->patch("/agendamentos/{$ag->id}/pagar")->assertRedirect();
        $this->assertNotNull($ag->fresh()->pago_em);

        $ag->update(['status' => 'completed']);

        $this->actingAs($user, 'web')->post("/agendamentos/{$ag->id}/avaliar", [
            'nota' => 5,
            'comentario' => 'Excelente!',
        ])->assertRedirect();

        $this->assertDatabaseHas('avaliacoes', ['id_agendamento' => $ag->id, 'nota' => 5]);
    }

    /** Conflito de horario com outra trilha do mesmo guia e bloqueado. */
    public function test_conflito_de_horario(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia, ['tempo_estimado_horas' => 4]);
        $this->inscrever($guia, $trilha);

        $data = now()->addDays(6)->toDateString();
        $this->novoAgendamento($user, $guia, $trilha, ['data' => $data, 'horario' => '08:00', 'status' => 'accepted']);

        $this->actingAs($user, 'web')
            ->from('/agendamentos/criar')
            ->post('/agendamentos', [
                'id_trilha' => $trilha->id,
                'id_guia' => $guia->id,
                'data' => $data,
                'horario' => '10:00', // dentro da janela 08:00-12:00
                'num_pessoas' => 1,
            ])->assertSessionHasErrors('horario');
    }

    /** Data no passado e rejeitada pela validacao after:today. */
    public function test_agendamento_data_passada_rejeitado(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);

        $this->actingAs($user, 'web')
            ->from('/agendamentos/criar')
            ->post('/agendamentos', [
                'id_trilha' => $trilha->id,
                'id_guia' => $guia->id,
                'data' => now()->subDay()->toDateString(),
                'horario' => '08:00',
                'num_pessoas' => 1,
            ])->assertSessionHasErrors('data');
    }

    /** Quem nao participa do agendamento recebe 403 ao tentar ve-lo. */
    public function test_terceiro_nao_ve_agendamento(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $estranho = $this->novoUser();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha);

        $this->actingAs($estranho, 'web')->get("/agendamentos/{$ag->id}")->assertForbidden();
    }

    /** Guia nao consegue aceitar proposta de outro guia. */
    public function test_guia_nao_aceita_proposta_alheia(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $outroGuia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha);

        $this->actingAs($outroGuia, 'guia')->patch("/agendamentos/{$ag->id}/aceitar")->assertNotFound();
        $this->assertSame('pending', $ag->fresh()->status);
    }

    /** Cancelamento bloqueado a menos de 1 dia da trilha. */
    public function test_cancelamento_fora_da_janela(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, [
            'status' => 'accepted',
            'data' => now()->toDateString(), // trilha e hoje
        ]);

        $this->actingAs($user, 'web')
            ->from("/agendamentos/{$ag->id}")
            ->patch("/agendamentos/{$ag->id}/cancelar")
            ->assertSessionHas('error');

        $this->assertSame('accepted', $ag->fresh()->status);
    }

    /** Pagamento so e aceito em agendamento aceito e ainda nao pago. */
    public function test_nao_paga_proposta_pendente(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'pending']);

        $this->actingAs($user, 'web')->patch("/agendamentos/{$ag->id}/pagar")->assertNotFound();
        $this->assertNull($ag->fresh()->pago_em);
    }

    /** Conclusao automatica de trilhas aceitas com data passada no dashboard. */
    public function test_dashboard_conclui_trilhas_passadas(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, [
            'status' => 'accepted',
            'data' => now()->subDays(2)->toDateString(),
        ]);

        $this->actingAs($user, 'web')->get('/conta')->assertOk();

        $this->assertSame('completed', $ag->fresh()->status);
    }

    // ---------------------------------------------------------------------
    // Chat
    // ---------------------------------------------------------------------

    /** Chat so abre apos o aceite (pending => 403). */
    public function test_chat_bloqueado_antes_do_aceite(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'pending']);

        $this->actingAs($user, 'web')->get("/chat/{$ag->id}")->assertForbidden();
    }

    /** Mensagem enviada por participante apos aceite e persistida. */
    public function test_chat_envia_mensagem_apos_aceite(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'accepted']);

        $this->actingAs($user, 'web')->post("/chat/{$ag->id}", ['mensagem' => 'Ola guia'])->assertRedirect();

        $this->assertDatabaseHas('chat_messages', [
            'agendamento_id' => $ag->id,
            'sender_type' => 'user',
            'mensagem' => 'Ola guia',
        ]);
    }

    /** Terceiro nao acessa o chat de um agendamento alheio. */
    public function test_terceiro_nao_acessa_chat(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $estranho = $this->novoUser();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'accepted']);

        $this->actingAs($estranho, 'web')->get("/chat/{$ag->id}")->assertForbidden();
    }

    // ---------------------------------------------------------------------
    // Fotos da aventura
    // ---------------------------------------------------------------------

    /** Limite de fotos por pessoa e respeitado. */
    public function test_limite_de_fotos_por_pessoa(): void
    {
        Storage::fake('public');
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'completed']);

        $max = FotoAventuraController_MAX();
        for ($i = 0; $i < $max; $i++) {
            FotoAventura::create([
                'agendamento_id' => $ag->id,
                'postado_por_type' => 'user',
                'postado_por_id' => $user->id,
                'path' => "storage/aventuras/f{$i}.jpg",
            ]);
        }

        $this->actingAs($user, 'web')
            ->from("/agendamentos/{$ag->id}")
            ->post("/agendamentos/{$ag->id}/fotos", [
                'foto' => UploadedFile::fake()->image('extra.jpg', 800, 600),
            ])->assertSessionHas('error');
    }

    /** Usuario so remove as proprias fotos. */
    public function test_so_remove_propria_foto(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'completed']);

        $foto = FotoAventura::create([
            'agendamento_id' => $ag->id,
            'postado_por_type' => 'guia',
            'postado_por_id' => $guia->id,
            'path' => 'storage/aventuras/dono.jpg',
        ]);

        $this->actingAs($user, 'web')->delete("/fotos-aventura/{$foto->id}")->assertForbidden();
        $this->assertDatabaseHas('fotos_aventura', ['id' => $foto->id]);
    }

    // ---------------------------------------------------------------------
    // Avaliacoes
    // ---------------------------------------------------------------------

    /** Nao e possivel avaliar duas vezes o mesmo agendamento. */
    public function test_avaliacao_dupla_bloqueada(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'completed']);

        Avaliacao::create([
            'id_users' => $user->id,
            'id_guia' => $guia->id,
            'id_agendamento' => $ag->id,
            'nota' => 4,
        ]);

        $this->actingAs($user, 'web')->post("/agendamentos/{$ag->id}/avaliar", [
            'nota' => 1,
        ])->assertStatus(422);

        $this->assertSame(1, Avaliacao::where('id_agendamento', $ag->id)->count());
    }

    /** So avalia agendamento concluido. */
    public function test_avaliacao_so_apos_concluir(): void
    {
        $user = $this->novoUser();
        $guia = $this->novoGuia();
        $trilha = $this->novaTrilha($guia);
        $this->inscrever($guia, $trilha);
        $ag = $this->novoAgendamento($user, $guia, $trilha, ['status' => 'accepted']);

        $this->actingAs($user, 'web')->post("/agendamentos/{$ag->id}/avaliar", [
            'nota' => 5,
        ])->assertNotFound();
    }

    // ---------------------------------------------------------------------
    // Notificacoes
    // ---------------------------------------------------------------------

    /** markRead nao afeta notificacao de outro usuario. */
    public function test_nao_le_notificacao_alheia(): void
    {
        $dono = $this->novoUser();
        $intruso = $this->novoUser();

        $notif = \App\Models\Notificacao::notificar('user', $dono->id, 'nova_mensagem', 'T', 'M');

        $this->actingAs($intruso, 'web')->patch("/notificacoes/{$notif->id}/ler")->assertRedirect();

        $this->assertNull($notif->fresh()->lida_em, 'notificacao alheia nao deveria ser marcada como lida');
    }

    // ---------------------------------------------------------------------
    // Model quebrado
    // ---------------------------------------------------------------------

    /**
     * PONTO DE ERRO ESPERADO: User::isGuia() consulta guias.user_id, coluna
     * inexistente, lancando QueryException em vez de retornar booleano.
     */
    public function test_user_isguia_retorna_booleano(): void
    {
        $user = $this->novoUser();

        $this->assertIsBool($user->isGuia());
    }
}

/** Atalho para a constante de limite de fotos. */
function FotoAventuraController_MAX(): int
{
    return \App\Http\Controllers\FotoAventuraController::MAX_FOTOS_POR_PESSOA;
}
