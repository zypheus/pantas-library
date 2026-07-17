import { Head } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { PublishBar, useAppearanceForm, saveDraft } from './components/AppearanceHelpers';

const FONT_OPTIONS = [
    { value: "'Poppins', ui-sans-serif, system-ui, sans-serif", label: 'Poppins' },
    { value: "'Figtree', ui-sans-serif, system-ui, sans-serif", label: 'Figtree' },
    { value: "'Martel Sans', ui-sans-serif, system-ui, sans-serif", label: 'Martel Sans' },
    { value: "ui-sans-serif, system-ui, sans-serif", label: 'System UI' },
];

const MONO_OPTIONS = [
    { value: "ui-monospace, 'Cascadia Code', Consolas, monospace", label: 'System Mono' },
    { value: "'Cascadia Code', Consolas, monospace", label: 'Cascadia Code' },
];

export default function Typography({ effective, meta, urls }) {
    const form = useAppearanceForm(effective, urls);

    const setFont = (key, value) => {
        form.setData('theme', { ...form.data.theme, [key]: value });
    };

    return (
        <DeveloperLayout title="Typography">
            <Head title="Typography" />
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
                        <CardTitle>Font families</CardTitle>
                    </CardHeader>
                    <CardContent className="grid gap-4 md:grid-cols-3">
                        <div className="space-y-1.5">
                            <Label>Body</Label>
                            <select
                                className="h-9 w-full rounded-md border bg-background px-2 text-sm"
                                value={form.data.theme['brand-font-family'] || FONT_OPTIONS[0].value}
                                onChange={(e) => setFont('brand-font-family', e.target.value)}
                            >
                                {FONT_OPTIONS.map((opt) => (
                                    <option key={opt.label} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                        </div>
                        <div className="space-y-1.5">
                            <Label>Headings</Label>
                            <select
                                className="h-9 w-full rounded-md border bg-background px-2 text-sm"
                                value={form.data.theme['brand-font-family-heading'] || FONT_OPTIONS[0].value}
                                onChange={(e) => setFont('brand-font-family-heading', e.target.value)}
                            >
                                {FONT_OPTIONS.map((opt) => (
                                    <option key={opt.label} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                        </div>
                        <div className="space-y-1.5">
                            <Label>Monospace</Label>
                            <select
                                className="h-9 w-full rounded-md border bg-background px-2 text-sm"
                                value={form.data.theme['brand-font-family-mono'] || MONO_OPTIONS[0].value}
                                onChange={(e) => setFont('brand-font-family-mono', e.target.value)}
                            >
                                {MONO_OPTIONS.map((opt) => (
                                    <option key={opt.label} value={opt.value}>{opt.label}</option>
                                ))}
                            </select>
                        </div>
                    </CardContent>
                </Card>

                <div
                    className="rounded-lg border bg-background p-6"
                    style={{ fontFamily: form.data.theme['brand-font-family'] }}
                >
                    <h2
                        className="text-2xl font-semibold"
                        style={{ fontFamily: form.data.theme['brand-font-family-heading'] }}
                    >
                        Heading preview
                    </h2>
                    <p className="mt-2 text-sm text-muted-foreground">
                        Body text uses the selected sans-serif stack for forms, tables, and navigation.
                    </p>
                    <code
                        className="mt-3 block rounded bg-muted px-2 py-1 text-xs"
                        style={{ fontFamily: form.data.theme['brand-font-family-mono'] }}
                    >
                        barcode / accession: ACC-000123
                    </code>
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
