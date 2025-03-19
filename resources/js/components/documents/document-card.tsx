import { Badge } from "@/components/ui/badge";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/components/ui/card";
import { formatBytes, formatDate } from "@/lib/utils";
import { FileText } from "lucide-react";

interface DocumentCardProps {
    id: number;
    name: string;
    description?: string | null;
    size: string;
    created_at: string;
    // @ts-ignore
    analysis_status: App.Enums.DocumentAnalysisStatus;
    thumbnail_url?: string;
}

export function DocumentCard({
    name,
    description,
    size,
    created_at,
    analysis_status,
    thumbnail_url,
}: DocumentCardProps) {
    return (
        <Card className="group relative overflow-hidden transition-all hover:shadow-md">
            <CardHeader className="p-0">
                <div className="relative aspect-square w-full overflow-hidden bg-muted">
                    {thumbnail_url ? (
                        <img
                            src={thumbnail_url}
                            alt={name}
                            className="h-full w-full object-cover transition-transform group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <FileText className="h-8 w-8 text-muted-foreground" />
                        </div>
                    )}
                </div>
            </CardHeader>
            <CardContent className="p-3">
                <h3 className="line-clamp-1 font-semibold text-base">{name}</h3>
                {description && (
                    <p className="mt-1 line-clamp-1 text-muted-foreground text-xs">
                        {description}
                    </p>
                )}
                <div className="mt-2 flex items-center justify-between text-muted-foreground text-xs">
                    <div className="flex items-center gap-2">
                        <Badge
                            variant="secondary"
                            className={`${
                                analysis_status === "Completed"
                                    ? "bg-green-500/10 text-green-500 hover:bg-green-500/20"
                                    : analysis_status === "In Progress"
                                      ? "bg-blue-500/10 text-blue-500 hover:bg-blue-500/20"
                                      : analysis_status === "Failed"
                                        ? "bg-red-500/10 text-red-500 hover:bg-red-500/20"
                                        : "bg-gray-500/10 text-gray-500 hover:bg-gray-500/20"
                            }`}
                        >
                            {analysis_status}
                        </Badge>
                    </div>
                    <div className="flex items-center gap-2">
                        <span>{formatBytes(Number.parseInt(size))}</span>
                        <span>{formatDate(created_at)}</span>
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
