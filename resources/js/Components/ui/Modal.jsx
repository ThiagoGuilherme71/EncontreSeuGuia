import { cn } from '@/lib/utils';
import { Dialog, DialogPanel, DialogTitle, Transition, TransitionChild } from '@headlessui/react';
import { Fragment } from 'react';
import { X } from 'lucide-react';

export default function Modal({ open, onClose, title, children, size = 'md', className }) {
    const sizes = {
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-2xl',
    };

    return (
        <Transition show={open} as={Fragment}>
            <Dialog onClose={onClose} className="relative z-50">
                <TransitionChild
                    as={Fragment}
                    enter="ease-out duration-200"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-150"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="fixed inset-0 bg-black/40 backdrop-blur-sm" />
                </TransitionChild>

                <div className="fixed inset-0 overflow-y-auto flex items-center justify-center p-4">
                    <TransitionChild
                        as={Fragment}
                        enter="ease-out duration-200"
                        enterFrom="opacity-0 scale-95 translate-y-4"
                        enterTo="opacity-100 scale-100 translate-y-0"
                        leave="ease-in duration-150"
                        leaveFrom="opacity-100 scale-100 translate-y-0"
                        leaveTo="opacity-0 scale-95 translate-y-2"
                    >
                        <DialogPanel className={cn(
                            'w-full bg-white rounded-2xl border-2 border-[#1C1917] shadow-[5px_5px_0px_#1C1917] p-6',
                            sizes[size],
                            className,
                        )}>
                            <div className="flex items-start justify-between mb-4">
                                {title && (
                                    <DialogTitle className="font-display font-bold text-lg text-[#1C1917]">
                                        {title}
                                    </DialogTitle>
                                )}
                                <button
                                    onClick={onClose}
                                    className="ml-auto p-1 rounded-lg hover:bg-[#F5EDD9] transition-colors text-[#78716C]"
                                >
                                    <X size={18} />
                                </button>
                            </div>
                            {children}
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </Dialog>
        </Transition>
    );
}
