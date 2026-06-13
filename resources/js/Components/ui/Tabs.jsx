import { cn } from '@/lib/utils';
import { createContext, useContext, useState } from 'react';

const TabsCtx = createContext(null);

export function Tabs({ defaultValue, value, onChange, children, className }) {
    const [active, setActive] = useState(defaultValue);
    const current = value ?? active;
    const setTab = onChange ?? setActive;

    return (
        <TabsCtx.Provider value={{ current, setTab }}>
            <div className={cn('', className)}>{children}</div>
        </TabsCtx.Provider>
    );
}

export function TabList({ children, className, variant = 'line' }) {
    const styles = {
        line: 'flex gap-1 border-b-2 border-[#1C1917] overflow-x-auto scrollbar-hide',
        pills: 'flex flex-wrap gap-2 bg-[#F5EDD9] p-1 rounded-xl',
    };
    return (
        <div className={cn(styles[variant], className)}>
            {children}
        </div>
    );
}

export function Tab({ value, children, className, variant = 'line' }) {
    const { current, setTab } = useContext(TabsCtx);
    const active = current === value;

    const lineStyles = active
        ? 'border-b-2 border-[#2D6A4F] text-[#2D6A4F] font-bold -mb-0.5'
        : 'text-[#78716C] hover:text-[#1C1917] border-b-2 border-transparent -mb-0.5';

    const pillStyles = active
        ? 'bg-white text-[#1C1917] font-bold shadow-[2px_2px_0px_#1C1917] border-2 border-[#1C1917]'
        : 'text-[#78716C] hover:text-[#1C1917] border-2 border-transparent';

    return (
        <button
            type="button"
            onClick={() => setTab(value)}
            className={cn(
                'px-4 py-2.5 text-sm whitespace-nowrap transition-all duration-150 rounded-t-lg',
                variant === 'line' ? lineStyles : pillStyles,
                className,
            )}
        >
            {children}
        </button>
    );
}

export function TabPanel({ value, children, className }) {
    const { current } = useContext(TabsCtx);
    if (current !== value) return null;
    return (
        <div className={cn('animate-fade-up', className)}>
            {children}
        </div>
    );
}
