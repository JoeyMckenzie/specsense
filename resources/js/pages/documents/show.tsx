import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import AppLayout from "@/layouts/app-layout";
import { formatBytes, formatDate } from "@/lib/utils";
import type { BreadcrumbItem, SharedData } from "@/types";
import { Head, Link, useForm, usePage } from "@inertiajs/react";
import { FileText, FileType, HardDrive, Upload, User } from "lucide-react";
import { useState } from "react";

const breadcrumbs = (title: string): BreadcrumbItem[] => [
    {
        title: "Documents",
        href: "/documents",
    },
    {
        title: "Document Details",
        href: "#",
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
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const { delete: destroy, processing } = useForm();

    const handleDelete = () => {
        destroy(route("documents.destroy", document.id), {
            preserveScroll: true,
            onSuccess: () => {
                setIsDeleteDialogOpen(false);
            },
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(document.name)}>
            <Head title={`${document.name} - Document Details`} />
            <div className="container mx-auto py-8">
                <div className="mb-8 flex items-center justify-between">
                    <div>
                        <h1 className="font-bold text-3xl tracking-tight">
                            {document.name}
                        </h1>
                        <p className="mt-2 text-muted-foreground">
                            Uploaded on {formatDate(document.createdAt)}
                        </p>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" asChild>
                            <Link href={route("documents.edit", document.id)}>
                                Edit
                            </Link>
                        </Button>
                        <Dialog
                            open={isDeleteDialogOpen}
                            onOpenChange={setIsDeleteDialogOpen}
                        >
                            <DialogTrigger asChild>
                                <Button variant="destructive">Delete</Button>
                            </DialogTrigger>
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Delete Document</DialogTitle>
                                    <DialogDescription>
                                        Are you sure you want to delete this
                                        document? This action cannot be undone.
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter className="gap-2">
                                    <Button
                                        variant="secondary"
                                        onClick={() =>
                                            setIsDeleteDialogOpen(false)
                                        }
                                    >
                                        Cancel
                                    </Button>
                                    <Button
                                        variant="destructive"
                                        onClick={handleDelete}
                                        disabled={processing}
                                    >
                                        {processing
                                            ? "Deleting..."
                                            : "Confirm Delete"}
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                        <Button asChild>
                            <Link href={route("documents.index", document.id)}>
                                Begin Analysis
                            </Link>
                        </Button>
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
                        </CardContent>
                    </Card>

                    {document.description && (
                        <Card className="md:col-span-2">
                            <CardHeader>
                                <CardTitle>Description</CardTitle>
                                <CardDescription>
                                    Additional information about the document
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <p className="whitespace-pre-wrap text-muted-foreground">
                                    {document.description}
                                </p>
                            </CardContent>
                        </Card>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
