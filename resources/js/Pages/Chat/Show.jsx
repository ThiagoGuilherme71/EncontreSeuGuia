import { Head, Link, router, useForm } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import Avatar from '@/Components/ui/Avatar';
import { ChevronLeft, SendHorizonal, Lock, MapPin } from 'lucide-react';
import { cn, formatTime, formatDate } from '@/lib/utils';

function Bolha({ minha, mensagem, hora }) {
    return (
        <div className={cn('flex', minha ? 'justify-end' : 'justify-start')}>
            <div className={cn(
                'max-w-[80%] md:max-w-[65%] px-3.5 py-2.5 rounded-2xl border-2 border-[#1C1917] text-sm',
                minha
                    ? 'bg-[#2D6A4F] text-white rounded-br-md shadow-[2px_2px_0px_#1C1917]'
                    : 'bg-white text-[#1C1917] rounded-bl-md shadow-[2px_2px_0px_#1C1917]',
            )}>
                <p className="whitespace-pre-line break-words">{mensagem}</p>
                <p className={cn('text-[10px] mt-1 text-right', minha ? 'text-white/70' : 'text-[#78716C]')}>
                    {hora}
                </p>
            </div>
        </div>
    );
}

export default function Show({ agendamento, mensagens = [], eu, pode_enviar }) {
    const bottomRef = useRef(null);
    const countRef = useRef(mensagens.length);

    const outraParte = eu === 'user' ? agendamento.guia : agendamento.user;

    const { data, setData, post, processing, reset } = useForm({ mensagem: '' });

    // polling: recarrega só as mensagens a cada 5s
    useEffect(() => {
        const timer = setInterval(() => {
            router.reload({ only: ['mensagens'], preserveScroll: true });
        }, 5000);
        return () => clearInterval(timer);
    }, []);

    // auto-scroll quando chegam mensagens novas
    useEffect(() => {
        if (mensagens.length !== countRef.current) {
            countRef.current = mensagens.length;
            bottomRef.current?.scrollIntoView({ behavior: 'smooth' });
        }
    }, [mensagens.length]);

    // scroll inicial
    useEffect(() => {
        bottomRef.current?.scrollIntoView();
    }, []);

    function submit(e) {
        e.preventDefault();
        if (!data.mensagem.trim()) return;
        post(`/chat/${agendamento.id}`, {
            preserveScroll: true,
            onSuccess: () => reset(),
        });
    }

    // agrupar por dia
    const grupos = [];
    let diaAtual = null;
    for (const m of mensagens) {
        const dia = m.created_at?.slice(0, 10);
        if (dia !== diaAtual) {
            diaAtual = dia;
            grupos.push({ tipo: 'dia', valor: dia, key: `dia-${dia}` });
        }
        grupos.push({ tipo: 'msg', valor: m, key: m.id });
    }

    return (
        <AppLayout hideBottomNav>
            <Head title={`Chat — ${outraParte?.nome}`} />

            <div className="max-w-2xl mx-auto flex flex-col h-[calc(100dvh-4rem)] md:h-[calc(100dvh-5rem)]">

                {/* Header do chat */}
                <div className="flex items-center gap-3 px-4 py-3 bg-white border-b-2 border-[#1C1917]">
                    <Link
                        href={`/agendamentos/${agendamento.id}`}
                        className="p-1.5 rounded-lg hover:bg-[#F5EDD9] text-[#78716C]"
                    >
                        <ChevronLeft size={18} />
                    </Link>
                    <Avatar name={outraParte?.nome} size="md" />
                    <div className="min-w-0">
                        <p className="font-display font-bold text-sm text-[#1C1917] line-clamp-1">
                            {outraParte?.nome}
                        </p>
                        <p className="flex items-center gap-1 text-xs text-[#78716C]">
                            <MapPin size={10} className="text-[#E07A45]" />
                            {agendamento.trilha?.nome} · {formatDate(agendamento.data)}
                        </p>
                    </div>
                </div>

                {/* Mensagens */}
                <div className="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-2.5 bg-[#FAFAF5]">
                    {mensagens.length === 0 && (
                        <div className="text-center py-12">
                            <p className="text-sm text-[#78716C]">
                                Nenhuma mensagem ainda.<br />
                                {pode_enviar ? 'Quebra o gelo aí! 👋' : ''}
                            </p>
                        </div>
                    )}

                    {grupos.map((g) =>
                        g.tipo === 'dia' ? (
                            <div key={g.key} className="flex items-center gap-3 my-2">
                                <div className="flex-1 h-px bg-[#E3CDA8]" />
                                <span className="text-[10px] font-bold text-[#78716C] uppercase">
                                    {formatDate(g.valor)}
                                </span>
                                <div className="flex-1 h-px bg-[#E3CDA8]" />
                            </div>
                        ) : (
                            <Bolha
                                key={g.key}
                                minha={g.valor.sender_type === eu}
                                mensagem={g.valor.mensagem}
                                hora={formatTime(g.valor.created_at?.slice(11, 16))}
                            />
                        )
                    )}
                    <div ref={bottomRef} />
                </div>

                {/* Input ou aviso de fechado */}
                {pode_enviar ? (
                    <form
                        onSubmit={submit}
                        className="flex items-end gap-2 px-4 py-3 bg-white border-t-2 border-[#1C1917]"
                    >
                        <textarea
                            value={data.mensagem}
                            onChange={(e) => setData('mensagem', e.target.value)}
                            onKeyDown={(e) => {
                                if (e.key === 'Enter' && !e.shiftKey) {
                                    e.preventDefault();
                                    submit(e);
                                }
                            }}
                            placeholder="Escreva sua mensagem..."
                            rows={1}
                            className="flex-1 resize-none rounded-xl border-2 border-[#1C1917] px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/30 max-h-28"
                        />
                        <button
                            type="submit"
                            disabled={processing || !data.mensagem.trim()}
                            className="shrink-0 w-11 h-11 rounded-xl bg-[#2D6A4F] text-white border-2 border-[#1C1917] shadow-[2px_2px_0px_#1C1917] flex items-center justify-center disabled:opacity-40 disabled:shadow-none active:translate-y-px transition-all"
                        >
                            <SendHorizonal size={18} />
                        </button>
                    </form>
                ) : (
                    <div className="flex items-center justify-center gap-2 px-4 py-4 bg-[#F5EDD9] border-t-2 border-[#1C1917] text-sm text-[#78716C]">
                        <Lock size={15} />
                        {agendamento.status === 'completed'
                            ? 'Trilha concluída — o chat ficou só pra consulta.'
                            : agendamento.status === 'cancelled'
                                ? 'Agendamento cancelado — o chat foi encerrado.'
                                : 'O chat fecha 1 dia antes da trilha. Bora se preparar! 🎒'}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
