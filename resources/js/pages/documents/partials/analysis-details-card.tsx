import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import {
    Calendar,
    Clock,
    DollarSign,
    FileCheck,
    FileText,
    Hash,
    MapPin,
    Target,
} from "lucide-react";

export function AnalysisDetailsCard({
    analysis,
}: { analysis: App.Data.DocumentAnalysisSummaryData }) {
    const details = [
        {
            icon: FileText,
            label: "Contract Number",
            value: analysis.contractNumber,
        },
        {
            icon: Hash,
            label: "Project ID",
            value: analysis.projectId,
        },
        {
            icon: DollarSign,
            label: "Engineer's Estimate",
            value: analysis.engineersEstimate,
        },
        {
            icon: Calendar,
            label: "Bid Due Date",
            value: analysis.bidDueDate,
        },
        {
            icon: Clock,
            label: "Working Days",
            value: analysis.numberOfWorkingDays,
        },
        {
            icon: Target,
            label: "DBE Goal",
            value: analysis.dbeGoal,
        },
        {
            icon: FileCheck,
            label: "DIR Number",
            value: analysis.dirNumber,
        },
        {
            icon: MapPin,
            label: "Job Location",
            value: analysis.jobLocation,
        },
    ];

    return (
        <Card>
            <CardHeader>
                <CardTitle>Analysis Details</CardTitle>
                <CardDescription>
                    Information extracted from the document analysis
                </CardDescription>
            </CardHeader>
            <CardContent className="grid gap-4 sm:grid-cols-2">
                {details.map((detail) => (
                    <div key={detail.label} className="flex items-center gap-4">
                        <detail.icon className="h-5 w-5 text-muted-foreground" />
                        <div>
                            <p className="font-medium text-sm">
                                {detail.label}
                            </p>
                            <p className="text-muted-foreground text-sm">
                                {detail.value || "Not specified"}
                            </p>
                        </div>
                    </div>
                ))}
            </CardContent>
        </Card>
    );
}
