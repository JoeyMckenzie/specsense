import {
    Card,
    CardContent,
    CardFooter,
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
            description: "The unique identifier for the contract",
        },
        {
            icon: Hash,
            label: "Project ID",
            value: analysis.projectId,
            description: "The project's unique identifier",
        },
        {
            icon: DollarSign,
            label: "Engineer's Estimate",
            value: analysis.engineersEstimate,
            description: "The estimated cost of the project",
        },
        {
            icon: Calendar,
            label: "Bid Due Date",
            value: analysis.bidDueDate,
            description: "The deadline for bid submission",
        },
        {
            icon: Clock,
            label: "Working Days",
            value: analysis.numberOfWorkingDays,
            description: "The number of working days for the project",
        },
        {
            icon: Target,
            label: "DBE Goal",
            value: analysis.dbeGoal,
            description:
                "The Disadvantaged Business Enterprise participation goal",
        },
        {
            icon: FileCheck,
            label: "DIR Number",
            value: analysis.dirNumber,
            description: "The Department of Industrial Relations number",
        },
        {
            icon: MapPin,
            label: "Job Location",
            value: analysis.jobLocation,
            description: "The physical location of the project",
        },
    ];

    return (
        <div className="space-y-6 md:col-span-2">
            <div>
                <h2 className="font-semibold text-lg">Analysis Details</h2>
                <p className="text-muted-foreground text-sm">
                    Information extracted from the document analysis
                </p>
            </div>
            <div className="grid gap-4 sm:grid-cols-4">
                {details.map((detail) => (
                    <Card key={detail.label}>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle className="font-medium text-sm">
                                {detail.label}
                            </CardTitle>
                            <detail.icon className="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div className="font-bold">
                                {detail.value || "Not specified"}
                            </div>
                        </CardContent>
                        <CardFooter className="text-muted-foreground text-xs">
                            {detail.description}
                        </CardFooter>
                    </Card>
                ))}
            </div>
        </div>
    );
}
