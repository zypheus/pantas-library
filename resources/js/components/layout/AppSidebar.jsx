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
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarRail,
} from '@/components/ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { ScrollArea } from '@/components/ui/scroll-area';
import {
    adminNavigation,
    filterNavigation,
    isNavGroupActive,
    isNavItemActive,
} from '@/config/adminNavigation';
import { ShellSidebarFooter } from '@/components/layout/ShellSidebarFooter';
import { useShellProps } from '@/context/ShellPropsContext';
import { ChevronRight, Home } from 'lucide-react';
import * as Lucide from 'lucide-react';

export function AppSidebar() {
    let shell;
    try {
        shell = useShellProps();
    } catch {
        shell = usePage().props;
    }

    const isAdmin = Boolean(shell?.auth?.user?.isAdmin);
    const branding = shell?.branding || {};
    const routeName = shell?.routeName;
    const user = shell?.auth?.user;
    const items = filterNavigation(adminNavigation, isAdmin);
    const pathname = typeof window !== 'undefined' ? window.location.pathname : '';

    return (
        <Sidebar collapsible="icon" variant="sidebar">
            <SidebarHeader className="border-b border-sidebar-border">
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild tooltip={branding.schoolName || 'Staff Portal'}>
                            <Link href="/book">
                                {branding.logoCompactUrl || branding.logoUrl ? (
                                    <img
                                        src={branding.logoCompactUrl || branding.logoUrl}
                                        alt=""
                                        className="size-8 rounded-md object-contain"
                                    />
                                ) : (
                                    <div className="flex size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground">
                                        <Home className="size-4" />
                                    </div>
                                )}
                                <div className="grid flex-1 text-left text-sm leading-tight">
                                    <span className="truncate font-semibold">
                                        {branding.schoolName || branding.school_name || 'Library'}
                                    </span>
                                    <span className="truncate text-xs opacity-80">
                                        {branding.staffPortalSubtitle || 'Staff Portal'}
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
                        <SidebarGroupLabel>Navigation</SidebarGroupLabel>
                        <SidebarGroupContent>
                            <SidebarMenu>
                                {items.map((item) => {
                                    const Icon = (item.icon && Lucide[item.icon]) || Home;

                                    if (item.children?.length) {
                                        const groupActive = isNavGroupActive(item, routeName, pathname);

                                        return (
                                            <Collapsible
                                                key={item.label}
                                                className="group/collapsible"
                                                defaultOpen={groupActive}
                                            >
                                                <SidebarMenuItem>
                                                    <CollapsibleTrigger asChild>
                                                        <SidebarMenuButton tooltip={item.label} isActive={groupActive}>
                                                            <Icon />
                                                            <span>{item.label}</span>
                                                            <ChevronRight className="ml-auto transition-transform group-data-[state=open]/collapsible:rotate-90" />
                                                        </SidebarMenuButton>
                                                    </CollapsibleTrigger>
                                                    <CollapsibleContent>
                                                        <SidebarMenuSub>
                                                            {item.children.map((child) => (
                                                                <SidebarMenuSubItem key={child.href}>
                                                                    <SidebarMenuSubButton
                                                                        asChild
                                                                        isActive={isNavItemActive(
                                                                            child,
                                                                            routeName,
                                                                            pathname,
                                                                        )}
                                                                    >
                                                                        <Link href={child.href}>{child.label}</Link>
                                                                    </SidebarMenuSubButton>
                                                                </SidebarMenuSubItem>
                                                            ))}
                                                        </SidebarMenuSub>
                                                    </CollapsibleContent>
                                                </SidebarMenuItem>
                                            </Collapsible>
                                        );
                                    }

                                    return (
                                        <SidebarMenuItem key={item.href || item.label}>
                                            <SidebarMenuButton
                                                asChild
                                                isActive={isNavItemActive(item, routeName, pathname)}
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
                <ShellSidebarFooter user={user} />
            </SidebarFooter>
            <SidebarRail />
        </Sidebar>
    );
}
