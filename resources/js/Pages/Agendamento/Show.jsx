import { Head, Link, router, usePage, useForm } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import Button from '@/Components/ui/Button';
import Modal from '@/Components/ui/Modal';
import Avatar from '@/Components/ui/Avatar';
import { Textarea } from '@/Components/ui/Input';
import { StarDisplay, StarInput } from '@/Components/ui/StarRating';
import useAuth from '@/hooks/useAuth';
import {
    ChevronLeft, CalendarDays, Clock, Users, MapPin, FileText,
    CreditCard, Hourglass, PartyPopper, XCircle, Ban, CheckCircle2, MessageSquareText,
    MessageCircle, Star,
} from 'lucide-react';
import { cn, formatDateLong, formatTime, formatCurrency, getDifficultyColor } from '@/lib/utils';

const statusConfig = {
    pending: {
        icon: Hourglass,
        cor: 'bg-[#FFF8E6] border-[#F2C94C] text-yellow-700',
        titulo: 'Proposta enviada!',
        tituloGuia: 'Proposta recebida',
    },
    accepted: {
        icon: PartyPopper,
        cor: 'bg-[#D8EFE3] border-[#2D6A4F] text-[#2D6A4F]',
        titulo: 'Proposta aceita! 🎉',
        tituloGuia: 'Você aceitou essa proposta',
    },
    rejected: {
        icon: XCircle,
        cor: 'bg-red-50 border-red-400 text-red-700',
        titulo: 'Proposta não aceita',
        tituloGuia: 'Você rejeitou essa proposta',
    },
    cancelled: {
        icon: Ban,
        cor: 'bg-gray-100 border-gray-400 text-gray-600',
        titulo: 'Agendamento cancelado',
        tituloGuia: 'Agendamento cancelado',
    },
    completed: {
        icon: CheckCircle2,
        cor: 'bg-blue-50 border-blue-400 text-blue-700',
        titulo: 'Trilha concluída!',
        tituloGuia: 'Trilha concluída!',
    },
};

function InfoRow({ icon: Icon, label, children }) {
    return (
        <div className="flex items-center gap-3 py-2.5">
            <div className="w-9 h-9 rounded-xl bg-[#F5EDD9] flex items-center justify-center text-[#A27738] shrink-0">
                <Icon size={16} />
            </div>
            <div className="min-w-0">
                <p className="text-xs text-[#78716C]">{label}</p>
                <p className="text-sm font-semibold text-[#1C1917]">{children}</p>
            </div>
        </div>
    );
}

function AvaliacaoForm({ agendamento }) {
    const { data, setData, post, processing, errors } = useForm({
        nota: 0,
        comentario: '',
    });

    function submit(e) {
        e.preventDefault();
        post(`/agendamentos/${agendamento.id}/avaliar`, { preserveScroll: true });
    }

    return (
        <form
            onSubmit={submit}
            className="bg-[#FFF8E6] rounded-2xl border-2 border-[#F2C94C] shadow-[3px_3px_0px_#1C1917] p-5 mt-4"
        >
            <h2 className="font-display font-bold text-[#1C1917] flex items-center gap-2">
                <Star size={17} className="text-[#F2C94C]" /> Como foi a trilha?
            </h2>
            <p className="text-xs text-[#78716C] mt-0.5 mb-3">
                Avalie {agendamento.guia?.nome} — sua opinião ajuda outros trilheiros.
            </p>

            <StarInput value={data.nota} onChange={(n) => setData('nota', n)} size={30} />
            {errors.nota && <p className="text-xs text-red-600 mt-1">{errors.nota}</p>}

            <Textarea
                value={data.comentario}
                onChange={(e) => setData('comentario', e.target.value)}
                error={errors.comentario}
                placeholder="Conta como foi! (opcional)"
                rows={3}
                className="mt-3"
            />

            <Button
                type="submit"
                loading={processing}
                disabled={data.nota === 0}
                className="w-full mt-3"
            >
                Enviar avaliação
            </Button>
        </form>
    );
}

