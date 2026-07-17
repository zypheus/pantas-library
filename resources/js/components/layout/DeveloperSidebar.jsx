import { Link, usePage } from '@inertiajs/react';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarRail,
} from '@/components/ui/sidebar';
import { ScrollArea } from '@/components/ui/scroll-area';
import { developerNavigation, isDeveloperNavActive } from '@/config/developerNavigation';
import { ShellSidebarFooter } from '@/components/layout/ShellSidebarFooter';
import { Code2 } from 'lucide-react';
import * as Lucide from 'lucide-react';

export function DeveloperSidebar() {
    const { auth, branding, routeName } = usePage().props;
    const pathname = typeof window !== 'undefined' ? window.location.pathname : '';

    return (
        <Sidebar collapsible="icon" variant="sidebar">
            <SidebarHeader className="border-b border-sidebar-border">
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild tooltip="Developer Console">
                            <Link href="/developer">
                                {branding?.logoCompactUrl || branding?.logo_compact_url ? (
                                    <img
                                        src={branding.logoCompactUrl || branding.logo_compact_url}
                                        alt=""
                                        className="size-8 rounded-md object-contain"
                                    />
                                ) : (
                                    <div className="flex size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
                                        <Code2 className="size-4" />
                                    </div>
                                )}
                                <div className="grid flex-1 text-left text-sm leading-tight">
                                    <span className="truncate font-semibold">Developer Console</span>
                                    <span className="truncate text-xs opacity-80">
                                        {branding?.systemName || branding?.system_name || 'PANTAS'}
                                    </span>
                                </div>
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <ScrollArea className="h-full">
                    <SidebarGroup>
                        <SidebarGroupLabel>Appearance</SidebarGroupLabel>
                        <SidebarGroupContent>
                            <SidebarMenu>
                                {developerNavigation.map((item) => {
                                    const Icon = (item.icon && Lucide[item.icon]) || Code2;
                                    const active = isDeveloperNavActive(item, routeName, pathname);

                                    return (
                                        <SidebarMenuItem key={item.href}>
                                            <SidebarMenuButton
                                                asChild
                                                isActive={active}
                                                tooltip={item.label}
                                            >
                                                <Link href={item.href}>
                                                    <Icon />
                                                    <span>{item.label}</span>
                                                </Link>
                                            </SidebarMenuButton>
                                        </SidebarMenuItem>
                                    );
                                })}
                            </SidebarMenu>
                        </SidebarGroupContent>
                    </SidebarGroup>
                </ScrollArea>
            </SidebarContent>
            <SidebarFooter className="border-t border-sidebar-border">
                <ShellSidebarFooter user={auth?.user} accountHref={null} />
            </SidebarFooter>
            <SidebarRail />
        </Sidebar>
    );
}
