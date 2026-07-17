import { Head } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { PublishBar, useAppearanceForm, saveDraft } from './components/AppearanceHelpers';

const FIELDS = [
    ['hero_kicker', 'Hero kicker'],
    ['hero_heading', 'Hero heading'],
    ['hero_subtitle', 'Hero subtitle'],
    ['search_placeholder', 'Search placeholder'],
    ['search_button_label', 'Search button label'],
    ['helper_text', 'Helper text'],
    ['new_arrivals_title', 'New arrivals title'],
    ['new_arrivals_description', 'New arrivals description'],
    ['external_links_title', 'External links title'],
    ['external_links_description', 'External links description'],
];

export default function Landing({ effective, meta, urls }) {
    const form = useAppearanceForm(effective, urls);
    const landing = form.data.landing_page || {};
    const visible = landing.sections_visible || {};

    const setLanding = (key, value) => {
        form.setData('landing_page', { ...landing, [key]: value });
    };

    return (
        <DeveloperLayout title="OPAC Landing">
            <Head title="OPAC Landing" />
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
                        <CardTitle>Copy</CardTitle>
                    </CardHeader>
                    <CardContent className="grid gap-4 md:grid-cols-2">
                        {FIELDS.map(([key, label]) => (
                            <div key={key} className="space-y-1.5">
                                <Label htmlFor={key}>{label}</Label>
                                <Input
                                    id={key}
                                    value={landing[key] || ''}
                                    onChange={(e) => setLanding(key, e.target.value)}
                                />
                            </div>
                        ))}
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Section visibility</CardTitle>
                    </CardHeader>
                    <CardContent className="flex flex-wrap gap-4">
                        {['hero', 'new_arrivals', 'external_links'].map((section) => (
                            <label key={section} className="flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    checked={visible[section] !== false}
                                    onChange={(e) =>
                                        setLanding('sections_visible', {
                                            ...visible,
                                            [section]: e.target.checked,
                                        })
                                    }
                                />
                                {section.replace('_', ' ')}
                            </label>
                        ))}
                    </CardContent>
                </Card>

                <div className="rounded-lg border bg-slate-50 p-6">
                    <p className="text-xs uppercase tracking-wide text-muted-foreground">
                        {landing.hero_kicker}
                    </p>
                    <h2 className="mt-1 text-2xl font-semibold">{landing.hero_heading}</h2>
                    <p className="mt-2 text-sm text-muted-foreground">{landing.hero_subtitle}</p>
                    <div className="mt-4 flex gap-2">
                        <input
                            readOnly
                            className="flex-1 rounded-md border px-3 py-2 text-sm"
                            placeholder={landing.search_placeholder}
                        />
                        <button type="button" className="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground">
                            {landing.search_button_label}
                        </button>
                    </div>
                    <p className="mt-2 text-xs text-muted-foreground">{landing.helper_text}</p>
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
