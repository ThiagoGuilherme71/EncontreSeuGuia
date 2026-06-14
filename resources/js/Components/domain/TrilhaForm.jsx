import { useState } from 'react';
import Button from '@/Components/ui/Button';
import { Input, Textarea, Select } from '@/Components/ui/Input';
import EstadoCidadeSelect from '@/Components/domain/EstadoCidadeSelect';
import ChipInput from '@/Components/ui/ChipInput';
import MapPicker from '@/Components/ui/MapPicker';
import { ImagePlus, Mountain, X } from 'lucide-react';

const MAX_FOTO_MB = 4;
const MAX_FOTO_BYTES = MAX_FOTO_MB * 1024 * 1024;

export default function TrilhaForm({ data, setData, errors = {}, onSubmit, processing, submitLabel = 'Salvar', fotoAtual = null, dificuldades = [], }) {
    const [preview, setPreview] = useState(fotoAtual ? `/${fotoAtual}` : null);
    const [fotoSizeError, setFotoSizeError] = useState(null);
    const fotoError = fotoSizeError || errors.foto;

    function handleFoto(e) {
        const file = e.target.files?.[0];
        if (!file) return;
        if (file.size > MAX_FOTO_BYTES) {
            setFotoSizeError(`A foto deve ter no máximo ${MAX_FOTO_MB}MB (arquivo enviado: ${(file.size / 1024 / 1024).toFixed(1)}MB)`);
            e.target.value = '';
            return;
        }
        setFotoSizeError(null);
        setData('foto', file);
        setPreview(URL.createObjectURL(file));
    }

    function clearFoto() {
        setData('foto', null);
        setPreview(fotoAtual ? `/${fotoAtual}` : null);
        setFotoSizeError(null);
    }

    return (
        <form onSubmit={onSubmit} className="flex flex-col gap-5">
            {/* Foto */}
            <div className="flex flex-col gap-1.5">
                <label className="text-sm font-semibold text-[#1C1917]">
                    Foto da trilha {!fotoAtual && <span className="text-red-500">*</span>}
                </label>
                <div className={`relative h-44 rounded-2xl border-2 border-dashed overflow-hidden ${fotoError ? 'border-red-500 bg-red-50' : 'border-[#A27738] bg-[#F5EDD9]'}`}>
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
                {preview && (
                    <label className="text-xs text-[#2D6A4F] font-semibold cursor-pointer hover:underline">
                        {data.foto ? 'Trocar foto' : 'Alterar foto'}
                        <input type="file" accept="image/*" onChange={handleFoto} className="hidden" />
                    </label>
                )}
                {fotoError && <p className="text-xs text-red-600">{fotoError}</p>}
            </div>

            <Input
                label="Nome da trilha"
                value={data.nome}
                onChange={(e) => setData('nome', e.target.value)}
                error={errors.nome}
                placeholder="Ex.: Cachoeira do Sossego"
                required
            />

            <EstadoCidadeSelect
                estado={data.estado}
                cidade={data.cidade}
                onEstadoChange={(v) => setData('estado', v)}
                onCidadeChange={(v) => setData('cidade', v)}
                errors={errors}
            />

            {/* Distância e tempo */}
            <div className="grid grid-cols-2 gap-4">
                <Input
                    label="Distância total (km)"
                    type="number"
                    min="0"
                    max="9999"
                    step="0.1"
                    value={data.distancia_km ?? ''}
                    onChange={(e) => setData('distancia_km', e.target.value || null)}
                    error={errors.distancia_km}
                    placeholder="Ex.: 12"
                />

                {/* Tempo estimado — seletor horas + minutos */}
                <div className="flex flex-col gap-1.5">
                    <label className="text-sm font-semibold text-[#1C1917]">Tempo estimado</label>
                    <div className="flex gap-2">
                        <select
                            value={data.tempo_estimado_horas != null ? Math.floor(Number(data.tempo_estimado_horas)) : ''}
                            onChange={(e) => {
                                const h = e.target.value === '' ? null : Number(e.target.value);
                                const minPart = data.tempo_estimado_horas != null
                                    ? (Number(data.tempo_estimado_horas) % 1) * 60
                                    : 0;
                                setData('tempo_estimado_horas', h != null ? h + minPart / 60 : null);
                            }}
                            className="flex-1 h-11 rounded-xl border-2 border-[#E3CDA8] bg-white px-3 text-sm text-[#1C1917] focus:outline-none focus:border-[#2D6A4F] appearance-none"
                        >
                            <option value="">h</option>
                            {Array.from({ length: 73 }, (_, i) => (
                                <option key={i} value={i}>{i}h</option>
                            ))}
                        </select>
                        <select
                            value={data.tempo_estimado_horas != null ? Math.round((Number(data.tempo_estimado_horas) % 1) * 60) : ''}
                            onChange={(e) => {
                                const min = e.target.value === '' ? 0 : Number(e.target.value);
                                const hPart = data.tempo_estimado_horas != null
                                    ? Math.floor(Number(data.tempo_estimado_horas))
                                    : 0;
                                setData('tempo_estimado_horas', hPart + min / 60);
                            }}
                            className="flex-1 h-11 rounded-xl border-2 border-[#E3CDA8] bg-white px-3 text-sm text-[#1C1917] focus:outline-none focus:border-[#2D6A4F] appearance-none"
                        >
                            {[0, 15, 30, 45].map((m) => (
                                <option key={m} value={m}>{String(m).padStart(2, '0')}min</option>
                            ))}
                        </select>
                    </div>
                    {errors.tempo_estimado_horas && (
                        <p className="text-xs text-red-600">{errors.tempo_estimado_horas}</p>
                    )}
                </div>
            </div>

            <Select
                label="Dificuldade"
                value={data.id_dificuldade}
                onChange={(e) => setData('id_dificuldade', e.target.value)}
                error={errors.id_dificuldade}
                required
            >
                <option value="">Selecione...</option>
                {dificuldades.map((d) => (
                    <option key={d.id} value={d.id}>{d.descricao}</option>
                ))}
            </Select>

            <Textarea
                label="Descrição"
                value={data.descricao}
                onChange={(e) => setData('descricao', e.target.value)}
                error={errors.descricao}
                placeholder="Conte sobre a trilha: distância, tempo médio, o que esperar..."
                rows={5}
                required
            />

            {/* O que levar */}
            <ChipInput
                label="O que levar"
                value={data.o_que_levar ?? []}
                onChange={(chips) => setData('o_que_levar', chips)}
                placeholder="Ex.: 2L de água, bota de trilha..."
                error={errors.o_que_levar}
            />

            {/* Ponto de encontro */}
            <div className="flex flex-col gap-3">
                <MapPicker
                    lat={data.ponto_encontro_lat}
                    lng={data.ponto_encontro_lng}
                    onChange={(lat, lng) => {
                        setData('ponto_encontro_lat', lat);
                        setData('ponto_encontro_lng', lng);
                    }}
                    error={errors.ponto_encontro_lat}
                />
                <Input
                    label="Descrição do ponto de encontro"
                    value={data.ponto_encontro_descricao ?? ''}
                    onChange={(e) => setData('ponto_encontro_descricao', e.target.value)}
                    error={errors.ponto_encontro_descricao}
                    placeholder="Ex.: Estacionamento em frente à pousada Vale Verde"
                />
            </div>

            <Button type="submit" loading={processing} size="lg" className="w-full">
                <Mountain size={17} /> {submitLabel}
            </Button>
        </form>
    );
}
