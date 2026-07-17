import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { CheckCircle2, AlertCircle } from 'lucide-react';

export function ShellFlashMessages({ flash }) {
    if (!flash?.success && !flash?.error) {
        return null;
    }

    return (
        <div className="space-y-2">
            {flash.success ? (
                <Alert className="border-green-200 bg-green-50 text-green-900 dark:border-green-900 dark:bg-green-950 dark:text-green-100">
                    <CheckCircle2 className="text-green-600" />
                    <AlertTitle>Success</AlertTitle>
                    <AlertDescription>{flash.success}</AlertDescription>
                </Alert>
            ) : null}
            {flash.error ? (
                <Alert variant="destructive">
                    <AlertCircle />
                    <AlertTitle>Error</AlertTitle>
                    <AlertDescription>{flash.error}</AlertDescription>
                </Alert>
            ) : null}
        </div>
    );
}
