import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import { Card, CardContent } from '@/components/ui/card';

export function CatalogResultsTable({ books }) {
    const rows = books?.data ?? [];

    return (
        <Card>
            <CardContent className="p-0">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Title</TableHead>
                            <TableHead>Author</TableHead>
                            <TableHead>Call no.</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        {rows.length === 0 ? (
                            <TableRow>
                                <TableCell colSpan={3} className="text-muted-foreground">
                                    No results.
                                </TableCell>
                            </TableRow>
                        ) : (
                            rows.map((book) => (
                                <TableRow key={book.id}>
                                    <TableCell>{book.title_statement ?? book.title ?? '—'}</TableCell>
                                    <TableCell>{book.main_creator ?? book.author ?? '—'}</TableCell>
                                    <TableCell>{book.call_number ?? '—'}</TableCell>
                                </TableRow>
                            ))
                        )}
                    </TableBody>
                </Table>
            </CardContent>
        </Card>
    );
}
