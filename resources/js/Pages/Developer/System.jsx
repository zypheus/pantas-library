import { Head, router } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

export default function System({ health }) {
    return (
        <DeveloperLayout title="System">
            <Head title="System" />

            <div className="grid gap-4 lg:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Health</CardTitle>
                        <CardDescription>Masked environment info for developers.</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-2 text-sm">
                        <Row label="PHP" value={health.php_version} />
                        <Row label="Laravel" value={health.laravel_version} />
                        <Row label="APP_ENV" value={health.app_env} />
                        <Row label="APP_DEBUG" value={health.app_debug ? 'true' : 'false'} />
                        <Row label="Cache driver" value={health.cache_driver} />
                        <Row label="Queue" value={health.queue_connection} />
                        <Row label="Storage writable" value={health.storage_writable ? 'yes' : 'no'} />
                        <Row label="Branding dir writable" value={health.branding_dir_writable ? 'yes' : 'no'} />
                        <Row label="Theme ETag" value={health.theme_etag} />
                        <Row label="Branding version" value={String(health.branding_version)} />
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Cache</CardTitle>
                        <CardDescription>
                            Clears branding resolver cache, theme CSS cache, views, and config.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <button
                            type="button"
                            className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground"
                            onClick={() => {
                                if (confirm('Clear branding and application caches?')) {
                                    router.post('/developer/system/clear-caches');
                                }
                            }}
                        >
                            Clear caches
                        </button>
                        <p className="mt-3 text-xs text-muted-foreground">
                            Does not expose secrets. Does not restart PHP-FPM or queues.
                        </p>
                    </CardContent>
                </Card>
            </div>
        </DeveloperLayout>
    );
}

function Row({ label, value }) {
    return (
        <div className="flex justify-between gap-4 border-b py-1.5 last:border-0">
            <span className="text-muted-foreground">{label}</span>
            <span className="truncate font-mono text-xs">{value}</span>
        </div>
    );
}
