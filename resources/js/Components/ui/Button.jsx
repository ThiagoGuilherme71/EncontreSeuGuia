import { cn } from '@/lib/utils';

const variants = {
    primary:   'bg-[#2D6A4F] text-white border-2 border-[#1C1917] hover:bg-[#235840] active:translate-y-px',
    secondary: 'bg-[#E3CDA8] text-[#1C1917] border-2 border-[#1C1917] hover:bg-[#d4bb92] active:translate-y-px',
    ghost:     'bg-transparent text-[#1C1917] border-2 border-transparent hover:border-[#1C1917] hover:bg-[#E3CDA8]',
    danger:    'bg-red-600 text-white border-2 border-[#1C1917] hover:bg-red-700 active:translate-y-px',
    outline:   'bg-white text-[#2D6A4F] border-2 border-[#2D6A4F] hover:bg-[#D8EFE3] active:translate-y-px',
};

const sizes = {
    sm:  'text-sm px-3 py-1.5 rounded-lg',
    md:  'text-sm px-4 py-2 rounded-xl',
    lg:  'text-base px-6 py-3 rounded-xl',
};

export default function Button({
    children,
    variant = 'primary',
    size = 'md',
    className,
    shadow = true,
    disabled,
    loading,
    ...props
}) {
    return (
        <button
            disabled={disabled || loading}
            className={cn(
                'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-150',
                'disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none',
                shadow && !disabled && 'shadow-[3px_3px_0px_#1C1917]',
                variants[variant],
                sizes[size],
                className,
            )}
            {...props}
        >
            {loading && (
                <svg className="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4" />
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg>
            )}
            {children}
        </button>
    );
}
