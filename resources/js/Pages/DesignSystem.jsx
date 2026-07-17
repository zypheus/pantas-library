import { Head } from '@inertiajs/react';

/** Public / local design-system stub (README). Prefer /developer/design-system when signed in. */
export default function DesignSystem() {
    return (
        <div className="min-h-screen bg-background p-8 text-foreground">
            <Head title="Design System" />
            <h1 className="text-2xl font-semibold">Design System</h1>
            <p className="mt-2 text-muted-foreground">
                Sign in as a developer and open <code>/developer/design-system</code> for the full token gallery.
            </p>
            <div className="mt-6 flex flex-wrap gap-2">
                <button className="rounded-md bg-primary px-4 py-2 text-sm text-primary-foreground">Primary</button>
                <button className="rounded-md bg-secondary px-4 py-2 text-sm text-secondary-foreground">Secondary</button>
            </div>
        </div>
    );
}
