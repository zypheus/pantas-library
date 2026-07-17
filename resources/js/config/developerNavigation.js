export const developerNavigation = [
    { label: 'Overview', href: '/developer', routeName: 'developer.dashboard', icon: 'LayoutDashboard' },
    { label: 'Branding', href: '/developer/branding', routeName: 'developer.branding', icon: 'BadgeCheck' },
    { label: 'Colors & Tokens', href: '/developer/colors', routeName: 'developer.colors', icon: 'Palette' },
    { label: 'Typography', href: '/developer/typography', routeName: 'developer.typography', icon: 'Type' },
    { label: 'OPAC Landing', href: '/developer/landing', routeName: 'developer.landing', icon: 'Home' },
    { label: 'Feature Flags', href: '/developer/feature-flags', routeName: 'developer.feature_flags', icon: 'ToggleLeft' },
    { label: 'Theme Packages', href: '/developer/packages', routeName: 'developer.packages', icon: 'Package' },
    { label: 'Design System', href: '/developer/design-system', routeName: 'developer.design_system', icon: 'Boxes' },
    { label: 'System', href: '/developer/system', routeName: 'developer.system', icon: 'Settings' },
];

export function isDeveloperNavActive(item, routeName, pathname) {
    if (routeName && item.routeName === routeName) {
        return true;
    }

    if (pathname && item.href === pathname) {
        return true;
    }

    if (pathname && item.href !== '/developer' && pathname.startsWith(`${item.href}/`)) {
        return true;
    }

    return false;
}

export function resolveDeveloperBreadcrumbs(routeName, title) {
    const item = developerNavigation.find((entry) => entry.routeName === routeName);

    if (!item) {
        return [
            { label: 'Developer', href: '/developer', isCurrent: false },
            { label: title || 'Console', isCurrent: true },
        ];
    }

    if (item.routeName === 'developer.dashboard') {
        return [{ label: 'Overview', isCurrent: true }];
    }

    return [
        { label: 'Developer', href: '/developer', isCurrent: false },
        { label: item.label, isCurrent: true },
    ];
}
