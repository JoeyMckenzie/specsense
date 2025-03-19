import { DocumentCard } from "@/components/documents/document-card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import type { BreadcrumbItem } from "@/types";
import { Head, Link } from "@inertiajs/react";
import { FileText, Upload } from "lucide-react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Documents",
        href: "/documents",
    },
];

export default function Index() {
    // TODO: Replace with actual data from the backend
    const documents1 = [];

    // TODO: Replace with actual data from the backend
    const documents2 = [
        {
            id: 1,
            name: "Project A Special Provisions",
            description:
                "Special provisions document for Project A construction",
            size: "2500000",
            created_at: "2024-03-20T10:00:00Z",
            analysis_status: "Completed",
            thumbnail_url: undefined,
        },
        {
            id: 2,
            name: "Project B Requirements",
            description: "Construction requirements and specifications",
            size: "1500000",
            created_at: "2024-03-19T15:30:00Z",
            analysis_status: "In Progress",
            thumbnail_url: undefined,
        },
        {
            id: 3,
            name: "Project C Documents",
            description: "Project documentation and specifications",
            size: "3500000",
            created_at: "2024-03-18T09:15:00Z",
            analysis_status: "Not Started",
            thumbnail_url: undefined,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Documents" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                {documents1.length > 0 ? (
                    <>
                        <div className="flex items-center justify-between">
                            <h1 className="font-semibold text-2xl">
                                Your Documents
                            </h1>
                            <Button>
                                <Upload className="mr-2 h-4 w-4" />
                                Upload Document
                            </Button>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {documents1.map((document) => (
                                <DocumentCard key={document.id} {...document} />
                            ))}
                        </div>
                    </>
                ) : (
                    <div className="flex h-full flex-1 flex-col items-center justify-center gap-6 rounded-xl border border-dashed p-8 text-center">
                        <div className="rounded-full bg-primary/10 p-4">
                            <FileText className="h-8 w-8 text-primary" />
                        </div>
                        <div className="space-y-2">
                            <h2 className="font-semibold text-xl">
                                No documents yet
                            </h2>
                            <p className="text-muted-foreground text-sm">
                                Upload your first document to get started with
                                document analysis
                            </p>
                        </div>
                        <Link href={route("documents.create")}>
                            <Button
                                size="lg"
                                className="gap-2"
                                variant="outline"
                            >
                                <Upload className="h-4 w-4" />
                                Upload Document
                            </Button>
                        </Link>
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
