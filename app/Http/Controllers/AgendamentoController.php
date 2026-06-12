<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Notificacao;
use App\Models\Trilha;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgendamentoController extends Controller
{
    // Sandbox: preço fixo por pessoa enquanto trilhas não têm precificação própria
    public const PRECO_POR_PESSOA = 150.00;

    public function create(Request $request)
    {
        $trilha = Trilha::with('dificuldade')->findOrFail($request->query('trilha'));
        $guia = $trilha->guias()->findOrFail($request->query('guia'));

        return Inertia::render('Agendamento/Create', [
            'trilha' => $trilha,
            'guia' => $guia->only('id', 'nome', 'anos_experiencia'),
            'preco_por_pessoa' => self::PRECO_POR_PESSOA,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_trilha' => 'required|exists:trilhas,id',
            'id_guia' => 'required|exists:guias,id',
            'data' => 'required|date|after:today',
            'horario' => 'required',
            'num_pessoas' => 'required|integer|min:1|max:20',
            'observacoes' => 'nullable|string|max:1000',
        ], [
            'data.after' => 'A data precisa ser a partir de amanhã.',
        ]);

        $user = $request->user('web');
        $trilha = Trilha::findOrFail($request->id_trilha);

        $agendamento = Agendamento::create([
            'id_users' => $user->id,
            'id_trilha' => $trilha->id,
            'id_guia' => $request->id_guia,
            'data' => $request->data,
            'horario' => $request->horario,
            'num_pessoas' => $request->num_pessoas,
            'observacoes' => $request->observacoes,
            'status' => 'pending',
            'total_valor' => $request->num_pessoas * self::PRECO_POR_PESSOA,
        ]);

        Notificacao::notificar(
            'guia',
            $agendamento->id_guia,
            'proposta_recebida',
            'Nova proposta de trilha! 🥾',
            "{$user->nome} quer agendar {$trilha->nome} para " . $agendamento->data->format('d/m/Y') . " com {$agendamento->num_pessoas} pessoa(s).",
            ['agendamento_id' => $agendamento->id]
        );

        return redirect()->route('agendamentos.show', $agendamento->id)
            ->with('success', 'Proposta enviada! Aguarde a resposta do guia.');
    }

    public function show(Request $request, $id)
    {
        $agendamento = Agendamento::with(['trilha.dificuldade', 'guia:id,nome,telefone,anos_experiencia', 'user:id,nome,telefone,email'])
            ->findOrFail($id);

        $this->authorizeParticipant($request, $agendamento);

        return Inertia::render('Agendamento/Show', [
            'agendamento' => $agendamento,
            'pode_cancelar' => $this->podeCancelar($agendamento),
        ]);
    }

    public function accept(Request $request, $id)
    {
        $guia = $request->user('guia');
        $agendamento = Agendamento::with('trilha:id,nome')
            ->where('id_guia', $guia->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $agendamento->update(['status' => 'accepted']);

        Notificacao::notificar(
            'user',
            $agendamento->id_users,
            'proposta_aceita',
            'Proposta aceita! 🎉',
            "{$guia->nome} aceitou sua proposta para {$agendamento->trilha->nome} em " . $agendamento->data->format('d/m/Y') . ". Finalize o pagamento!",
            ['agendamento_id' => $agendamento->id]
        );

        return back()->with('success', 'Proposta aceita!');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['motivo' => 'nullable|string|max:500']);

        $guia = $request->user('guia');
        $agendamento = Agendamento::with('trilha:id,nome')
            ->where('id_guia', $guia->id)
            ->where('status', 'pending')
            ->findOrFail($id);

        $agendamento->update([
            'status' => 'rejected',
            'motivo_rejeicao' => $request->motivo,
        ]);

        $mensagem = "{$guia->nome} não pôde aceitar sua proposta para {$agendamento->trilha->nome}.";
        if ($request->motivo) {
            $mensagem .= " Motivo: {$request->motivo}";
        }

        Notificacao::notificar(
            'user',
            $agendamento->id_users,
            'proposta_rejeitada',
            'Proposta não aceita',
            $mensagem,
            ['agendamento_id' => $agendamento->id]
        );

        return back()->with('success', 'Proposta rejeitada.');
    }

    public function cancel(Request $request, $id)
    {
        $agendamento = Agendamento::with('trilha:id,nome')->findOrFail($id);
        $this->authorizeParticipant($request, $agendamento);

        if (!in_array($agendamento->status, ['pending', 'accepted'])) {
            return back()->with('error', 'Esse agendamento não pode mais ser cancelado.');
        }

        if (!$this->podeCancelar($agendamento)) {
            return back()->with('error', 'Cancelamentos só são permitidos até 1 dia antes da trilha.');
        }

        $agendamento->update(['status' => 'cancelled']);

        $canceladoPorGuia = (bool) $request->user('guia');
        $dataFormatada = $agendamento->data->format('d/m/Y');

        if ($canceladoPorGuia) {
            Notificacao::notificar(
                'user',
                $agendamento->id_users,
                'agendamento_cancelado',
                'Agendamento cancelado',
                "O guia cancelou o agendamento de {$agendamento->trilha->nome} em {$dataFormatada}.",
                ['agendamento_id' => $agendamento->id]
            );
        } else {
            Notificacao::notificar(
                'guia',
                $agendamento->id_guia,
                'agendamento_cancelado',
                'Agendamento cancelado',
                "{$request->user('web')->nome} cancelou o agendamento de {$agendamento->trilha->nome} em {$dataFormatada}.",
                ['agendamento_id' => $agendamento->id]
            );
        }

        return back()->with('success', 'Agendamento cancelado.');
    }

    public function payment(Request $request, $id)
    {
        $agendamento = Agendamento::with(['trilha:id,nome,cidade,foto', 'guia:id,nome'])
            ->where('id_users', $request->user('web')->id)
            ->where('status', 'accepted')
            ->whereNull('pago_em')
            ->findOrFail($id);

        return Inertia::render('Agendamento/Payment', [
            'agendamento' => $agendamento,
        ]);
    }

    public function pay(Request $request, $id)
    {
        $agendamento = Agendamento::where('id_users', $request->user('web')->id)
            ->where('status', 'accepted')
            ->whereNull('pago_em')
            ->findOrFail($id);

        $agendamento->update(['pago_em' => now()]);

        return redirect()->route('agendamentos.show', $agendamento->id)
            ->with('success', 'Pagamento confirmado! (sandbox)');
    }

    public function receipt(Request $request, $id)
    {
        $agendamento = Agendamento::with(['trilha.dificuldade', 'guia:id,nome,telefone', 'user:id,nome,email,cpf'])
            ->whereNotNull('pago_em')
            ->findOrFail($id);

        $this->authorizeParticipant($request, $agendamento);

        return Inertia::render('Agendamento/Receipt', [
            'agendamento' => $agendamento,
        ]);
    }

    private function authorizeParticipant(Request $request, Agendamento $agendamento): void
    {
        $user = $request->user('web');
        $guia = $request->user('guia');

        $isOwner = ($user && $agendamento->id_users === $user->id)
            || ($guia && $agendamento->id_guia === $guia->id);

        abort_unless($isOwner, 403);
    }

    private function podeCancelar(Agendamento $agendamento): bool
    {
        if (!in_array($agendamento->status, ['pending', 'accepted'])) {
            return false;
        }

        return now()->lt($agendamento->data->copy()->subDay()->endOfDay());
    }
}
