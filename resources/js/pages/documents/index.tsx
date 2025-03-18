import { DocumentCard } from "@/components/documents/document-card";
import { Button } from "@/components/ui/button";
import AppLayout from "@/layouts/app-layout";
import type { BreadcrumbItem } from "@/types";
import { Head } from "@inertiajs/react";
import { Plus } from "lucide-react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Documents",
        href: "/documents",
    },
];

export default function Index() {
    // TODO: Replace with actual data from the backend
    const documents = [
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
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h1 className="font-semibold text-2xl">Your Documents</h1>
                    <Button>
                        <Plus className="mr-2 h-4 w-4" />
                        Upload Document
                    </Button>
                </div>
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {documents.map((document) => (
                        <DocumentCard key={document.id} {...document} />
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
