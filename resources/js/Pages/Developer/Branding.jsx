import { Head, router } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { PublishBar, useAppearanceForm, saveDraft } from './components/AppearanceHelpers';

const TEXT_FIELDS = [
    ['school_name', 'School name'],
    ['library_name', 'Library name'],
    ['system_name', 'System name'],
    ['staff_portal_subtitle', 'Staff portal subtitle'],
    ['school_home_url', 'School home URL'],
    ['external_resource_url', 'External resource URL'],
];

const ASSET_LABELS = {
    logo: 'Primary logo',
    logo_landscape: 'Landscape logo',
    logo_compact: 'Compact logo',
    favicon: 'Favicon',
    banner: 'Banner',
    partner_logo: 'Partner logo',
    default_book: 'Default book cover',
};

export default function Branding({ effective, meta, urls, assetKeys }) {
    const form = useAppearanceForm(effective, urls);

    return (
        <DeveloperLayout title="Branding">
            <Head title="Branding" />
            <PublishBar meta={meta} urls={urls} />

            <form
                className="space-y-4"
                onSubmit={(e) => {
                    e.preventDefault();
                    saveDraft(form, urls);
                }}
            >
                <Card>
                    <CardHeader>
                        <CardTitle>Identity text</CardTitle>
                    </CardHeader>
                    <CardContent className="grid gap-4 md:grid-cols-2">
                        {TEXT_FIELDS.map(([key, label]) => (
                            <div key={key} className="space-y-1.5">
                                <Label htmlFor={key}>{label}</Label>
                                <Input
                                    id={key}
                                    value={form.data.branding[key] || ''}
                                    onChange={(e) =>
                                        form.setData('branding', {
                                            ...form.data.branding,
                                            [key]: e.target.value,
                                        })
                                    }
                                />
                            </div>
                        ))}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Assets</CardTitle>
                    </CardHeader>
                    <CardContent className="grid gap-6 md:grid-cols-2">
                        {(assetKeys || Object.keys(ASSET_LABELS)).map((key) => (
                            <div key={key} className="space-y-2 rounded-lg border p-3">
                                <p className="text-sm font-medium">{ASSET_LABELS[key] || key}</p>
                                <p className="truncate text-xs text-muted-foreground">
                                    {form.data.branding[key] || 'Using config default'}
                                </p>
                                {form.data.branding[key] && (
                                    <img
                                        src={`/${form.data.branding[key]}`}
                                        alt={key}
                                        className="max-h-16 object-contain"
                                    />
                                )}
                                <label className="block">
                                    <span className="text-xs text-muted-foreground">Upload replacement</span>
                                    <input
                                        type="file"
                                        accept="image/*,.svg,.ico"
                                        className="mt-1 block w-full text-sm"
                                        onChange={(e) => {
                                            const file = e.target.files?.[0];
                                            if (!file) return;
                                            const data = new FormData();
                                            data.append('key', key);
                                            data.append('file', file);
                                            router.post(urls.upload, data, {
                                                forceFormData: true,
                                                preserveScroll: true,
                                            });
                                        }}
                                    />
                                </label>
                            </div>
                        ))}
                    </CardContent>
                </Card>

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
