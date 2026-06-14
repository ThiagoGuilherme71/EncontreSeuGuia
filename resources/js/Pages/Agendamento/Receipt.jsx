import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import Button from '@/Components/ui/Button';
import { ChevronLeft, Download, Mountain, CheckCircle2 } from 'lucide-react';
import { formatDate, formatDateLong, formatTime, formatCurrency } from '@/lib/utils';

export default function Receipt({ agendamento }) {
    const numero = `TRL-${String(agendamento.id).padStart(6, '0')}`;

    return (
        <AppLayout>
            <Head title={`Recibo ${numero}`} />

            <div className="max-w-lg mx-auto px-4 py-6 md:py-10">
                <div className="flex items-center justify-between mb-6 no-print">
                    <Link
                        href={`/agendamentos/${agendamento.id}`}
                        className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917]"
                    >
                        <ChevronLeft size={15} /> Voltar
                    </Link>
                    <Button onClick={() => window.print()} size="sm" variant="outline">
                        <Download size={15} /> Baixar recibo
                    </Button>
                </div>

                {/* Recibo */}
                <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] overflow-hidden print:shadow-none print:border print:rounded-none">

                    {/* Header */}
                    <div className="bg-[#2D6A4F] text-white p-6 text-center relative overflow-hidden">
                        <div className="absolute inset-0 opacity-10">
                            <svg width="100%" height="100%">
                                <defs>
                                    <pattern id="receiptTopo" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse">
                                        <path d="M0 45 L15 22 L30 38 L45 15 L60 33" stroke="white" strokeWidth="2" fill="none"/>
                                    </pattern>
                                </defs>
                                <rect width="100%" height="100%" fill="url(#receiptTopo)"/>
                            </svg>
                        </div>
                        <div className="relative">
                            <div className="w-12 h-12 mx-auto rounded-xl bg-white/20 border-2 border-white/40 flex items-center justify-center mb-2">
                                <Mountain size={24} />
                            </div>
                            <h1 className="font-display font-bold text-xl">Trilhas</h1>
                            <p className="text-sm opacity-80">Recibo de pagamento</p>
                        </div>
                    </div>

                    {/* Status pago */}
                    <div className="flex items-center justify-center gap-2 py-3 bg-[#D8EFE3] border-b-2 border-dashed border-[#2D6A4F] text-[#2D6A4F]">
                        <CheckCircle2 size={18} />
                        <span className="font-bold text-sm">PAGO (sandbox)</span>
                    </div>

                    <div className="p-6">
                        <div className="flex justify-between text-xs text-[#78716C] mb-5">
                            <span>Recibo nº <strong className="text-[#1C1917]">{numero}</strong></span>
                            <span>Emitido em {formatDate(agendamento.pago_em)}</span>
                        </div>

                        <div className="flex flex-col gap-3 text-sm">
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Trilha</span>
                                <span className="font-semibold text-[#1C1917] text-right">
                                    {agendamento.trilha?.nome}
                                    {agendamento.trilha?.dificuldade && ` (${agendamento.trilha.dificuldade.descricao})`}
                                </span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Local</span>
                                <span className="font-semibold text-[#1C1917]">{agendamento.trilha?.cidade}</span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Data da trilha</span>
                                <span className="font-semibold text-[#1C1917] text-right">{formatDateLong(agendamento.data)}</span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Saída</span>
                                <span className="font-semibold text-[#1C1917]">{formatTime(agendamento.horario)}</span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Guia</span>
                                <span className="font-semibold text-[#1C1917]">{agendamento.guia?.nome}</span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Trilheiro</span>
                                <span className="font-semibold text-[#1C1917]">{agendamento.user?.nome}</span>
                            </div>
                            <div className="flex justify-between gap-4">
                                <span className="text-[#78716C]">Participantes</span>
                                <span className="font-semibold text-[#1C1917]">{agendamento.num_pessoas}</span>
                            </div>
                        </div>

                        <div className="mt-5 pt-4 border-t-2 border-dashed border-[#E3CDA8]">
                            <div className="flex justify-between items-center">
                                <span className="font-display font-bold text-[#1C1917]">Total pago</span>
                                <span className="font-display font-extrabold text-2xl text-[#2D6A4F]">
                                    {formatCurrency(agendamento.total_valor)}
                                </span>
                            </div>
                        </div>

                        <p className="text-[10px] text-[#78716C] text-center mt-6">
                            Documento gerado em ambiente sandbox — sem valor fiscal.<br />
                            Encontre seu Guia · Conectando trilheiros e guias
                        </p>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
