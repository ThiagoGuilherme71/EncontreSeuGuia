import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '@/Layouts/AppLayout';
import TrilhaForm from '@/Components/domain/TrilhaForm';
import { ChevronLeft, Sparkles } from 'lucide-react';

export default function Edit({ trilha, dificuldades = [], sou_criador = false, errors = {} }) {
    const { data, setData, post, processing } = useForm({
        nome: trilha.nome ?? '',
        descricao: trilha.descricao ?? '',
        cidade: trilha.cidade ?? '',
        id_dificuldade: trilha.id_dificuldade ?? '',
        foto: null,
    });

    function submit(e) {
        e.preventDefault();
        post(`/trilhas/${trilha.id}`, { forceFormData: true });
    }

    return (
        <AppLayout>
            <Head title={`Editar ${trilha.nome}`} />

            <div className="max-w-xl mx-auto px-4 py-6 md:py-10">
                <Link
                    href="/guia-dash"
                    className="inline-flex items-center gap-1 text-sm font-semibold text-[#78716C] hover:text-[#1C1917] mb-4"
                >
                    <ChevronLeft size={15} /> Dashboard
                </Link>

                <h1 className="font-display font-extrabold text-2xl md:text-3xl text-[#1C1917] mb-1">
                    {sou_criador ? 'Editar trilha' : 'Solicitar edição'}
                </h1>
                <p className="text-sm text-[#78716C] mb-6">
                    {sou_criador
                        ? 'Atualize as informações da sua trilha.'
                        : `Essa trilha foi criada por outro guia. Sua solicitação de edição passa pelo fluxo de aprovação.`}
                </p>

                {/* Aviso sandbox */}
                <div className="flex items-start gap-2.5 bg-[#D8EFE3] border-2 border-[#2D6A4F] rounded-xl p-3.5 mb-6 text-sm text-[#2D6A4F]">
                    <Sparkles size={16} className="shrink-0 mt-0.5" />
                    <p>
                        Estamos em sandbox: {sou_criador ? 'a edição é' : 'sua solicitação é'}{' '}
                        <strong>aprovada automaticamente</strong> ao salvar.
                    </p>
                </div>

                <TrilhaForm
                    data={data}
                    setData={setData}
                    errors={errors}
                    onSubmit={submit}
                    processing={processing}
                    submitLabel={sou_criador ? 'Salvar alterações' : 'Enviar solicitação'}
                    fotoAtual={trilha.foto}
                    dificuldades={dificuldades}
                />
            </div>
        </AppLayout>
    );
}
