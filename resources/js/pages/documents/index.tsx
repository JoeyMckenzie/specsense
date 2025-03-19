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

export default function Index({
    documents,
}: { documents: App.Data.DocumentSummaryData[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Documents" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                {documents.length > 0 ? (
                    <>
                        <div className="flex items-center justify-between">
                            <h1 className="font-semibold text-2xl">
                                Your Documents
                            </h1>
                            <Link href={route("documents.create")} as="button">
                                <Button>
                                    <Upload className="mr-2 h-4 w-4" />
                                    Upload Document
                                </Button>
                            </Link>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {documents.map((document) => (
                                <Link
                                    key={document.id}
                                    href={route("documents.show", document.id)}
                                >
                                    <DocumentCard document={document} />
                                </Link>
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
