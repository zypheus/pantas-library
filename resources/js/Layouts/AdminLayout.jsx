import { AdminShellLayout } from '@/Layouts/AdminShellLayout';
import { ShellPropsProvider } from '@/context/ShellPropsContext';
import { usePage } from '@inertiajs/react';

/**
 * Inertia wrapper using the same admin shell as Blade pages.
 */
export default function AdminLayout({ children, breadcrumbOverride }) {
    const { routeName, auth, branding, adminActivity, flash } = usePage().props;

    return (
        <ShellPropsProvider
            value={{
                auth,
                branding,
                adminActivity,
                routeName,
                flash,
            }}
        >
            <AdminShellLayout
                routeName={routeName}
                breadcrumbOverride={breadcrumbOverride}
                flash={flash}
            >
                {children}
            </AdminShellLayout>
        </ShellPropsProvider>
    );
}
