import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import AppLayout from "@/layouts/app-layout";
import type { BreadcrumbItem } from "@/types";
import { Head, useForm } from "@inertiajs/react";
import { Upload } from "lucide-react";
import { useState } from "react";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: "Documents",
        href: "/documents",
    },
    {
        title: "Upload Document",
        href: "/documents/create",
    },
];

export default function Create() {
    const { data, setData, post, processing, errors } = useForm({
        name: "",
        description: "",
        file: null as File | null,
    });

    const [dragActive, setDragActive] = useState(false);

    const handleDrag = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
    };

    const handleDragIn = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(true);
    };

    const handleDragOut = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);
    };

    const handleDrop = (e: React.DragEvent) => {
        e.preventDefault();
        e.stopPropagation();
        setDragActive(false);

        if (e.dataTransfer.files?.[0]) {
            setData("file", e.dataTransfer.files[0]);
        }
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files?.[0]) {
            setData("file", e.target.files[0]);
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post("/documents");
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Upload Document" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle>Upload Document</CardTitle>
                        <CardDescription>
                            Upload a special provisions document for analysis
                        </CardDescription>
                    </CardHeader>
                    <form onSubmit={handleSubmit}>
                        <CardContent className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="name">Document Name</Label>
                                <Input
                                    id="name"
                                    value={data.name}
                                    onChange={(e) =>
                                        setData("name", e.target.value)
                                    }
                                    placeholder="Enter document name"
                                    required
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="description">
                                    Description (Optional)
                                </Label>
                                <Textarea
                                    id="description"
                                    value={data.description}
                                    onChange={(e) =>
                                        setData("description", e.target.value)
                                    }
                                    placeholder="Enter document description"
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="space-y-2">
                                <Label>Document File</Label>
                                <div
                                    className={`relative flex h-32 cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed transition-colors ${
                                        dragActive
                                            ? "border-primary bg-primary/5"
                                            : "border-muted-foreground/25"
                                    }`}
                                    onDragEnter={handleDragIn}
                                    onDragLeave={handleDragOut}
                                    onDragOver={handleDrag}
                                    onDrop={handleDrop}
                                    onClick={() =>
                                        document.getElementById("file")?.click()
                                    }
                                >
                                    <input
                                        id="file"
                                        type="file"
                                        className="hidden"
                                        onChange={handleFileChange}
                                        accept=".pdf"
                                        required
                                    />
                                    <Upload className="mb-2 h-8 w-8 text-muted-foreground" />
                                    <p className="text-muted-foreground text-sm">
                                        {data.file
                                            ? data.file.name
                                            : "Drag and drop your file here, or click to select"}
                                    </p>
                                </div>
                                <InputError message={errors.file} />
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button
                                type="submit"
                                disabled={processing}
                                className="w-full"
                            >
                                {processing
                                    ? "Uploading..."
                                    : "Upload Document"}
                            </Button>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </AppLayout>
    );
}
