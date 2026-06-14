import { useState, useEffect } from 'react';
import { Select } from '@/Components/ui/Input';

const ESTADOS = [
    { sigla: 'AC', nome: 'Acre' }, { sigla: 'AL', nome: 'Alagoas' },
    { sigla: 'AP', nome: 'Amapá' }, { sigla: 'AM', nome: 'Amazonas' },
    { sigla: 'BA', nome: 'Bahia' }, { sigla: 'CE', nome: 'Ceará' },
    { sigla: 'DF', nome: 'Distrito Federal' }, { sigla: 'ES', nome: 'Espírito Santo' },
    { sigla: 'GO', nome: 'Goiás' }, { sigla: 'MA', nome: 'Maranhão' },
    { sigla: 'MT', nome: 'Mato Grosso' }, { sigla: 'MS', nome: 'Mato Grosso do Sul' },
    { sigla: 'MG', nome: 'Minas Gerais' }, { sigla: 'PA', nome: 'Pará' },
    { sigla: 'PB', nome: 'Paraíba' }, { sigla: 'PR', nome: 'Paraná' },
    { sigla: 'PE', nome: 'Pernambuco' }, { sigla: 'PI', nome: 'Piauí' },
    { sigla: 'RJ', nome: 'Rio de Janeiro' }, { sigla: 'RN', nome: 'Rio Grande do Norte' },
    { sigla: 'RS', nome: 'Rio Grande do Sul' }, { sigla: 'RO', nome: 'Rondônia' },
    { sigla: 'RR', nome: 'Roraima' }, { sigla: 'SC', nome: 'Santa Catarina' },
    { sigla: 'SP', nome: 'São Paulo' }, { sigla: 'SE', nome: 'Sergipe' },
    { sigla: 'TO', nome: 'Tocantins' },
];

export default function EstadoCidadeSelect({ estado, cidade, onEstadoChange, onCidadeChange, errors = {} }) {
    const [cidades, setCidades] = useState([]);
    const [loadingCidades, setLoadingCidades] = useState(false);

    useEffect(() => {
        if (!estado) {
            setCidades([]);
            return;
        }

        setLoadingCidades(true);
        fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${estado}/municipios?orderBy=nome`)
            .then((r) => r.json())
            .then((data) => setCidades(data.map((m) => m.nome)))
            .catch(() => setCidades([]))
            .finally(() => setLoadingCidades(false));
    }, [estado]);

    function handleEstado(e) {
        onEstadoChange(e.target.value);
        onCidadeChange('');
    }

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Select
                label="Estado"
                value={estado}
                onChange={handleEstado}
                error={errors.estado}
                required
            >
                <option value="">Selecione o estado...</option>
                {ESTADOS.map((uf) => (
                    <option key={uf.sigla} value={uf.sigla}>
                        {uf.nome} ({uf.sigla})
                    </option>
                ))}
            </Select>

            <Select
                label="Cidade"
                value={cidade}
                onChange={(e) => onCidadeChange(e.target.value)}
                error={errors.cidade}
                disabled={!estado || loadingCidades}
                required
            >
                <option value="">
                    {loadingCidades ? 'Carregando...' : estado ? 'Selecione a cidade...' : 'Selecione o estado primeiro'}
                </option>
                {cidades.map((c) => (
                    <option key={c} value={c}>{c}</option>
                ))}
            </Select>
        </div>
    );
}
