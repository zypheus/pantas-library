import { Separator } from '@/components/ui/separator';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { AppBreadcrumb } from '@/components/layout/AppBreadcrumb';
import { resolveDeveloperBreadcrumbs } from '@/config/developerNavigation';
import { usePage } from '@inertiajs/react';
import { ExternalLink } from 'lucide-react';
import { Button } from '@/components/ui/button';

export function DeveloperHeader({ title }) {
    const { routeName } = usePage().props;
    const breadcrumbs = resolveDeveloperBreadcrumbs(routeName, title);

    return (
        <header className="flex h-14 shrink-0 items-center gap-2 border-b bg-background px-4">
            <SidebarTrigger type="button" className="-ml-1" />
            <Separator orientation="vertical" className="mr-2 h-4" />
            <div className="flex min-w-0 flex-1 flex-col gap-0.5 sm:flex-row sm:items-center sm:gap-4">
                <div className="min-w-0">
                    <p className="text-xs font-medium uppercase tracking-wide text-muted-foreground">
                        Developer
                    </p>
                    <h1 className="truncate text-base font-semibold leading-tight sm:text-lg">
                        {title || 'Console'}
                    </h1>
                </div>
                <div className="hidden min-w-0 flex-1 sm:block">
                    <AppBreadcrumb items={breadcrumbs} />
                </div>
            </div>
            <Button variant="outline" size="sm" asChild className="hidden shrink-0 sm:inline-flex">
                <a href="/branding/theme.css" target="_blank" rel="noreferrer">
                    <ExternalLink className="size-4" />
                    theme.css
                </a>
            </Button>
        </header>
    );
}
