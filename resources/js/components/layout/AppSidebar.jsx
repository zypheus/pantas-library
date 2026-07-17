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
} from '@/components/ui/sidebar';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import { adminNavigation, filterNavigation } from '@/config/adminNavigation';
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
    const items = filterNavigation(adminNavigation, isAdmin);

    return (
        <Sidebar>
            <SidebarHeader className="border-b border-sidebar-border px-3 py-3">
                <div className="flex items-center gap-2">
                    {branding.logoCompactUrl || branding.logoUrl ? (
                        <img
                            src={branding.logoCompactUrl || branding.logoUrl}
                            alt=""
                            className="h-8 w-auto object-contain"
                        />
                    ) : (
                        <Home className="size-5" />
                    )}
                    <div className="min-w-0">
                        <p className="truncate text-sm font-semibold">
                            {branding.schoolName || branding.school_name || 'Library'}
                        </p>
                        <p className="truncate text-xs opacity-80">
                            {branding.staffPortalSubtitle || 'Staff Portal'}
                        </p>
                    </div>
                </div>
            </SidebarHeader>
            <SidebarContent>
                <SidebarGroup>
                    <SidebarGroupLabel>Menu</SidebarGroupLabel>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            {items.map((item) => {
                                const Icon = (item.icon && Lucide[item.icon]) || Home;
                                if (item.children?.length) {
                                    return (
                                        <Collapsible key={item.label} className="group/collapsible" defaultOpen>
                                            <SidebarMenuItem>
                                                <CollapsibleTrigger asChild>
                                                    <SidebarMenuButton>
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
                                                                    isActive={routeName === child.routeName}
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
                                        <SidebarMenuButton asChild isActive={routeName === item.routeName}>
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
            </SidebarContent>
            <SidebarFooter className="border-t border-sidebar-border p-2 text-xs opacity-80">
                {shell?.auth?.user?.name}
            </SidebarFooter>
        </Sidebar>
    );
}
