import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import TrilhaGuiaCard from '@/Components/domain/TrilhaGuiaCard';
import PropostaCard from '@/Components/domain/PropostaCard';
import EmptyState from '@/Components/ui/EmptyState';
import Button from '@/Components/ui/Button';
import useAuth from '@/hooks/useAuth';
import { Tabs, TabList, Tab, TabPanel } from '@/Components/ui/Tabs';
import {
    Mountain, MapPinned, Inbox, History, Plus, Hourglass,
} from 'lucide-react';

export default function Dashboard({ trilhas_criadas = [], trilhas_cadastradas = [], propostas = [], historico = [] }) {
    const { auth } = useAuth();
    const { flash } = usePage().props;

    const pendentes = propostas.filter((p) => p.status === 'pending').length;
    const primeiroNome = auth?.guia?.nome?.split(' ')[0];

    return (
        <AppLayout>
            <Head title="Dashboard do Guia" />

            <div className="max-w-3xl mx-auto px-4 py-6 md:py-10">

                {/* Header */}
                <div className="flex items-center justify-between gap-3 mb-6 flex-wrap">
                    <div>
                        <h1 className="font-display font-extrabold text-2xl md:text-3xl text-[#1C1917]">
                            E aí, {primeiroNome}! 🏔️
                        </h1>
                        <p className="text-sm text-[#78716C] mt-0.5">
                            {pendentes > 0
                                ? `Você tem ${pendentes} proposta${pendentes > 1 ? 's' : ''} aguardando resposta.`
                                : 'Tudo em dia por aqui.'}
                        </p>
                    </div>
                    <Link href="/trilhas/criar">
                        <Button size="sm">
                            <Plus size={15} /> Nova trilha
                        </Button>
                    </Link>
                </div>

                {flash?.success && (
                    <div className="mb-4 text-sm text-[#2D6A4F] bg-[#D8EFE3] border-2 border-[#2D6A4F] rounded-xl px-4 py-3">
                        {flash.success}
                    </div>
                )}

                <Tabs defaultValue="propostas">
                    <TabList variant="pills" className="overflow-x-auto scrollbar-hide">
                        <Tab value="propostas" variant="pills">
                            <span className="flex items-center gap-1.5 whitespace-nowrap">
                                <Inbox size={14} /> Propostas
                                {pendentes > 0 && (
                                    <span className="bg-[#E07A45] text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] flex items-center justify-center px-1">
                                        {pendentes}
                                    </span>
                                )}
                            </span>
                        </Tab>
                        <Tab value="criadas" variant="pills">
                            <span className="flex items-center gap-1.5 whitespace-nowrap">
                                <Mountain size={14} /> Minhas trilhas ({trilhas_criadas.length})
                            </span>
                        </Tab>
                        <Tab value="cadastradas" variant="pills">
                            <span className="flex items-center gap-1.5 whitespace-nowrap">
                                <MapPinned size={14} /> Me cadastrei ({trilhas_cadastradas.length})
                            </span>
                        </Tab>
                        <Tab value="historico" variant="pills">
                            <span className="flex items-center gap-1.5 whitespace-nowrap">
                                <History size={14} /> Histórico ({historico.length})
                            </span>
                        </Tab>
                    </TabList>

                    {/* Propostas recebidas */}
                    <TabPanel value="propostas" className="mt-4">
                        {propostas.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {propostas.map((p) => (
                                    <PropostaCard key={p.id} agendamento={p} papel="guia" />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={Hourglass}
                                title="Nenhuma proposta no momento"
                                description="Quando um trilheiro quiser agendar com você, a proposta aparece aqui e você recebe uma notificação."
                            />
                        )}
                    </TabPanel>

                    {/* Trilhas que criei */}
                    <TabPanel value="criadas" className="mt-4">
                        {trilhas_criadas.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {trilhas_criadas.map((t) => (
                                    <TrilhaGuiaCard key={t.id} trilha={t} souCriador />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={Mountain}
                                title="Você ainda não criou trilhas"
                                description="Cadastre uma trilha que você conhece bem e comece a receber propostas."
                                action={
                                    <Link href="/trilhas/criar">
                                        <Button size="sm">
                                            <Plus size={15} /> Criar trilha
                                        </Button>
                                    </Link>
                                }
                            />
                        )}
                    </TabPanel>

                    {/* Trilhas que me cadastrei */}
                    <TabPanel value="cadastradas" className="mt-4">
                        {trilhas_cadastradas.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {trilhas_cadastradas.map((t) => (
                                    <TrilhaGuiaCard key={t.id} trilha={t} />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={MapPinned}
                                title="Nenhuma trilha de outros guias"
                                description="Você pode se cadastrar em trilhas criadas por outros guias para ampliar sua atuação."
                            />
                        )}
                    </TabPanel>

                    {/* Histórico */}
                    <TabPanel value="historico" className="mt-4">
                        {historico.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {historico.map((a) => (
                                    <PropostaCard key={a.id} agendamento={a} papel="guia" />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={History}
                                title="Histórico vazio"
                                description="Trilhas concluídas, propostas rejeitadas e cancelamentos ficam registrados aqui."
                            />
                        )}
                    </TabPanel>
                </Tabs>
            </div>
        </AppLayout>
    );
}
