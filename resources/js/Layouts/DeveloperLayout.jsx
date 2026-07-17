import { DeveloperShellLayout } from '@/Layouts/DeveloperShellLayout';

export default function DeveloperLayout({ children, title }) {
    return <DeveloperShellLayout title={title}>{children}</DeveloperShellLayout>;
}
