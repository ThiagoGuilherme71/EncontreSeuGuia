import AppLayout from '@/Layouts/AppLayout';

export default function GuiaLayout({ children, className }) {
    return (
        <AppLayout className={className}>
            {children}
        </AppLayout>
    );
}
