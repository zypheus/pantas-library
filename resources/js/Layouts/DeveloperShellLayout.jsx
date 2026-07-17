import { DeveloperHeader } from '@/components/layout/DeveloperHeader';
import { DeveloperSidebar } from '@/components/layout/DeveloperSidebar';
import { ShellFlashMessages } from '@/components/layout/ShellFlashMessages';
import { SidebarInset, SidebarProvider } from '@/components/ui/sidebar';
import { TooltipProvider } from '@/components/ui/tooltip';
import { usePage } from '@inertiajs/react';

export function DeveloperShellLayout({ title, children }) {
    const { flash } = usePage().props;

    return (
        <TooltipProvider>
            <SidebarProvider className="developer-shell min-w-0 w-full">
                <DeveloperSidebar />
                <SidebarInset>
                    <DeveloperHeader title={title} />
                    <div className="flex flex-1 flex-col gap-4 p-4 md:p-6">
                        <ShellFlashMessages flash={flash} />
                        {children}
                    </div>
                </SidebarInset>
            </SidebarProvider>
        </TooltipProvider>
    );
}
