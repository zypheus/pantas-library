import { Head } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { PublishBar, useAppearanceForm, saveDraft } from './components/AppearanceHelpers';

const FLAG_META = {
    maintenance_mode: 'Show a branded maintenance page to public visitors.',
    show_staging_banner: 'Display a staging environment ribbon.',
    opac_room_booking_link: 'Show room booking link on OPAC landing.',
    kiosk_logout_feedback: 'Prompt for feedback on gate logout.',
    experimental_inertia_ui: 'Prefer experimental Inertia pages where available.',
};

export default function FeatureFlags({ effective, meta, urls }) {
    const form = useAppearanceForm(effective, urls);
    const flags = form.data.feature_flags || {};

    return (
        <DeveloperLayout title="Feature Flags">
            <Head title="Feature Flags" />
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
                        <CardTitle>Toggles</CardTitle>
                        <CardDescription>
                            Non-secret runtime flags. Secrets stay in .env.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        {Object.keys(FLAG_META).map((flag) => (
                            <label
                                key={flag}
                                className="flex items-start gap-3 rounded-lg border p-3"
                            >
                                <input
                                    type="checkbox"
                                    className="mt-1"
                                    checked={Boolean(flags[flag])}
                                    onChange={(e) =>
                                        form.setData('feature_flags', {
                                            ...flags,
                                            [flag]: e.target.checked,
                                        })
                                    }
                                />
                                <span>
                                    <span className="block text-sm font-medium">{flag}</span>
                                    <span className="text-xs text-muted-foreground">{FLAG_META[flag]}</span>
                                </span>
                            </label>
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
