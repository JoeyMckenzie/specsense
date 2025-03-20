import InputError from "@/components/input-error";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import AppLayout from "@/layouts/app-layout";
import type { BreadcrumbItem } from "@/types";
import { Head, useForm } from "@inertiajs/react";
import type { FilePondFile } from "filepond";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import { FilePond, registerPlugin } from "react-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css";
import { Card } from "@/components/ui/card";
import { Checkbox } from "@/components/ui/checkbox";
import { Progress } from "@/components/ui/progress";
import { FileText } from "lucide-react";

// Register the plugins
registerPlugin(FilePondPluginFileValidateType, FilePondPluginImagePreview);

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
    const { data, setData, post, processing, errors, progress } = useForm<{
        name: string;
        description: string;
        file: File | null;
        analyze: boolean;
    }>({
        name: "",
        description: "",
        file: null,
        analyze: false,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post("/documents");
    };

    const handleFileUpdate = (files: FilePondFile[]) => {
        const file = files[0]?.file as File;
        setData("file", file ?? null);
    };

    const uploadProgress = progress ? Math.round(progress?.percentage ?? 0) : 0;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Upload Document" />
            <div className="container mx-auto px-4 py-8">
                <div className="mx-auto max-w-4xl space-y-8">
                    <div className="space-y-2">
                        <h1 className="font-semibold text-2xl tracking-tight">
                            Upload Document
                        </h1>
                        <p className="text-muted-foreground text-sm">
                            Upload your special provisions document for analysis
                        </p>
                    </div>

                    <Card className="p-6">
                        <form onSubmit={handleSubmit} className="space-y-8">
                            <div className="grid gap-8 md:grid-cols-2">
                                <div className="space-y-6">
                                    <div className="space-y-2">
                                        <Label
                                            htmlFor="name"
                                            className="text-sm"
                                        >
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
                                        <InputError message={errors.name} />
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
                                                setData(
                                                    "description",
                                                    e.target.value,
                                                )
                                            }
                                            placeholder="Provide additional context about this document..."
                                            className="min-h-[100px] resize-none"
                                        />
                                        <InputError
                                            message={errors.description}
                                        />
                                    </div>

                                    <div className="flex items-center space-x-2">
                                        <Checkbox
                                            id="analyze"
                                            checked={data.analyze}
                                            onCheckedChange={(checked) =>
                                                setData(
                                                    "analyze",
                                                    checked === true,
                                                )
                                            }
                                        />
                                        <Label
                                            htmlFor="analyze"
                                            className="font-medium text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                        >
                                            Analyze document after upload
                                        </Label>
                                    </div>
                                </div>

                                <div className="space-y-6">
                                    <div className="space-y-2">
                                        <Label className="text-sm">
                                            Upload Document
                                        </Label>
                                        <div className="relative">
                                            <FilePond
                                                files={
                                                    data.file ? [data.file] : []
                                                }
                                                onupdatefiles={handleFileUpdate}
                                                acceptedFileTypes={[
                                                    "application/pdf",
                                                ]}
                                                maxFiles={1}
                                                labelIdle="Drag and drop your PDF here or click to browse"
                                                labelFileTypeNotAllowed="File is of invalid type"
                                                allowMultiple={false}
                                                className="filepond"
                                            />
                                            {data.file && (
                                                <div className="mt-2 flex items-center gap-2 text-muted-foreground text-xs">
                                                    <FileText className="h-3 w-3" />
                                                    <span>
                                                        {data.file.name}
                                                    </span>
                                                </div>
                                            )}
                                        </div>
                                        <InputError message={errors.file} />
                                    </div>
                                </div>
                            </div>

                            {processing && (
                                <div className="space-y-2">
                                    <Progress
                                        value={uploadProgress}
                                        className="h-2"
                                    />
                                    <p className="text-center text-muted-foreground text-sm">
                                        Uploading document... {uploadProgress}%
                                    </p>
                                </div>
                            )}

                            <div className="flex justify-end">
                                <Button
                                    type="submit"
                                    disabled={processing}
                                    size="lg"
                                    className="min-w-[160px]"
                                >
                                    {processing
                                        ? "Uploading..."
                                        : "Upload Document"}
                                </Button>
                            </div>
                        </form>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
