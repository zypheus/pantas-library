import { Head, Link } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

export default function Dashboard({ overview }) {
    return (
        <DeveloperLayout title="Overview">
            <Head title="Developer Console" />
            <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <Card>
                    <CardHeader>
                        <CardTitle>Theme status</CardTitle>
                        <CardDescription>Published appearance version</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-2 text-sm">
                        <p><span className="text-muted-foreground">Version:</span> {overview.version}</p>
                        <p><span className="text-muted-foreground">Published:</span> {overview.published_at || 'Not yet'}</p>
                        <p>
                            <span className="text-muted-foreground">Draft:</span>{' '}
                            {overview.has_draft_changes ? 'Unpublished changes' : 'Clean'}
                        </p>
                        <Link href="/developer/branding" className="inline-block text-sm text-primary underline">
                            Edit branding →
                        </Link>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Identity</CardTitle>
                        <CardDescription>Resolved names</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-2 text-sm">
                        <p><span className="text-muted-foreground">School:</span> {overview.school_name}</p>
                        <p><span className="text-muted-foreground">Library:</span> {overview.library_name}</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Health</CardTitle>
                        <CardDescription>Runtime snapshot</CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-2 text-sm">
                        <p><span className="text-muted-foreground">PHP:</span> {overview.php_version}</p>
                        <p><span className="text-muted-foreground">Laravel:</span> {overview.laravel_version}</p>
                        <p><span className="text-muted-foreground">Cache:</span> {overview.cache_driver}</p>
                        <p>
                            <span className="text-muted-foreground">Branding writable:</span>{' '}
                            {overview.storage_writable ? 'Yes' : 'No'}
                        </p>
                        <Link href="/developer/system" className="inline-block text-sm text-primary underline">
                            System tools →
                        </Link>
                    </CardContent>
                </Card>

                <Card className="md:col-span-2 xl:col-span-3">
                    <CardHeader>
                        <CardTitle>Quick actions</CardTitle>
                    </CardHeader>
                    <CardContent className="flex flex-wrap gap-2">
                        <Link href="/developer/colors" className="rounded-md border px-3 py-2 text-sm hover:bg-muted">Colors & tokens</Link>
                        <Link href="/developer/packages" className="rounded-md border px-3 py-2 text-sm hover:bg-muted">Import / export</Link>
                        <Link href="/developer/feature-flags" className="rounded-md border px-3 py-2 text-sm hover:bg-muted">Feature flags</Link>
                        <Link href="/developer/design-system" className="rounded-md border px-3 py-2 text-sm hover:bg-muted">Design system</Link>
                        <a href="/branding/theme.css" target="_blank" rel="noreferrer" className="rounded-md border px-3 py-2 text-sm hover:bg-muted">
                            Live theme.css
                        </a>
                    </CardContent>
                </Card>
            </div>
        </DeveloperLayout>
    );
}
