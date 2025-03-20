import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { formatBytes, formatDate } from "@/lib/utils";
import { FileText } from "lucide-react";

export function DocumentCard({
    document,
}: { document: App.Data.DocumentSummaryData }) {
    return (
        <Card className="group relative overflow-hidden p-0 transition-all hover:shadow-md">
            <div className="-mt-[1px] relative h-48 w-full overflow-hidden bg-muted">
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
            <CardContent className="p-3">
                <h3 className="line-clamp-1 font-semibold text-base">
                    {document.name}
                </h3>
                {document.description && (
                    <p className="mt-1 line-clamp-1 text-muted-foreground text-xs">
                        {document.description}
                    </p>
                )}
                <div className="mt-2 flex items-center justify-between text-muted-foreground text-xs">
                    <div className="flex items-center gap-2">
                        {/*<Badge*/}
                        {/*    variant="secondary"*/}
                        {/*    className={`${*/}
                        {/*        analysis_status === "Completed"*/}
                        {/*            ? "bg-green-500/10 text-green-500 hover:bg-green-500/20"*/}
                        {/*            : analysis_status === "In Progress"*/}
                        {/*                ? "bg-blue-500/10 text-blue-500 hover:bg-blue-500/20"*/}
                        {/*                : analysis_status === "Failed"*/}
                        {/*                    ? "bg-red-500/10 text-red-500 hover:bg-red-500/20"*/}
                        {/*                    : "bg-gray-500/10 text-gray-500 hover:bg-gray-500/20"*/}
                        {/*    }`}*/}
                        {/*>*/}
                        {/*    {analysis_status}*/}
                        {/*</Badge>*/}
                    </div>
                    <div className="flex items-center gap-2">
                        <span>{formatBytes(document.size)}</span>
                        <span>{formatDate(document.createdAt)}</span>
                    </div>
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
