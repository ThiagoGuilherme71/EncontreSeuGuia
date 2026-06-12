import { Link } from '@inertiajs/react';
import { Mountain } from 'lucide-react';

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
                    <div className="w-16 h-16 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center mx-auto mb-6">
                        <Mountain size={32} strokeWidth={2} />
                    </div>
                    <h1 className="font-display font-bold text-4xl mb-4">Trilhas</h1>
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
                    <div className="flex items-center justify-center gap-2 mb-8 lg:hidden">
                        <div className="w-10 h-10 rounded-xl bg-[#2D6A4F] border-2 border-[#E3CDA8] flex items-center justify-center">
                            <Mountain size={20} className="text-white" />
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
