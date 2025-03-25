import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import AppLayout from "@/layouts/app-layout";
import { formatBytes, formatDate } from "@/lib/utils";
import { AddBidItemFormModal } from "@/pages/documents/partials/add-bid-item-form-modal";
import { AnalysisDetailsCard } from "@/pages/documents/partials/analysis-details-card";
import { AnalysisFormModal } from "@/pages/documents/partials/analysis-form-modal";
import { AnalysisStatusBadge } from "@/pages/documents/partials/analysis-status-badge";
import { BidItemsTable } from "@/pages/documents/partials/bid-items-table";
import { DeleteFormModal } from "@/pages/documents/partials/delete-form-modal";
import { DocumentProvider } from "@/pages/documents/partials/document-provider";
import type { BreadcrumbItem, SharedData } from "@/types";
import { Head, Link, usePage } from "@inertiajs/react";
import {
    Activity,
    FileText,
    FileType,
    HardDrive,
    Text,
    Upload,
    User,
} from "lucide-react";

const breadcrumbs = (title: string): BreadcrumbItem[] => [
    {
        title: "Documents",
        href: "/documents",
    },
    {
        title,
        href: "#",
    },
];

export default function Show({
    document,
}: { document: App.Data.DocumentSummaryData }) {
    const { user } = usePage<SharedData>().props.auth;

    return (
        <AppLayout breadcrumbs={breadcrumbs(document.name)}>
            <Head title={`${document.name} | Document Details`} />
            <DocumentProvider currentDocument={document}>
                <div className="container mx-auto p-4">
                    <div className="mb-8 flex flex-col justify-between sm:flex-row sm:items-center">
                        <div className="pb-4 sm:pb-0">
                            <h1 className="font-bold text-2xl tracking-tight">
                                {document.name}
                            </h1>
                            <p className="mt-2 text-muted-foreground text-sm">
                                Uploaded on {formatDate(document.createdAt)}
                            </p>
                        </div>
                        <div className="flex gap-2">
                            <Button variant="outline" asChild>
                                <Link
                                    href={route("documents.edit", document.id)}
                                >
                                    Edit
                                </Link>
                            </Button>
                            <DeleteFormModal />
                            {!document.analysis && <AnalysisFormModal />}
                        </div>
                    </div>

                    <div className="grid gap-6 md:grid-cols-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Document Information</CardTitle>
                                <CardDescription>
                                    Basic details about the document
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="flex items-center gap-4">
                                    <FileText className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Filename
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {document.name}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <FileType className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Document Type
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {document.type}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <HardDrive className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            File Size
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {formatBytes(document.size)}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <Text className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Description
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {document.description ??
                                                "No description"}
                                        </p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <CardTitle>Upload Details</CardTitle>
                                <CardDescription>
                                    Information about the upload
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-4">
                                <div className="flex items-center gap-4">
                                    <User className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Uploaded By
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {user.fullName}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <Upload className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Upload Date
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {formatDate(document.createdAt)}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <FileText className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Last Updated
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            {formatDate(document.updatedAt)}
                                        </p>
                                    </div>
                                </div>
                                <div className="flex items-center gap-4">
                                    <Activity className="h-5 w-5 text-muted-foreground" />
                                    <div>
                                        <p className="font-medium text-sm">
                                            Analysis Status
                                        </p>
                                        <p className="text-muted-foreground text-sm">
                                            <AnalysisStatusBadge />
                                        </p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        {document.analysis && <AnalysisDetailsCard />}

                        {document.analysis?.bidItems && (
                            <Card className="md:col-span-2">
                                <CardHeader className="flex flex-row items-center justify-between">
                                    <div className="space-y-0 md:flex-row md:items-center md:space-x-2 md:space-y-1">
                                        <CardTitle>Bid Items</CardTitle>
                                        <CardDescription>
                                            List of bid items extracted from the
                                            document
                                        </CardDescription>
                                    </div>
                                    <AddBidItemFormModal />
                                </CardHeader>
                                <CardContent>
                                    <BidItemsTable />
                                </CardContent>
                            </Card>
                        )}
                    </div>
                </div>
            </DocumentProvider>
        </AppLayout>
    );
}
