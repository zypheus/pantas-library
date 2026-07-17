import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

export function CatalogFilterSidebar({ programs = [], filters = {} }) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>Filters</CardTitle>
            </CardHeader>
            <CardContent className="text-sm text-muted-foreground">
                {programs.length} program(s) available.
                {filters.q ? (
                    <p className="mt-2">Query: {filters.q}</p>
                ) : null}
            </CardContent>
        </Card>
    );
}

export function CatalogResultsSummary({ books }) {
    return (
        <p className="text-sm text-muted-foreground">
            Showing {books?.total ?? 0} title(s)
        </p>
    );
}

export function CatalogEmptyState() {
    return (
        <Card>
            <CardContent className="py-10 text-center text-sm text-muted-foreground">
                Search the catalog using the filters to see results.
            </CardContent>
        </Card>
    );
}
