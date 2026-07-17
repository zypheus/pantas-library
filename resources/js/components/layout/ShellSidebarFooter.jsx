import { router } from '@inertiajs/react';
import { ChevronsUpDown, LogOut, UserRound } from 'lucide-react';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';

export function ShellSidebarFooter({ user, accountHref = '/account' }) {
    if (!user) {
        return null;
    }

    const initials = user.initials || user.name?.slice(0, 2)?.toUpperCase() || 'U';

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton
                            size="lg"
                            className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                        >
                            <Avatar className="size-8 rounded-lg">
                                {user.avatarUrl ? (
                                    <AvatarImage src={user.avatarUrl} alt={user.name} />
                                ) : null}
                                <AvatarFallback className="rounded-lg">{initials}</AvatarFallback>
                            </Avatar>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-medium">{user.name}</span>
                                <span className="truncate text-xs opacity-80">{user.email}</span>
                            </div>
                            <ChevronsUpDown className="ml-auto size-4 opacity-70" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className="w-[--radix-dropdown-menu-trigger-width] min-w-56 rounded-lg"
                        side="top"
                        align="end"
                        sideOffset={4}
                    >
                        <DropdownMenuLabel className="p-0 font-normal">
                            <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <Avatar className="size-8 rounded-lg">
                                    {user.avatarUrl ? (
                                        <AvatarImage src={user.avatarUrl} alt={user.name} />
                                    ) : null}
                                    <AvatarFallback className="rounded-lg">{initials}</AvatarFallback>
                                </Avatar>
                                <div className="grid flex-1 text-left text-sm leading-tight">
                                    <span className="truncate font-medium">{user.name}</span>
                                    <span className="truncate text-xs text-muted-foreground">{user.email}</span>
                                </div>
                            </div>
                        </DropdownMenuLabel>
                        <DropdownMenuSeparator />
                        {accountHref ? (
                            <DropdownMenuItem onClick={() => router.visit(accountHref)}>
                                <UserRound className="size-4" />
                                Account
                            </DropdownMenuItem>
                        ) : null}
                        <DropdownMenuItem onClick={() => router.post('/logout')}>
                            <LogOut className="size-4" />
                            Sign out
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
}
