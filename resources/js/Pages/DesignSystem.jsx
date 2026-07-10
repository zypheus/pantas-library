import { Head } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

const swatches = [
    { name: 'Brand blue', token: '--brand-blue', role: 'Primary actions' },
    { name: 'Brand green', token: '--brand-green', role: 'Secondary / success' },
    { name: 'Brand gold', token: '--brand-gold', role: 'Accent highlights' },
    { name: 'Brand maroon', token: '--brand-maroon', role: 'Destructive emphasis' },
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
                            Colors come from <code className="rounded bg-primary-foreground/10 px-1">public/branding/branding.css</code>{' '}
                            and <code className="rounded bg-primary-foreground/10 px-1">resources/css/brand-tokens.css</code>.
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
