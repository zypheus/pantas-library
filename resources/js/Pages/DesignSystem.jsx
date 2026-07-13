import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

const swatches = [
    { name: 'Primary (gold)', token: '--brand-primary', role: 'Highlights, sidebar bar' },
    { name: 'Accent (green)', token: '--brand-accent', role: 'Sidebar background' },
    { name: 'Blue', token: '--brand-blue', role: 'Active nav, buttons' },
    { name: 'Green dark', token: '--brand-green-dark', role: 'Hovers, borders' },
];

export default function DesignSystem({ branding }) {
    const schoolName = branding?.schoolName ?? 'Library';

    return (
        <>
            <Head title="Design system" />

            <div className="min-h-screen bg-muted/40">
                <header className="bg-primary text-primary-foreground">
                    <div className="mx-auto flex max-w-5xl flex-col gap-2 px-6 py-8">
                        <p className="text-sm font-medium uppercase tracking-wider text-primary-foreground/80">
                            {schoolName}
                        </p>
                        <h1 className="text-3xl font-semibold">PANTAS shadcn theme</h1>
                        <p className="max-w-2xl text-primary-foreground/90">
                            All colors are edited in{' '}
                            <code className="rounded bg-primary-foreground/10 px-1">public/branding/branding.css</code>.
                        </p>
                    </div>
                </header>

                <main className="mx-auto max-w-5xl space-y-8 px-6 py-8">
                    <section className="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 className="mb-4 text-lg font-semibold">Brand swatches</h2>
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            {swatches.map((swatch) => (
                                <div key={swatch.token} className="overflow-hidden rounded-lg border">
                                    <div
                                        className="h-20"
                                        style={{ backgroundColor: `var(${swatch.token})` }}
                                    />
                                    <div className="space-y-1 p-3">
                                        <p className="font-medium">{swatch.name}</p>
                                        <p className="text-xs text-muted-foreground">{swatch.role}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>

                    <section className="rounded-xl border bg-card p-6 shadow-sm">
                        <h2 className="mb-4 text-lg font-semibold">Buttons</h2>
                        <div className="flex flex-wrap gap-3">
                            <Button>Primary</Button>
                            <Button variant="secondary">Secondary</Button>
                            <Button variant="outline">Outline</Button>
                            <Button variant="destructive">Destructive</Button>
                        </div>
                    </section>
                </main>

                <footer className="border-t bg-card py-6 text-center text-sm text-muted-foreground">
                    Pantas © {new Date().getFullYear()} · {schoolName}
                </footer>
            </div>
        </>
    );
}
