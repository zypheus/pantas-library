import { router, useForm } from '@inertiajs/react';

export function PublishBar({ meta, urls }) {
    return (
        <div className="sticky top-0 z-10 mb-4 flex flex-wrap items-center gap-2 rounded-lg border bg-background/95 p-3 shadow-sm backdrop-blur">
            <div className="mr-auto min-w-[180px]">
                <p className="text-sm font-medium">
                    Version {meta?.version ?? 0}
                    {meta?.has_draft_changes ? (
                        <span className="ml-2 rounded bg-amber-100 px-1.5 py-0.5 text-xs text-amber-800">Draft changes</span>
                    ) : (
                        <span className="ml-2 rounded bg-slate-100 px-1.5 py-0.5 text-xs text-slate-600">In sync</span>
                    )}
                </p>
                <p className="text-xs text-muted-foreground">
                    {meta?.published_at ? `Published ${meta.published_at}` : 'Never published — using config defaults'}
                </p>
            </div>
            <button
                type="button"
                className="rounded-md border px-3 py-1.5 text-sm hover:bg-muted"
                onClick={() => router.post(urls.discard)}
            >
                Discard draft
            </button>
            <button
                type="button"
                className="rounded-md border border-red-200 px-3 py-1.5 text-sm text-red-700 hover:bg-red-50"
                onClick={() => {
                    if (confirm('Reset all appearance settings to configuration defaults and publish?')) {
                        router.post(urls.reset);
                    }
                }}
            >
                Reset defaults
            </button>
            <button
                type="button"
                className="rounded-md bg-primary px-3 py-1.5 text-sm font-medium text-primary-foreground hover:opacity-90"
                onClick={() => {
                    if (confirm('Publish draft? This updates the live theme for all users.')) {
                        router.post(urls.publish);
                    }
                }}
            >
                Publish
            </button>
        </div>
    );
}

export function ColorField({ label, name, value, onChange }) {
    return (
        <label className="block space-y-1">
            <span className="text-xs font-medium text-muted-foreground">{label}</span>
            <div className="flex items-center gap-2">
                <input
                    type="color"
                    className="h-9 w-12 cursor-pointer rounded border bg-transparent p-0.5"
                    value={normalizeHex(value)}
                    onChange={(e) => onChange(name, e.target.value)}
                />
                <input
                    type="text"
                    className="h-9 flex-1 rounded-md border bg-background px-2 text-sm"
                    value={value || ''}
                    onChange={(e) => onChange(name, e.target.value)}
                    placeholder="#000000"
                />
            </div>
        </label>
    );
}

function normalizeHex(value) {
    if (!value || typeof value !== 'string') return '#000000';
    if (/^#[0-9a-fA-F]{6}$/.test(value)) return value;
    if (/^#[0-9a-fA-F]{3}$/.test(value)) {
        const r = value[1];
        const g = value[2];
        const b = value[3];
        return `#${r}${r}${g}${g}${b}${b}`;
    }
    return '#000000';
}

export function useAppearanceForm(effective, urls) {
    return useForm({
        branding: { ...(effective?.branding || {}) },
        landing_page: { ...(effective?.landing_page || {}) },
        buttons: { ...(effective?.buttons || {}) },
        tables: { ...(effective?.tables || {}) },
        theme: { ...(effective?.theme || {}) },
        feature_flags: { ...(effective?.feature_flags || {}) },
    });
}

export function saveDraft(form, urls) {
    form.post(urls.saveDraft, { preserveScroll: true });
}
