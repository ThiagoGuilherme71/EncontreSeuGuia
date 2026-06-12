import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';
import AppLayout from '@/Layouts/AppLayout';
import Button from '@/Components/ui/Button';
import { ChevronLeft, ShieldAlert, Lock, Mountain } from 'lucide-react';
import { formatDate, formatTime, formatCurrency } from '@/lib/utils';

export default function Payment({ agendamento }) {
    const [busy, setBusy] = useState(false);

    function confirmar() {
        setBusy(true);
        router.patch(`/agendamentos/${agendamento.id}/pagar`, {}, {
            onFinish: () => setBusy(false),
        });
    }

    return (
        <AppLayout>
            <Head title="Pagamento" />

            <div className="max-w-lg mx-auto px-4 py-6 md:py-10">
                <Link
                    href={`/agendamentos/${agendamento.id}`}
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> Voltar
                </Link>

                <h1 className="font-display font-extrabold text-2xl md:text-3xl text-[#1C1917] mb-6">
                    Pagamento
                </h1>

                {/* Banner sandbox */}
                <div className="flex items-start gap-3 bg-[#FFF8E6] border-2 border-[#F2C94C] rounded-2xl p-4 mb-6">
                    <ShieldAlert size={20} className="text-[#F2C94C] shrink-0 mt-0.5" />
                    <div>
                        <p className="font-bold text-sm text-yellow-800">Estamos em sandbox 🏖️</p>
                        <p className="text-sm text-yellow-700 mt-0.5">
                            O pagamento é simulado. Nenhum valor real será cobrado.
                        </p>
                    </div>
                </div>

                {/* Resumo */}
                <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] overflow-hidden">
                    {/* Trilha */}
                    <div className="flex items-center gap-3 p-4 bg-[#F5EDD9] border-b-2 border-[#1C1917]">
                        <div className="w-14 h-14 rounded-xl bg-[#D8EFE3] border-2 border-[#1C1917] overflow-hidden shrink-0 flex items-center justify-center text-[#2D6A4F]">
                            {agendamento.trilha?.foto ? (
                                <img src={`/${agendamento.trilha.foto}`} alt="" className="w-full h-full object-cover" />
                            ) : (
                                <Mountain size={24} strokeWidth={1.5} />
                            )}
                        </div>
                        <div>
                            <p className="font-display font-bold text-[#1C1917]">{agendamento.trilha?.nome}</p>
                            <p className="text-xs text-[#78716C]">{agendamento.trilha?.cidade}</p>
                        </div>
                    </div>

                    <div className="p-4 flex flex-col gap-2.5 text-sm">
                        <div className="flex justify-between">
                            <span className="text-[#78716C]">Guia</span>
                            <span className="font-semibold text-[#1C1917]">{agendamento.guia?.nome}</span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-[#78716C]">Data</span>
                            <span className="font-semibold text-[#1C1917]">
                                {formatDate(agendamento.data)} às {formatTime(agendamento.horario)}
                            </span>
                        </div>
                        <div className="flex justify-between">
                            <span className="text-[#78716C]">Pessoas</span>
                            <span className="font-semibold text-[#1C1917]">{agendamento.num_pessoas}</span>
                        </div>

                        <div className="flex justify-between items-center pt-3 mt-1 border-t-2 border-dashed border-[#E3CDA8]">
                            <span className="font-display font-bold text-lg text-[#1C1917]">Total</span>
                            <span className="font-display font-extrabold text-2xl text-[#2D6A4F]">
                                {formatCurrency(agendamento.total_valor)}
                            </span>
                        </div>
                    </div>
                </div>

                <Button
                    onClick={confirmar}
                    loading={busy}
                    size="lg"
                    className="w-full mt-6"
                >
                    <Lock size={16} /> Confirmar pagamento
                </Button>

                <p className="text-xs text-[#78716C] text-center mt-3">
                    Ao confirmar, sua vaga fica garantida com o guia.
                </p>
            </div>
        </AppLayout>
    );
}
