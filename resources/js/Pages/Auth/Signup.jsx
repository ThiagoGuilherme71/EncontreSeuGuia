import { useForm, Link } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Button from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';

export default function Signup({ errors = {} }) {
    const { data, setData, post, processing } = useForm({
        nome: '',
        email: '',
        telefone: '',
        data_nascimento: '',
        cpf: '',
        password: '',
        password_confirmation: '',
    });

    function submit(e) {
        e.preventDefault();
        post('/signup');
    }

    return (
        <AuthLayout title="Criar conta" subtitle="Cadastre-se para agendar trilhas com os melhores guias.">
            <form onSubmit={submit} className="flex flex-col gap-4">
                <Input
                    label="Nome completo"
                    value={data.nome}
                    onChange={(e) => setData('nome', e.target.value)}
                    error={errors.nome}
                    placeholder="Seu nome"
                    required
                    autoFocus
                />

                <Input
                    label="E-mail"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    error={errors.email}
                    placeholder="seu@email.com"
                    required
                />

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <Input
                        label="Telefone"
                        value={data.telefone}
                        onChange={(e) => setData('telefone', e.target.value)}
                        error={errors.telefone}
                        placeholder="(00) 00000-0000"
                        required
                    />
                    <Input
                        label="Data de nascimento"
                        type="date"
                        value={data.data_nascimento}
                        onChange={(e) => setData('data_nascimento', e.target.value)}
                        error={errors.data_nascimento}
                        required
                    />
                </div>

                <Input
                    label="CPF"
                    value={data.cpf}
                    onChange={(e) => setData('cpf', e.target.value)}
                    error={errors.cpf}
                    placeholder="000.000.000-00"
                    required
                />

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <Input
                        label="Senha"
                        type="password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        error={errors.password}
                        placeholder="Mín. 6 caracteres"
                        required
                    />
                    <Input
                        label="Confirmar senha"
                        type="password"
                        value={data.password_confirmation}
                        onChange={(e) => setData('password_confirmation', e.target.value)}
                        placeholder="Repita a senha"
                        required
                    />
                </div>

                <Button type="submit" loading={processing} className="w-full mt-2" size="lg">
                    Criar conta
                </Button>

                <p className="text-center text-sm text-[#78716C]">
                    Já tem conta?{' '}
                    <Link href="/login" className="text-[#2D6A4F] font-semibold hover:underline">
                        Entrar
                    </Link>
                </p>
                <p className="text-center text-sm text-[#78716C]">
                    É guia?{' '}
                    <Link href="/signup-guia" className="text-[#A27738] font-semibold hover:underline">
                        Cadastre-se como guia
                    </Link>
                </p>
            </form>
        </AuthLayout>
    );
}
