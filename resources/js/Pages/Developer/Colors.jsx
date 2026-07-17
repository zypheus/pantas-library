import { Head } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ColorField, PublishBar, useAppearanceForm, saveDraft } from './components/AppearanceHelpers';

const THEME_GROUPS = [
    {
        title: 'Core palette',
        keys: ['brand-primary', 'brand-accent', 'brand-blue', 'brand-blue-dark', 'brand-green-dark', 'brand-text-dark', 'brand-text-light', 'brand-page-bg'],
    },
    {
        title: 'Navigation & chrome',
        keys: ['brand-nav-link', 'brand-nav-link-active', 'brand-school-name-color', 'brand-footer-bg', 'brand-sidebar-bg', 'brand-sidebar-text', 'brand-shell-background', 'brand-shell-button-bg', 'brand-shell-button-text'],
    },
    {
        title: 'Kiosk & semantic',
        keys: ['brand-kiosk-gradient-from', 'brand-kiosk-gradient-to', 'brand-logout-bg', 'brand-logout-text', 'brand-danger-bg', 'brand-danger-text', 'brand-success-bg', 'brand-success-text'],
    },
];

const BUTTON_KEYS = [
    'brand-button-primary-bg',
    'brand-button-primary-text',
    'brand-button-primary-hover-bg',
    'brand-button-secondary-bg',
    'brand-button-secondary-text',
    'brand-button-secondary-hover-bg',
    'brand-button-bg',
    'brand-button-text',
    'brand-button-hover-bg',
    'brand-button-hover-text',
];

const TABLE_KEYS = [
    'brand-table-header-bg',
    'brand-table-header-text',
    'brand-table-row-bg',
    'brand-table-row-alt-bg',
    'brand-table-row-hover-bg',
    'brand-table-row-selected-bg',
    'brand-table-footer-bg',
    'brand-table-border',
    'brand-table-text',
];

export default function Colors({ effective, meta, urls }) {
    const form = useAppearanceForm(effective, urls);

    const setTheme = (name, value) => {
        form.setData('theme', { ...form.data.theme, [name]: value });
    };
    const setButton = (name, value) => {
        form.setData('buttons', { ...form.data.buttons, [name]: value });
        form.setData('theme', { ...form.data.theme, [name]: value });
    };
    const setTable = (name, value) => {
        form.setData('tables', { ...form.data.tables, [name]: value });
        form.setData('theme', { ...form.data.theme, [name]: value });
    };

    return (
        <DeveloperLayout title="Colors & Tokens">
            <Head title="Colors & Tokens" />
            <PublishBar meta={meta} urls={urls} />

            <form
                className="space-y-4"
                onSubmit={(e) => {
                    e.preventDefault();
                    saveDraft(form, urls);
                }}
            >
                {THEME_GROUPS.map((group) => (
                    <Card key={group.title}>
                        <CardHeader>
                            <CardTitle>{group.title}</CardTitle>
                        </CardHeader>
                        <CardContent className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            {group.keys.map((key) => (
                                <ColorField
                                    key={key}
                                    label={key}
                                    name={key}
                                    value={form.data.theme[key]}
                                    onChange={setTheme}
                                />
                            ))}
                        </CardContent>
                    </Card>
                ))}

                <Card>
                    <CardHeader>
                        <CardTitle>Buttons</CardTitle>
                    </CardHeader>
                    <CardContent className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {BUTTON_KEYS.map((key) => (
                            <ColorField
                                key={key}
                                label={key}
                                name={key}
                                value={form.data.buttons[key] || form.data.theme[key]}
                                onChange={setButton}
                            />
                        ))}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Tables</CardTitle>
                    </CardHeader>
                    <CardContent className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {TABLE_KEYS.map((key) => (
                            <ColorField
                                key={key}
                                label={key}
                                name={key}
                                value={form.data.tables[key] || form.data.theme[key]}
                                onChange={setTable}
                            />
                        ))}
                    </CardContent>
                </Card>

                <div className="rounded-lg border p-4">
                    <p className="mb-3 text-sm font-medium">Live preview</p>
                    <div className="flex flex-wrap gap-2">
                        <button
                            type="button"
                            className="rounded-md px-4 py-2 text-sm"
                            style={{
                                background: form.data.buttons['brand-button-primary-bg'] || form.data.theme['brand-button-primary-bg'],
                                color: form.data.buttons['brand-button-primary-text'] || form.data.theme['brand-button-primary-text'],
                            }}
                        >
                            Primary
                        </button>
                        <button
                            type="button"
                            className="rounded-md px-4 py-2 text-sm"
                            style={{
                                background: form.data.buttons['brand-button-secondary-bg'] || form.data.theme['brand-button-secondary-bg'],
                                color: form.data.buttons['brand-button-secondary-text'] || form.data.theme['brand-button-secondary-text'],
                            }}
                        >
                            Secondary
                        </button>
                    </div>
                    <table className="mt-4 w-full overflow-hidden rounded-md text-sm" style={{ border: `1px solid ${form.data.tables['brand-table-border'] || '#e5e7eb'}` }}>
                        <thead>
                            <tr style={{ background: form.data.tables['brand-table-header-bg'], color: form.data.tables['brand-table-header-text'] }}>
                                <th className="px-3 py-2 text-left">Title</th>
                                <th className="px-3 py-2 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody style={{ color: form.data.tables['brand-table-text'] }}>
                            <tr style={{ background: form.data.tables['brand-table-row-bg'] }}>
                                <td className="px-3 py-2">Sample book</td>
                                <td className="px-3 py-2">Available</td>
                            </tr>
                            <tr style={{ background: form.data.tables['brand-table-row-alt-bg'] }}>
                                <td className="px-3 py-2">Another title</td>
                                <td className="px-3 py-2">Borrowed</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <button
                    type="submit"
                    disabled={form.processing}
                    className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                >
                    Save draft
                </button>
            </form>
        </DeveloperLayout>
    );
}
