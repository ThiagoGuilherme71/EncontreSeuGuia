import { useState } from 'react';
import Button from '@/Components/ui/Button';
import { Input, Textarea, Select } from '@/Components/ui/Input';
import { ImagePlus, Mountain, X } from 'lucide-react';

export default function TrilhaForm({ data, setData, errors = {}, onSubmit, processing, submitLabel = 'Salvar', fotoAtual = null }) {
    const [preview, setPreview] = useState(fotoAtual ? `/${fotoAtual}` : null);

    function handleFoto(e) {
        const file = e.target.files?.[0];
        if (!file) return;
        setData('foto', file);
        setPreview(URL.createObjectURL(file));
    }

    function clearFoto() {
        setData('foto', null);
        setPreview(fotoAtual ? `/${fotoAtual}` : null);
    }

    return (
        <form onSubmit={onSubmit} className="flex flex-col gap-5">
            {/* Foto */}
            <div className="flex flex-col gap-1.5">
                <label className="text-sm font-semibold text-[#1C1917]">Foto da trilha</label>
                <div className="relative h-44 rounded-2xl border-2 border-dashed border-[#A27738] bg-[#F5EDD9] overflow-hidden">
                    {preview ? (
                        <>
                            <img src={preview} alt="Preview" className="w-full h-full object-cover" />
                            {data.foto && (
                                <button
                                    type="button"
                                    onClick={clearFoto}
                                    className="absolute top-2 right-2 bg-white rounded-lg p-1.5 border-2 border-[#1C1917] shadow-[2px_2px_0px_#1C1917]"
                                >
                                    <X size={14} />
                                </button>
                            )}
                        </>
                    ) : (
                        <label className="w-full h-full flex flex-col items-center justify-center gap-2 cursor-pointer text-[#A27738] hover:bg-[#E3CDA8]/50 transition-colors">
                            <ImagePlus size={28} strokeWidth={1.5} />
                            <span className="text-sm font-medium">Clique para enviar uma foto</span>
                            <span className="text-xs opacity-70">JPG ou PNG, até 4MB</span>
                            <input type="file" accept="image/*" onChange={handleFoto} className="hidden" />
                        </label>
                    )}
                </div>
                {preview && data.foto && (
                    <label className="text-xs text-[#2D6A4F] font-semibold cursor-pointer hover:underline">
                        Trocar foto
                        <input type="file" accept="image/*" onChange={handleFoto} className="hidden" />
                    </label>
                )}
                {errors.foto && <p className="text-xs text-red-600">{errors.foto}</p>}
            </div>

            <Input
                label="Nome da trilha"
                value={data.nome}
                onChange={(e) => setData('nome', e.target.value)}
                error={errors.nome}
                placeholder="Ex.: Cachoeira do Sossego"
                required
            />

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <Input
                    label="Cidade"
                    value={data.cidade}
                    onChange={(e) => setData('cidade', e.target.value)}
                    error={errors.cidade}
                    placeholder="Ex.: Lençóis"
                    required
                />
                <Select
                    label="Dificuldade"
                    value={data.id_dificuldade}
                    onChange={(e) => setData('id_dificuldade', e.target.value)}
                    error={errors.id_dificuldade}
                    required
                >
                    <option value="">Selecione...</option>
                    {(data._dificuldades ?? []).map((d) => (
                        <option key={d.id} value={d.id}>{d.descricao}</option>
                    ))}
                </Select>
            </div>

            <Textarea
                label="Descrição"
                value={data.descricao}
                onChange={(e) => setData('descricao', e.target.value)}
                error={errors.descricao}
                placeholder="Conte sobre a trilha: distância, tempo médio, o que esperar..."
                rows={5}
                required
            />

            <Button type="submit" loading={processing} size="lg" className="w-full">
                <Mountain size={17} /> {submitLabel}
            </Button>
        </form>
    );
}
