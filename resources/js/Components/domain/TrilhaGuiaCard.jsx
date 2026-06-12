import { Link, router } from '@inertiajs/react';
import { useState } from 'react';
import { MapPin, Mountain, Pencil, Snowflake, Sun, Eye } from 'lucide-react';
import Modal from '@/Components/ui/Modal';
import Button from '@/Components/ui/Button';
import { cn, getDifficultyColor } from '@/lib/utils';

export default function TrilhaGuiaCard({ trilha, souCriador = false, className }) {
    const congelada = !!trilha.pivot?.congelada;
    const [confirmOpen, setConfirmOpen] = useState(false);
    const [busy, setBusy] = useState(false);

    function toggleCongelada() {
        setBusy(true);
        const url = congelada
            ? `/trilhas/${trilha.id}/reativar`
            : `/trilhas/${trilha.id}/congelar`;
        router.patch(url, {}, {
            preserveScroll: true,
            onFinish: () => {
                setBusy(false);
                setConfirmOpen(false);
            },
        });
    }

    return (
        <div className={cn(
            'bg-white rounded-2xl border-2 border-[#1C1917] overflow-hidden',
            'shadow-[3px_3px_0px_#1C1917] transition-all duration-200',
            congelada && 'opacity-75',
            className,
        )}>
            <div className="flex gap-3 p-3">
                {/* Thumb */}
                <div className="relative w-20 h-20 rounded-xl bg-[#D8EFE3] border-2 border-[#1C1917] overflow-hidden shrink-0">
                    {trilha.foto ? (
                        <img
                            src={`/${trilha.foto}`}
                            alt={trilha.nome}
                            className={cn('w-full h-full object-cover', congelada && 'grayscale')}
                        />
                    ) : (
                        <div className="w-full h-full flex items-center justify-center text-[#2D6A4F]">
                            <Mountain size={28} strokeWidth={1.5} />
                        </div>
                    )}
                    {congelada && (
                        <div className="absolute inset-0 bg-blue-100/60 flex items-center justify-center">
                            <Snowflake size={20} className="text-blue-500" />
                        </div>
                    )}
                </div>

                {/* Info */}
                <div className="flex-1 min-w-0">
                    <div className="flex items-start justify-between gap-2">
                        <h4 className="font-display font-bold text-sm text-[#1C1917] line-clamp-1">
                            {trilha.nome}
                        </h4>
                        <div className="flex gap-1 shrink-0">
                            {souCriador && (
                                <span className="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-[#F5EDD9] text-[#A27738]">
                                    Criada por você
                                </span>
                            )}
                            {congelada && (
                                <span className="text-[10px] font-bold px-1.5 py-0.5 rounded-md bg-blue-50 text-blue-600">
                                    Congelada
                                </span>
                            )}
                        </div>
                    </div>

                    <p className="flex items-center gap-1 text-xs text-[#78716C] mt-0.5">
                        <MapPin size={11} className="text-[#E07A45]" /> {trilha.cidade}
                        {trilha.dificuldade && (
                            <span className={cn('ml-1.5 px-1.5 py-0.5 rounded font-bold text-[10px]', getDifficultyColor(trilha.dificuldade.descricao))}>
                                {trilha.dificuldade.descricao}
                            </span>
                        )}
                    </p>

                    {/* Ações */}
                    <div className="flex items-center gap-2 mt-2">
                        <Link
                            href={`/trilhas/${trilha.id}/editar`}
                            className="flex items-center gap-1 text-xs font-bold text-[#2D6A4F] hover:underline"
                        >
                            <Pencil size={12} /> {souCriador ? 'Editar' : 'Solicitar edição'}
                        </Link>
                        <span className="text-[#E3CDA8]">·</span>
                        <button
                            onClick={() => setConfirmOpen(true)}
                            disabled={busy}
                            className={cn(
                                'flex items-center gap-1 text-xs font-bold hover:underline',
                                congelada ? 'text-[#E07A45]' : 'text-blue-500',
                            )}
                        >
                            {congelada ? <><Sun size={12} /> Reativar</> : <><Snowflake size={12} /> Congelar</>}
                        </button>
                        <span className="text-[#E3CDA8]">·</span>
                        <Link
                            href={`/trilhas/${trilha.id}`}
                            className="flex items-center gap-1 text-xs font-bold text-[#78716C] hover:underline"
                        >
                            <Eye size={12} /> Ver
                        </Link>
                    </div>
                </div>
            </div>

            {/* Confirmação congelar/reativar */}
            <Modal
                open={confirmOpen}
                onClose={() => setConfirmOpen(false)}
                title={congelada ? 'Reativar inscrição?' : 'Congelar inscrição?'}
            >
                <p className="text-sm text-[#78716C]">
                    {congelada
                        ? 'Você voltará a aparecer como guia disponível nessa trilha e poderá receber propostas.'
                        : 'Você deixará de aparecer como guia disponível nessa trilha. Propostas já aceitas não são afetadas.'}
                </p>
                <div className="flex gap-3 mt-4">
                    <Button variant="secondary" onClick={() => setConfirmOpen(false)} className="flex-1">
                        Voltar
                    </Button>
                    <Button
                        variant={congelada ? 'primary' : 'danger'}
                        loading={busy}
                        onClick={toggleCongelada}
                        className="flex-1"
                    >
                        {congelada ? 'Reativar' : 'Congelar'}
                    </Button>
                </div>
            </Modal>
        </div>
    );
}
