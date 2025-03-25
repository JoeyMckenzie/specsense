import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { formatBytes, formatDate } from "@/lib/utils";
import { AnalysisStatusBadge } from "@/pages/documents/partials/analysis-status-badge";
import { FileText } from "lucide-react";

export function DocumentCard({
    document,
}: { document: App.Data.DocumentSummaryData }) {
    return (
        <Card className="group relative flex h-full flex-col overflow-hidden p-0 transition-all hover:shadow-md">
            <div className="relative aspect-[4/3] w-full overflow-hidden bg-muted">
                {document.previewImage ? (
                    <img
                        src={document.previewImage}
                        alt={document.name}
                        className="h-full w-full object-cover transition-transform group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center">
                        <FileText className="h-8 w-8 text-muted-foreground" />
                    </div>
                )}
            </div>
            <CardContent className="flex-1 p-3">
                <div className="flex items-center justify-between gap-2">
                    <h3 className="line-clamp-1 flex-1 font-semibold text-base">
                        {document.name}
                    </h3>
                    <AnalysisStatusBadge />
                </div>
                <div className="mt-2 flex items-center justify-between text-muted-foreground text-xs">
                    <span>{formatBytes(document.size)}</span>
                    <span>{formatDate(document.createdAt)}</span>
                </div>
            </CardContent>
            <CardFooter className="p-3 pt-0">
                <button
                    type="button"
                    className="w-full rounded-md bg-primary px-3 py-1.5 font-medium text-primary-foreground text-xs transition-colors hover:bg-primary/90"
                >
                    View Details
                </button>
            </CardFooter>
        </Card>
    );
}
