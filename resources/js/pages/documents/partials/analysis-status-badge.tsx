import { Badge } from "@/components/ui/badge";

interface AnalysisStatusBadgeProps {
    status?: string;
}

export function AnalysisStatusBadge({ status }: AnalysisStatusBadgeProps) {
    return (
        <Badge
            variant="secondary"
            className={`${
                status === "Completed"
                    ? "bg-green-500/10 text-green-500 hover:bg-green-500/20"
                    : status === "In Progress"
                      ? "bg-blue-500/10 text-blue-500 hover:bg-blue-500/20"
                      : status === "Failed"
                        ? "bg-red-500/10 text-red-500 hover:bg-red-500/20"
                        : "bg-gray-500/10 text-gray-500 hover:bg-gray-500/20"
            }`}
        >
            {status ?? "Not Started"}
        </Badge>
    );
}
