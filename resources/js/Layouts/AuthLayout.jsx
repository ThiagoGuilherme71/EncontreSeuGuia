import { Link } from '@inertiajs/react';

export default function AuthLayout({ children, title, subtitle }) {
    return (
        <div className="min-h-screen bg-[#1C1917] flex flex-col lg:flex-row">

            {/* Painel lateral (desktop) */}
            <div className="hidden lg:flex lg:w-1/2 relative bg-[#2D6A4F] items-center justify-center p-12 overflow-hidden">
                {/* Doodle background */}
                <div className="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="dots" x="0" y="0" width="30" height="30" patternUnits="userSpaceOnUse">
                                <circle cx="2" cy="2" r="2" fill="white"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#dots)"/>
                    </svg>
                </div>
                {/* Formas geométricas decorativas */}
                <div className="absolute top-16 right-16 w-24 h-24 border-4 border-white/20 rounded-2xl rotate-12" />
                <div className="absolute bottom-24 left-12 w-16 h-16 border-4 border-white/20 rounded-full" />
                <div className="absolute top-1/2 right-8 w-8 h-8 bg-[#F2C94C] border-2 border-[#1C1917] rounded-lg rotate-45" />

                <div className="relative text-white text-center max-w-sm">
                    {/* Container creme para a logo não se perder no fundo verde */}
                    <div className="w-28 h-28 rounded-3xl bg-[#ECE1CB] border-2 border-white/30 shadow-[4px_4px_0px_rgba(0,0,0,0.2)] flex items-center justify-center mx-auto mb-6 p-3">
                        <img src="/images/logo-montanha.svg" alt="Encontre seu Guia" className="w-full h-full" />
                    </div>
                    <h1 className="font-display font-bold text-4xl mb-4">Encontre seu Guia</h1>
                    <p className="text-lg opacity-80 leading-relaxed">
                        Conectando trilheiros e guias para experiências inesquecíveis na natureza.
                    </p>
                    <div className="mt-8 flex justify-center gap-3">
                        {['🌿', '⛰️', '🗺️'].map((emoji, i) => (
                            <span key={i} className="text-2xl">{emoji}</span>
                        ))}
                    </div>
                </div>
            </div>

            {/* Formulário */}
            <div className="flex-1 flex items-center justify-center p-6 lg:p-12">
                <div className="w-full max-w-md">
                    {/* Logo mobile */}
                    <div className="flex items-center justify-center gap-3 mb-8 lg:hidden">
                        <div className="w-14 h-14 rounded-2xl bg-[#ECE1CB] border-2 border-white/20 shadow-[3px_3px_0px_rgba(0,0,0,0.25)] flex items-center justify-center p-2 shrink-0">
                            <img src="/images/logo-montanha.svg" alt="Trilhas" className="w-full h-full" />
                        </div>
                        <span className="font-display font-bold text-xl text-white">Trilhas</span>
                    </div>

                    <div className="bg-[#FAFAF5] rounded-2xl border-2 border-[#E3CDA8] shadow-[5px_5px_0px_#E3CDA8] p-8">
                        {title && (
                            <div className="mb-6">
                                <h2 className="font-display font-bold text-2xl text-[#1C1917]">{title}</h2>
                                {subtitle && <p className="text-sm text-[#78716C] mt-1">{subtitle}</p>}
                            </div>
                        )}
                        {children}
                    </div>
                </div>
            </div>
        </div>
    );
}
