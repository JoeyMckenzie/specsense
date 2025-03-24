import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger,
} from "@/components/ui/tooltip";
import {
    Calendar,
    ClipboardList,
    Clock,
    DollarSign,
    FileCheck,
    FileText,
    Hash,
    MapPin,
    Target,
    TextSearch,
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

            <Card>
                <CardHeader className="flex flex-row items-center justify-between">
                    <CardTitle className="font-medium text-sm">
                        Summary
                    </CardTitle>
                    <TextSearch className="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <p className="text-muted-foreground text-sm">
                        {analysis.documentSummary || "Not specified"}
                    </p>
                </CardContent>
            </Card>

            <div className="grid gap-4 sm:grid-cols-4">
                {details.map((detail) => (
                    <Card key={detail.label}>
                        <CardHeader className="flex flex-row items-center justify-between space-y-0">
                            <CardTitle>{detail.label}</CardTitle>
                            <TooltipProvider>
                                <Tooltip>
                                    <TooltipTrigger asChild>
                                        <Button variant="outline">
                                            <detail.icon className="h-4 w-4 text-muted-foreground" />
                                        </Button>
                                    </TooltipTrigger>
                                    <TooltipContent>
                                        {detail.description}
                                    </TooltipContent>
                                </Tooltip>
                            </TooltipProvider>
                        </CardHeader>
                        <CardContent>
                            <div className="text-sm">
                                {detail.value || "Not specified"}
                            </div>
                        </CardContent>
                    </Card>
                ))}
            </div>

            {analysis.workScopes && analysis.workScopes.length > 0 && (
                <div className="space-y-4">
                    <div>
                        <h2 className="font-semibold text-lg">Work Scopes</h2>
                        <p className="text-muted-foreground text-sm">
                            Identified work scopes and their analysis
                        </p>
                    </div>

                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        {analysis.workScopes.map((workScope) => (
                            <Card key={workScope.id}>
                                <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                                    <CardTitle className="font-medium text-sm">
                                        {workScope.name}
                                    </CardTitle>
                                    <ClipboardList className="h-4 w-4 text-muted-foreground" />
                                </CardHeader>
                                <CardContent>
                                    <p className="whitespace-pre-wrap text-muted-foreground text-sm">
                                        {workScope.analysis ||
                                            "No analysis available"}
                                    </p>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}
