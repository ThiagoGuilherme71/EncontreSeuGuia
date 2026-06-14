import { useForm, Link } from '@inertiajs/react';
import AuthLayout from '@/Layouts/AuthLayout';
import Button from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Eye, EyeOff } from 'lucide-react';
import { useState } from 'react';

export default function Login({ errors = {} }) {
    const [showPass, setShowPass] = useState(false);

    const { data, setData, post, processing } = useForm({
        email: '',
        password: '',
    });

    function submit(e) {
        e.preventDefault();
        post('/login');
    }

    return (
        <AuthLayout title="Bem-vindo de volta!" subtitle="Entre na sua conta para continuar.">
            <form onSubmit={submit} className="flex flex-col gap-4">
                <Input
                    label="E-mail"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData('email', e.target.value)}
                    error={errors.email || errors.login}
                    placeholder="seu@email.com"
                    required
                    autoFocus
                />

                <div className="flex flex-col gap-1">
                    <label className="text-sm font-semibold text-[#1C1917]">
                        Senha <span className="text-red-500">*</span>
                    </label>
                    <div className="relative">
                        <input
                            type={showPass ? 'text' : 'password'}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            placeholder="••••••••"
                            className="w-full border-2 border-[#1C1917] rounded-xl px-3 py-2 pr-10 text-sm focus:outline-none focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/20"
                        />
                        <button
                            type="button"
                            onClick={() => setShowPass(!showPass)}
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-[#78716C]"
                        >
                            {showPass ? <EyeOff size={16} /> : <Eye size={16} />}
                        </button>
                    </div>
                    {errors.password && <p className="text-xs text-red-600">{errors.password}</p>}
                </div>

                {errors.login && (
                    <p className="text-sm text-red-600 bg-red-50 border border-red-200 rounded-xl px-3 py-2">
                        {errors.login}
                    </p>
                )}

                <Button type="submit" loading={processing} className="w-full mt-2" size="lg">
                    Entrar
                </Button>

                <p className="text-center text-sm text-[#78716C]">
                    Não tem conta?{' '}
                    <Link href="/signup" className="text-[#2D6A4F] font-semibold hover:underline">
                        Cadastre-se como trilheiro
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
