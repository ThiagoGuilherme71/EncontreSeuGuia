import { cn } from '@/lib/utils';
import { forwardRef } from 'react';

const baseInput = [
    'w-full bg-white border-2 border-[#1C1917] rounded-xl px-3 py-2 text-sm text-[#1C1917]',
    'placeholder:text-[#78716C] transition-colors duration-150',
    'focus:outline-none focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/20',
    'disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-[#F5EDD9]',
].join(' ');

export const Input = forwardRef(function Input({
    label,
    error,
    hint,
    className,
    wrapperClass,
    required,
    ...props
}, ref) {
    return (
        <div className={cn('flex flex-col gap-1', wrapperClass)}>
            {label && (
                <label className="text-sm font-semibold text-[#1C1917]">
                    {label}
                    {required && <span className="text-red-500 ml-0.5">*</span>}
                </label>
            )}
            <input
                ref={ref}
                className={cn(baseInput, error && 'border-red-500 focus:border-red-500 focus:ring-red-200', className)}
                {...props}
            />
            {error && <p className="text-xs text-red-600">{error}</p>}
            {hint && !error && <p className="text-xs text-[#78716C]">{hint}</p>}
        </div>
    );
});

export const Textarea = forwardRef(function Textarea({
    label,
    error,
    hint,
    className,
    wrapperClass,
    required,
    rows = 4,
    ...props
}, ref) {
    return (
        <div className={cn('flex flex-col gap-1', wrapperClass)}>
            {label && (
                <label className="text-sm font-semibold text-[#1C1917]">
                    {label}
                    {required && <span className="text-red-500 ml-0.5">*</span>}
                </label>
            )}
            <textarea
                ref={ref}
                rows={rows}
                className={cn(baseInput, 'resize-y min-h-[80px]', error && 'border-red-500', className)}
                {...props}
            />
            {error && <p className="text-xs text-red-600">{error}</p>}
            {hint && !error && <p className="text-xs text-[#78716C]">{hint}</p>}
        </div>
    );
});

export const Select = forwardRef(function Select({
    label,
    error,
    hint,
    className,
    wrapperClass,
    required,
    children,
    ...props
}, ref) {
    return (
        <div className={cn('flex flex-col gap-1', wrapperClass)}>
            {label && (
                <label className="text-sm font-semibold text-[#1C1917]">
                    {label}
                    {required && <span className="text-red-500 ml-0.5">*</span>}
                </label>
            )}
            <select
                ref={ref}
                className={cn(baseInput, 'cursor-pointer', error && 'border-red-500', className)}
                {...props}
            >
                {children}
            </select>
            {error && <p className="text-xs text-red-600">{error}</p>}
            {hint && !error && <p className="text-xs text-[#78716C]">{hint}</p>}
        </div>
    );
});