export default function Show({ agendamento, pode_cancelar, pode_chat, pode_avaliar }) {
    const { isGuia } = useAuth();
    const { flash } = usePage().props;
    const [rejectOpen, setRejectOpen] = useState(false);
    const [cancelOpen, setCancelOpen] = useState(false);
    const [motivo, setMotivo] = useState('');
    const [busy, setBusy] = useState(false);

    const cfg = statusConfig[agendamento.status] ?? statusConfig.pending;
    const StatusIcon = cfg.icon;
    const pago = !!agendamento.pago_em;
    const titulo = isGuia ? cfg.tituloGuia : cfg.titulo;

    function patch(url, data = {}) {
        setBusy(true);
        router.patch(url, data, {
            onFinish: () => {
                setBusy(false);
                setRejectOpen(false);
                setCancelOpen(false);
            },
        });
    }

    return (
        <AppLayout>
            <Head title={`Agendamento — ${agendamento.trilha?.nome}`} />

            <div className="max-w-2xl mx-auto px-4 py-6 md:py-10">
                <Link
                    href={isGuia ? '/guia-dash' : '/conta'}
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> {isGuia ? 'Dashboard' : 'Minhas reservas'}
                </Link>

                {flash?.error && (
                    <div className="mb-4 text-sm text-red-700 bg-red-50 border-2 border-red-300 rounded-xl px-4 py-3">
                        {flash.error}
                    </div>
                )}

                {/* Status hero */}
                <div className={cn('rounded-2xl border-2 p-5 flex items-center gap-4 shadow-[3px_3px_0px_#1C1917]', cfg.cor)}>
                    <StatusIcon size={32} className="shrink-0" />
                    <div>
                        <h1 className="font-display font-bold text-lg md:text-xl">{titulo}</h1>
                        {agendamento.status === 'pending' && !isGuia && (
                            <p className="text-sm opacity-80 mt-0.5">
                                {agendamento.guia?.nome} vai responder em breve. Você será notificado!
                            </p>
                        )}
                        {agendamento.status === 'accepted' && !isGuia && !pago && (
                            <p className="text-sm opacity-80 mt-0.5">
                                Agora é só confirmar o pagamento pra garantir sua vaga.
                            </p>
                        )}
                        {agendamento.status === 'accepted' && pago && (
                            <p className="text-sm opacity-80 mt-0.5">
                                Pagamento confirmado. Prepare a mochila! 🎒
                            </p>
                        )}
                        {agendamento.status === 'rejected' && agendamento.motivo_rejeicao && (
                            <p className="text-sm opacity-80 mt-0.5">
                                Motivo: {agendamento.motivo_rejeicao}
                            </p>
                        )}
                    </div>
                </div>

                {/* Ações do guia (proposta pendente) */}
                {isGuia && agendamento.status === 'pending' && (
                    <div className="flex gap-3 mt-4">
                        <Button
                            onClick={() => patch(`/agendamentos/${agendamento.id}/aceitar`)}
                            loading={busy}
                            className="flex-1"
                            size="lg"
                        >
                            Aceitar proposta
                        </Button>
                        <Button
                            variant="danger"
                            onClick={() => setRejectOpen(true)}
                            disabled={busy}
                            size="lg"
                        >
                            Rejeitar
                        </Button>
                    </div>
                )}

                {/* Ações do trilheiro (aceito, não pago) */}
                {!isGuia && agendamento.status === 'accepted' && !pago && (
                    <Link href={`/agendamentos/${agendamento.id}/pagamento`} className="block mt-4">
                        <Button size="lg" className="w-full">
                            <CreditCard size={18} /> Ir para o pagamento — {formatCurrency(agendamento.total_valor)}
                        </Button>
                    </Link>
                )}

                {/* Chat + Recibo */}
                {(pode_chat || pago) && (
                    <div className="flex gap-3 mt-4">
                        {pode_chat && (
                            <Link href={`/chat/${agendamento.id}`} className="flex-1">
                                <Button size="lg" className="w-full" variant={pago ? 'outline' : 'primary'}>
                                    <MessageCircle size={18} /> Chat
                                </Button>
                            </Link>
                        )}
                        {pago && (
                            <Link href={`/agendamentos/${agendamento.id}/recibo`} className="flex-1">
                                <Button variant="outline" size="lg" className="w-full">
                                    <FileText size={18} /> Recibo
                                </Button>
                            </Link>
                        )}
                    </div>
                )}

                {/* Avaliação (trilheiro, trilha concluída) */}
                {pode_avaliar && <AvaliacaoForm agendamento={agendamento} />}

                {/* Avaliação já feita */}
                {agendamento.avaliacao && (
                    <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5 mt-4">
                        <h2 className="font-display font-bold text-[#1C1917] flex items-center gap-2 mb-2">
                            <Star size={16} className="text-[#F2C94C]" />
                            {isGuia ? 'Avaliação recebida' : 'Sua avaliação'}
                        </h2>
                        <StarDisplay value={agendamento.avaliacao.nota} size={18} />
                        {agendamento.avaliacao.comentario && (
                            <p className="text-sm text-[#44403C] mt-2 whitespace-pre-line">
                                "{agendamento.avaliacao.comentario}"
                            </p>
                        )}
                    </div>
                )}

                {/* Detalhes */}
                <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5 mt-6">
                    <div className="flex items-center justify-between mb-3">
                        <h2 className="font-display font-bold text-[#1C1917]">Detalhes</h2>
                        {agendamento.trilha?.dificuldade && (
                            <span className={cn('text-xs font-bold px-2 py-0.5 rounded-md', getDifficultyColor(agendamento.trilha.dificuldade.descricao))}>
                                {agendamento.trilha.dificuldade.descricao}
                            </span>
                        )}
                    </div>

                    <div className="divide-y divide-[#F5EDD9]">
                        <InfoRow icon={MapPin} label="Trilha">
                            <Link href={`/trilhas/${agendamento.trilha?.id}`} className="hover:text-[#2D6A4F] hover:underline">
                                {agendamento.trilha?.nome}
                            </Link>
                            <span className="text-[#78716C] font-normal"> · {agendamento.trilha?.cidade}</span>
                        </InfoRow>
                        <InfoRow icon={CalendarDays} label="Data">
                            {formatDateLong(agendamento.data)}
                        </InfoRow>
                        <InfoRow icon={Clock} label="Horário de saída">
                            {formatTime(agendamento.horario)}
                        </InfoRow>
                        <InfoRow icon={Users} label="Pessoas">
                            {agendamento.num_pessoas}
                        </InfoRow>
                        <InfoRow icon={CreditCard} label="Valor total">
                            {formatCurrency(agendamento.total_valor)}
                            {pago && <span className="text-[#2D6A4F]"> · Pago ✓</span>}
                        </InfoRow>
                    </div>

                    {agendamento.observacoes && (
                        <div className="mt-3 pt-3 border-t border-[#F5EDD9]">
                            <p className="flex items-center gap-1.5 text-xs text-[#78716C] mb-1">
                                <MessageSquareText size={13} /> Observações
                            </p>
                            <p className="text-sm text-[#44403C] whitespace-pre-line">{agendamento.observacoes}</p>
                        </div>
                    )}
                </div>

                {/* A outra parte */}
                <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5 mt-4">
                    <h2 className="font-display font-bold text-[#1C1917] mb-3">
                        {isGuia ? 'Trilheiro' : 'Seu guia'}
                    </h2>
                    <div className="flex items-center gap-3">
                        <Avatar name={isGuia ? agendamento.user?.nome : agendamento.guia?.nome} size="lg" />
                        <div>
                            <p className="font-semibold text-[#1C1917]">
                                {isGuia ? agendamento.user?.nome : agendamento.guia?.nome}
                            </p>
                            {(agendamento.status === 'accepted') && (
                                <p className="text-sm text-[#78716C]">
                                    📞 {isGuia ? agendamento.user?.telefone : agendamento.guia?.telefone}
                                </p>
                            )}
                            {agendamento.status !== 'accepted' && !isGuia && agendamento.guia?.anos_experiencia != null && (
                                <p className="text-sm text-[#78716C]">
                                    {agendamento.guia.anos_experiencia} anos de experiência
                                </p>
                            )}
                        </div>
                    </div>
                    {agendamento.status === 'pending' && (
                        <p className="text-xs text-[#78716C] mt-3">
                            O contato é liberado quando a proposta for aceita.
                        </p>
                    )}
                </div>

                {/* Cancelar */}
                {pode_cancelar && (
                    <div className="mt-6 text-center no-print">
                        <button
                            onClick={() => setCancelOpen(true)}
                            className="text-sm text-red-600 font-semibold hover:underline"
                        >
                            Cancelar agendamento
                        </button>
                        <p className="text-xs text-[#78716C] mt-1">
                            Cancelamento gratuito até 1 dia antes da trilha.
                        </p>
                    </div>
                )}
            </div>

            {/* Modal rejeitar (guia) */}
            <Modal open={rejectOpen} onClose={() => setRejectOpen(false)} title="Rejeitar proposta">
                <p className="text-sm text-[#78716C] mb-3">
                    Quer explicar o motivo? É opcional, mas ajuda o trilheiro.
                </p>
                <Textarea
                    value={motivo}
                    onChange={(e) => setMotivo(e.target.value)}
                    placeholder="Ex.: Já tenho compromisso nessa data..."
                    rows={3}
                />
                <div className="flex gap-3 mt-4">
                    <Button variant="secondary" onClick={() => setRejectOpen(false)} className="flex-1">
                        Voltar
                    </Button>
                    <Button
                        variant="danger"
                        loading={busy}
                        onClick={() => patch(`/agendamentos/${agendamento.id}/rejeitar`, { motivo })}
                        className="flex-1"
                    >
                        Rejeitar
                    </Button>
                </div>
            </Modal>

            {/* Modal cancelar */}
            <Modal open={cancelOpen} onClose={() => setCancelOpen(false)} title="Cancelar agendamento?">
                <p className="text-sm text-[#78716C]">
                    Essa ação não pode ser desfeita. {!isGuia && 'O guia será notificado.'}
                    {isGuia && 'O trilheiro será notificado.'}
                </p>
                <div className="flex gap-3 mt-4">
                    <Button variant="secondary" onClick={() => setCancelOpen(false)} className="flex-1">
                        Voltar
                    </Button>
                    <Button
                        variant="danger"
                        loading={busy}
                        onClick={() => patch(`/agendamentos/${agendamento.id}/cancelar`)}
                        className="flex-1"
                    >
                        Sim, cancelar
                    </Button>
                </div>
            </Modal>
        </AppLayout>
    );
}
