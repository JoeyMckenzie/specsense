import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import AppLayout from "@/layouts/app-layout";
import type { BreadcrumbItem } from "@/types";
import { Head, Link, useForm } from "@inertiajs/react";
import { ArrowLeft } from "lucide-react";

const breadcrumbs = (title: string, id: number): BreadcrumbItem[] => [
    {
        title: "Documents",
        href: "/documents",
    },
    {
        title,
        href: `/documents/${id}`,
    },
    {
        title: "Edit",
        href: "#",
    },
];

export default function Edit({
    document,
}: {
    document: App.Data.DocumentSummaryData;
}) {
    const { data, setData, put, processing, errors } = useForm<{
        name: string;
        description: string;
    }>({
        name: document.name,
        description: document.description ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(route("documents.update", document.id));
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs(document.name, document.id)}>
            <Head title={`Edit ${document.name}`} />
            <div className="container mx-auto p-4">
                <div>
                    <div className="mb-8 flex flex-col justify-between sm:flex-row sm:items-center">
                        <div>
                            <h1 className="font-bold text-2xl tracking-tight">
                                Edit Document
                            </h1>
                            <p className="mt-2 text-muted-foreground text-sm">
                                Update the document details
                            </p>
                        </div>
                        <Link href={route("documents.show", document.id)}>
                            <Button variant="outline">
                                <ArrowLeft />
                                Back
                            </Button>
                        </Link>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Document Information</CardTitle>
                        <CardDescription>
                            Update the document details
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <div className="space-y-2">
                                <Label htmlFor="name" className="text-sm">
                                    Document Title
                                </Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData("name", e.target.value)
                                    }
                                    placeholder="Enter a descriptive title"
                                    className="h-10"
                                    required
                                />
                                {errors.name && (
                                    <p className="text-destructive text-sm">
                                        {errors.name}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label
                                    htmlFor="description"
                                    className="text-sm"
                                >
                                    Description
                                </Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) =>
                                        setData("description", e.target.value)
                                    }
                                    placeholder="Enter a description for this document"
                                    className="min-h-[100px]"
                                />
                                {errors.description && (
                                    <p className="text-destructive text-sm">
                                        {errors.description}
                                    </p>
                                )}
                            </div>

                            <div className="flex justify-end gap-2">
                                <Button
                                    variant="outline"
                                    asChild
                                    disabled={processing}
                                >
                                    <Link
                                        href={route(
                                            "documents.show",
                                            document.id,
                                        )}
                                    >
                                        Cancel
                                    </Link>
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? "Saving..." : "Save Changes"}
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
