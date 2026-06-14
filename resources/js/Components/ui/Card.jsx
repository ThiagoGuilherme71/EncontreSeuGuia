import { cn } from '@/lib/utils';

const variants = {
    default:  'bg-white border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917]',
    elevated: 'bg-white border-2 border-[#1C1917] shadow-[5px_5px_0px_#1C1917]',
    flat:     'bg-white border border-[#E3CDA8]',
    ocre:     'bg-[#F5EDD9] border-2 border-[#1C1917] shadow-[3px_3px_0px_#1C1917]',
    verde:    'bg-[#D8EFE3] border-2 border-[#2D6A4F] shadow-[3px_3px_0px_#2D6A4F]',
};

export function Card({ children, variant = 'default', className, ...props }) {
    return (
        <div
            className={cn('rounded-2xl p-4 transition-all', variants[variant], className)}
            {...props}
        >
            {children}
        </div>
    );
}

export function CardHeader({ children, className }) {
    return <div className={cn('mb-3', className)}>{children}</div>;
}

export function CardTitle({ children, className }) {
    return (
        <h3 className={cn('font-display font-bold text-lg text-[#1C1917]', className)}>
            {children}
        </h3>
    );
}

export function CardBody({ children, className }) {
    return <div className={cn('', className)}>{children}</div>;
}

export function CardFooter({ children, className }) {
    return (
        <div className={cn('mt-4 pt-3 border-t border-[#E3CDA8]', className)}>
            {children}
        </div>
    );
}
