import { router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import Modal from '@/Components/ui/Modal';
import Button from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import Avatar from '@/Components/ui/Avatar';
import { Camera, Trash2, ImagePlus, X } from 'lucide-react';
import { cn } from '@/lib/utils';

function UploadModal({ open, onClose, agendamentoId }) {
    const [preview, setPreview] = useState(null);
    const { data, setData, post, processing, errors, reset } = useForm({
        foto: null,
        legenda: '',
    });

    function handleFile(e) {
        const file = e.target.files?.[0];
        if (!file) return;
        setData('foto', file);
        setPreview(URL.createObjectURL(file));
    }

    function close() {
        reset();
        setPreview(null);
        onClose();
    }

    function submit(e) {
        e.preventDefault();
        post(`/agendamentos/${agendamentoId}/fotos`, {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: close,
        });
    }

    return (
        <Modal open={open} onClose={close} title="Postar foto da aventura 📸">
            <form onSubmit={submit} className="flex flex-col gap-4">
                {preview ? (
                    <div className="relative rounded-2xl border-2 border-[#1C1917] overflow-hidden">
                        <img src={preview} alt="Preview" className="w-full max-h-64 object-cover" />
                        <button
                            type="button"
                            onClick={() => { setData('foto', null); setPreview(null); }}
                            className="absolute top-2 right-2 bg-white rounded-lg p-1.5 border-2 border-[#1C1917] shadow-[2px_2px_0px_#1C1917]"
                        >
                            <X size={14} />
                        </button>
                    </div>
                ) : (
                    <label className="flex flex-col items-center justify-center gap-2 h-40 rounded-2xl border-2 border-dashed border-[#A27738] bg-[#F5EDD9] cursor-pointer text-[#A27738] hover:bg-[#E3CDA8]/50 transition-colors">
                        <ImagePlus size={28} strokeWidth={1.5} />
                        <span className="text-sm font-medium">Tirar foto ou escolher da galeria</span>
                        <span className="text-xs opacity-70">JPG ou PNG, até 10MB</span>
                        <input type="file" accept="image/*" onChange={handleFile} className="hidden" />
                    </label>
                )}
                {errors.foto && <p className="text-xs text-red-600">{errors.foto}</p>}

                <Input
                    label="Legenda (opcional)"
                    value={data.legenda}
                    onChange={(e) => setData('legenda', e.target.value)}
                    error={errors.legenda}
                    placeholder="chegamos no topo às 7h 🌄"
                    maxLength={140}
                />

                <p className="text-xs text-[#78716C]">
                    ⚠️ Sua foto ficará <strong>pública</strong> na página da trilha, junto com seu nome.
                </p>

                <Button type="submit" loading={processing} disabled={!data.foto} className="w-full">
                    Postar foto
                </Button>
            </form>
        </Modal>
    );
}

export default function FotosAventura({ agendamento, fotos = [], podePostar, eu }) {
    const [uploadOpen, setUploadOpen] = useState(false);
    const [deletando, setDeletando] = useState(null);

    function deletar(foto) {
        setDeletando(foto.id);
        router.delete(`/fotos-aventura/${foto.id}`, {
            preserveScroll: true,
            onFinish: () => setDeletando(null),
        });
    }

    if (!podePostar && fotos.length === 0) return null;

    return (
        <div className="bg-white rounded-2xl border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917] p-5 mt-4">
            <div className="flex items-center justify-between gap-2 mb-1">
                <h2 className="font-display font-bold text-[#1C1917] flex items-center gap-2">
                    <Camera size={17} className="text-[#E07A45]" /> Fotos da aventura
                </h2>
                {podePostar && (
                    <Button size="sm" variant="outline" onClick={() => setUploadOpen(true)}>
                        <ImagePlus size={14} /> Postar
                    </Button>
                )}
            </div>
            <p className="text-xs text-[#78716C] mb-3">
                {fotos.length > 0
                    ? 'As fotos aparecem na página da trilha pra inspirar outros trilheiros.'
                    : 'Poste as fotos dessa trilha! Elas aparecem na página da trilha pra todo mundo ver.'}
            </p>

            {fotos.length > 0 && (
                <div className="grid grid-cols-3 gap-2">
                    {fotos.map((foto) => {
                        const minha = foto.postado_por_type === eu;
                        return (
                            <div key={foto.id} className="relative group rounded-xl overflow-hidden border-2 border-[#1C1917] aspect-square">
                                <img
                                    src={`/${foto.thumb_path ?? foto.path}`}
                                    alt={foto.legenda ?? 'Foto da aventura'}
                                    loading="lazy"
                                    className="w-full h-full object-cover"
                                />
                                {/* autor */}
                                <div className="absolute bottom-1 left-1">
                                    <Avatar
                                        src={foto.autor?.foto ? `/${foto.autor.foto}` : null}
                                        name={foto.autor?.nome}
                                        size="sm"
                                        className="!w-6 !h-6 !text-[9px]"
                                    />
                                </div>
                                {minha && (
                                    <button
                                        onClick={() => deletar(foto)}
                                        disabled={deletando === foto.id}
                                        className={cn(
                                            'absolute top-1 right-1 bg-white/90 rounded-lg p-1 border border-[#1C1917]',
                                            'opacity-0 group-hover:opacity-100 focus:opacity-100 transition-opacity',
                                            deletando === foto.id && 'opacity-100 animate-pulse',
                                        )}
                                        title="Remover minha foto"
                                    >
                                        <Trash2 size={13} className="text-red-600" />
                                    </button>
                                )}
                            </div>
                        );
                    })}
                </div>
            )}

            <UploadModal
                open={uploadOpen}
                onClose={() => setUploadOpen(false)}
                agendamentoId={agendamento.id}
            />
        </div>
    );
}
