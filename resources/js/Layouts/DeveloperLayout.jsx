import { Link, usePage, router } from '@inertiajs/react';
import { developerNavigation } from '@/config/developerNavigation';
import {
    LayoutDashboard,
    BadgeCheck,
    Palette,
    Type,
    Home,
    ToggleLeft,
    Package,
    Boxes,
    Settings,
    LogOut,
} from 'lucide-react';
import { cn } from '@/lib/utils';

const icons = {
    LayoutDashboard,
    BadgeCheck,
    Palette,
    Type,
    Home,
    ToggleLeft,
    Package,
    Boxes,
    Settings,
};

export default function DeveloperLayout({ children, title }) {
    const { auth, flash, routeName, branding } = usePage().props;
    const user = auth?.user;

    return (
        <div className="min-h-screen bg-muted/40 text-foreground">
            <div className="flex min-h-screen">
                <aside className="hidden w-64 shrink-0 border-r bg-[var(--brand-sidebar-bg,#0f172a)] text-[var(--brand-sidebar-text,#fff)] md:flex md:flex-col">
                    <div className="border-b border-white/10 px-4 py-4">
                        <p className="text-xs uppercase tracking-wider text-white/70">Developer Console</p>
                        <p className="mt-1 text-sm font-semibold leading-snug">
                            {branding?.library_name || branding?.libraryName || 'Library'}
                        </p>
                        <p className="text-xs text-white/60">{branding?.system_name || branding?.systemName || 'PANTAS'}</p>
                    </div>
                    <nav className="flex-1 space-y-1 overflow-y-auto p-3">
                        {developerNavigation.map((item) => {
                            const Icon = icons[item.icon] || LayoutDashboard;
                            const active = routeName === item.routeName;
                            return (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    className={cn(
                                        'flex items-center gap-2 rounded-md px-3 py-2 text-sm transition-colors',
                                        active
                                            ? 'bg-white/15 font-medium text-white'
                                            : 'text-white/80 hover:bg-white/10 hover:text-white'
                                    )}
                                >
                                    <Icon className="size-4 shrink-0 opacity-80" />
                                    {item.label}
                                </Link>
                            );
                        })}
                    </nav>
                    <div className="border-t border-white/10 p-3">
                        <p className="truncate px-2 text-xs text-white/70">{user?.name}</p>
                        <p className="truncate px-2 text-[11px] text-white/50">{user?.email}</p>
                        <button
                            type="button"
                            className="mt-2 flex w-full items-center gap-2 rounded-md px-2 py-2 text-sm text-white/80 hover:bg-white/10"
                            onClick={() => router.post('/logout')}
                        >
                            <LogOut className="size-4" />
                            Sign out
                        </button>
                    </div>
                </aside>

                <div className="flex min-w-0 flex-1 flex-col">
                    <header className="flex items-center justify-between border-b bg-background px-4 py-3 md:px-6">
                        <div>
                            <p className="text-xs uppercase tracking-wide text-muted-foreground">Developer</p>
                            <h1 className="text-lg font-semibold">{title || 'Console'}</h1>
                        </div>
                        <div className="flex items-center gap-2 md:hidden">
                            <select
                                className="rounded-md border bg-background px-2 py-1.5 text-sm"
                                value={developerNavigation.find((n) => n.routeName === routeName)?.href || '/developer'}
                                onChange={(e) => router.visit(e.target.value)}
                            >
                                {developerNavigation.map((item) => (
                                    <option key={item.href} value={item.href}>{item.label}</option>
                                ))}
                            </select>
                        </div>
                    </header>

                    {(flash?.success || flash?.error) && (
                        <div className="px-4 pt-4 md:px-6">
                            {flash.success && (
                                <div className="rounded-md border border-green-200 bg-green-50 px-3 py-2 text-sm text-green-800">
                                    {flash.success}
                                </div>
                            )}
                            {flash.error && (
                                <div className="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">
                                    {flash.error}
                                </div>
                            )}
                        </div>
                    )}

                    <main className="flex-1 p-4 md:p-6">{children}</main>
                </div>
            </div>
        </div>
    );
}
