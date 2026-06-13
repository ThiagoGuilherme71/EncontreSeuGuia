import { Head, Link } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import PropostaCard from '@/Components/domain/PropostaCard';
import EmptyState from '@/Components/ui/EmptyState';
import Avatar from '@/Components/ui/Avatar';
import Button from '@/Components/ui/Button';
import { Tabs, TabList, Tab, TabPanel } from '@/Components/ui/Tabs';
import { CalendarDays, Compass, Hourglass, History, Pencil } from 'lucide-react';

export default function Dashboard({ perfil, agendamentos = [] }) {
    const ativos = agendamentos.filter((a) => ['pending', 'accepted'].includes(a.status));
    const passados = agendamentos.filter((a) => ['rejected', 'cancelled', 'completed'].includes(a.status));

    return (
        <AppLayout>
            <Head title="Minhas reservas" />

            <div className="max-w-2xl mx-auto px-4 py-6 md:py-10">

                {/* Perfil header */}
                <div className="flex items-center gap-4 mb-8">
                    <Avatar src={perfil.foto ? `/${perfil.foto}` : null} name={perfil.nome} size="xl" />
                    <div className="flex-1 min-w-0">
                        <h1 className="font-display font-extrabold text-xl md:text-2xl text-[#1C1917]">
                            {perfil.nome}
                        </h1>
                        <p className="text-sm text-[#78716C]">{perfil.email}</p>
                        {perfil.telefone && (
                            <p className="text-sm text-[#78716C]">{perfil.telefone}</p>
                        )}
                    </div>
                    <Link href="/perfil/editar">
                        <Button variant="outline" size="sm">
                            <Pencil size={14} /> Editar
                        </Button>
                    </Link>
                </div>

                <Tabs defaultValue="ativos">
                    <TabList variant="pills">
                        <Tab value="ativos" variant="pills">
                            <span className="flex items-center gap-1.5">
                                <Hourglass size={14} /> Ativas ({ativos.length})
                            </span>
                        </Tab>
                        <Tab value="historico" variant="pills">
                            <span className="flex items-center gap-1.5">
                                <History size={14} /> Histórico ({passados.length})
                            </span>
                        </Tab>
                    </TabList>

                    <TabPanel value="ativos" className="mt-4">
                        {ativos.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {ativos.map((a) => (
                                    <PropostaCard key={a.id} agendamento={a} papel="user" />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={CalendarDays}
                                title="Nenhuma reserva ativa"
                                description="Que tal explorar as trilhas disponíveis e agendar sua próxima aventura?"
                                action={
                                    <Link href="/">
                                        <Button size="sm">
                                            <Compass size={15} /> Explorar trilhas
                                        </Button>
                                    </Link>
                                }
                            />
                        )}
                    </TabPanel>

                    <TabPanel value="historico" className="mt-4">
                        {passados.length > 0 ? (
                            <div className="flex flex-col gap-3">
                                {passados.map((a) => (
                                    <PropostaCard key={a.id} agendamento={a} papel="user" />
                                ))}
                            </div>
                        ) : (
                            <EmptyState
                                icon={History}
                                title="Nada por aqui ainda"
                                description="Suas trilhas concluídas e propostas antigas aparecem aqui."
                            />
                        )}
                    </TabPanel>
                </Tabs>
            </div>
        </AppLayout>
    );
}
