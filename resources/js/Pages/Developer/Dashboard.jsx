import { Head, Link } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Button } from '@/components/ui/button';
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
                        <Button variant="outline" size="sm" asChild>
                            <Link href="/developer/colors">Colors & tokens</Link>
                        </Button>
                        <Button variant="outline" size="sm" asChild>
                            <Link href="/developer/packages">Import / export</Link>
                        </Button>
                        <Button variant="outline" size="sm" asChild>
                            <Link href="/developer/feature-flags">Feature flags</Link>
                        </Button>
                        <Button variant="outline" size="sm" asChild>
                            <Link href="/developer/design-system">Design system</Link>
                        </Button>
                        <Button variant="outline" size="sm" asChild>
                            <a href="/branding/theme.css" target="_blank" rel="noreferrer">
                                Live theme.css
                            </a>
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </DeveloperLayout>
    );
}
