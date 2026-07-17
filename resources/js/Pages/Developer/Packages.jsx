import { Head, router, useForm } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { PublishBar } from './components/AppearanceHelpers';

export default function Packages({ meta, urls, history, presets }) {
    const importForm = useForm({ bundle: null });

    return (
        <DeveloperLayout title="Theme Packages">
            <Head title="Theme Packages" />
            <PublishBar meta={meta} urls={urls} />

            <div className="grid gap-4 lg:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Export / Import</CardTitle>
                        <CardDescription>Move themes between staging and production.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-4">
                        <div className="flex flex-wrap gap-2">
                            <a
                                href={`${urls.export}`}
                                className="rounded-md border px-3 py-2 text-sm hover:bg-muted"
                            >
                                Export published JSON
                            </a>
                            <a
                                href={`${urls.export}?draft=1`}
                                className="rounded-md border px-3 py-2 text-sm hover:bg-muted"
                            >
                                Export draft JSON
                            </a>
                        </div>

                        <form
                            className="space-y-2"
                            onSubmit={(e) => {
                                e.preventDefault();
                                importForm.post(urls.import, { forceFormData: true });
                            }}
                        >
                            <label className="block text-sm font-medium">Import bundle as draft</label>
                            <input
                                type="file"
                                accept=".json,application/json"
                                className="block w-full text-sm"
                                onChange={(e) => importForm.setData('bundle', e.target.files?.[0] || null)}
                            />
                            <button
                                type="submit"
                                disabled={importForm.processing || !importForm.data.bundle}
                                className="rounded-md bg-primary px-3 py-2 text-sm text-primary-foreground"
                            >
                                Import
                            </button>
                        </form>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Presets</CardTitle>
                        <CardDescription>Apply a starting palette into the draft.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        {Object.entries(presets || {}).map(([key, preset]) => (
                            <div key={key} className="flex items-start justify-between gap-3 rounded-lg border p-3">
                                <div>
                                    <p className="text-sm font-medium">{preset.label}</p>
                                    <p className="text-xs text-muted-foreground">{preset.description}</p>
                                </div>
                                <button
                                    type="button"
                                    className="shrink-0 rounded-md border px-3 py-1.5 text-sm hover:bg-muted"
                                    onClick={() => router.post('/developer/packages/preset', { preset: key })}
                                >
                                    Apply
                                </button>
                            </div>
                        ))}
                    </CardContent>
                </Card>

                <Card className="lg:col-span-2">
                    <CardHeader>
                        <CardTitle>Version history</CardTitle>
                        <CardDescription>One-click rollback republishes the selected version.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        {(history || []).length === 0 ? (
                            <p className="text-sm text-muted-foreground">No published versions yet.</p>
                        ) : (
                            <div className="overflow-x-auto">
                                <table className="w-full text-sm">
                                    <thead>
                                        <tr className="border-b text-left text-muted-foreground">
                                            <th className="py-2 pr-4">Version</th>
                                            <th className="py-2 pr-4">Published</th>
                                            <th className="py-2 pr-4">By</th>
                                            <th className="py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {history.map((row) => (
                                            <tr key={row.id} className="border-b">
                                                <td className="py-2 pr-4">
                                                    v{row.version}
                                                    {row.is_current ? (
                                                        <span className="ml-2 rounded bg-green-100 px-1.5 text-xs text-green-800">current</span>
                                                    ) : null}
                                                </td>
                                                <td className="py-2 pr-4">{row.published_at}</td>
                                                <td className="py-2 pr-4">{row.publisher || '—'}</td>
                                                <td className="py-2">
                                                    {!row.is_current && (
                                                        <button
                                                            type="button"
                                                            className="rounded-md border px-2 py-1 text-xs hover:bg-muted"
                                                            onClick={() => {
                                                                if (confirm(`Rollback to version ${row.version}?`)) {
                                                                    router.post('/developer/packages/rollback', {
                                                                        version: row.version,
                                                                    });
                                                                }
                                                            }}
                                                        >
                                                            Rollback
                                                        </button>
                                                    )}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        )}
                    </CardContent>
                </Card>
            </div>
        </DeveloperLayout>
    );
}
