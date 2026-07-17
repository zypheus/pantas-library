import { Head } from '@inertiajs/react';
import DeveloperLayout from '@/Layouts/DeveloperLayout';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

export default function DesignSystem({ tokens, branding }) {
    return (
        <DeveloperLayout title="Design System">
            <Head title="Design System" />

            <div className="space-y-4">
                <Alert>
                    <AlertDescription>
                        Preview of published tokens for {branding?.library_name || 'library'}.
                        Draft changes appear here only after publish.
                    </AlertDescription>
                </Alert>

                <Card>
                    <CardHeader>
                        <CardTitle>Buttons</CardTitle>
                    </CardHeader>
                    <CardContent className="flex flex-wrap gap-2">
                        <button className="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground">Primary</button>
                        <button className="rounded-md bg-secondary px-4 py-2 text-sm text-secondary-foreground">Secondary</button>
                        <button className="rounded-md border px-4 py-2 text-sm">Outline</button>
                        <button className="rounded-md bg-destructive px-4 py-2 text-sm text-white">Danger</button>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Table</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Title</TableHead>
                                    <TableHead>Author</TableHead>
                                    <TableHead>Status</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow>
                                    <TableCell>Introduction to Cataloging</TableCell>
                                    <TableCell>Sample Author</TableCell>
                                    <TableCell>Available</TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell>Library Systems</TableCell>
                                    <TableCell>Another Author</TableCell>
                                    <TableCell>Borrowed</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Token dump</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                            {Object.entries(tokens || {}).slice(0, 36).map(([key, value]) => (
                                <div key={key} className="flex items-center gap-2 rounded border p-2 text-xs">
                                    {String(value).startsWith('#') && (
                                        <span
                                            className="size-5 shrink-0 rounded border"
                                            style={{ background: value }}
                                        />
                                    )}
                                    <div className="min-w-0">
                                        <p className="truncate font-medium">{key}</p>
                                        <p className="truncate text-muted-foreground">{String(value)}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </DeveloperLayout>
    );
}
