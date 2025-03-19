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
    const { data, setData, post, processing, errors } = useForm({
        name: "",
        description: "",
        file: null as File | null,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post("/documents");
    };

    const handleFileUpdate = (files: FilePondFile[]) => {
        const file = files[0]?.file as File;
        setData("file", file ?? null);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Upload Document" />
            <div className="flex h-full flex-1 items-center justify-center p-4">
                <div className="w-full max-w-4xl space-y-8">
                    <div className="space-y-2 text-center">
                        <h1 className="font-semibold text-2xl tracking-tight">
                            Upload Document
                        </h1>
                        <p className="text-muted-foreground text-sm">
                            Upload a special provisions document for analysis
                        </p>
                    </div>

                    <form onSubmit={handleSubmit} className="mx-auto max-w-2xl">
                        <div className="grid grid-cols-1 gap-8">
                            <div className="space-y-6">
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Title</Label>
                                    <Input
                                        id="name"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData("name", e.target.value)
                                        }
                                        placeholder="Document title"
                                        required
                                    />
                                    <InputError message={errors.name} />
                                </div>

                                <div className="grid gap-2">
                                    <Label htmlFor="description">
                                        Description (optional)
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
                                        placeholder="Optionally, provide a description of the document and its contents. This will help you identify the document when you are analyzing it."
                                        className="min-h-[120px]"
                                    />
                                    <InputError message={errors.description} />
                                </div>
                            </div>

                            <div className="space-y-6">
                                <div className="grid gap-2">
                                    <Label>Document File</Label>
                                    <FilePond
                                        files={data.file ? [data.file] : []}
                                        onupdatefiles={handleFileUpdate}
                                        acceptedFileTypes={["application/pdf"]}
                                        maxFiles={1}
                                        labelIdle="Drag and drop your PDF here, or click to browse"
                                        labelFileTypeNotAllowed="File is of invalid type"
                                        allowMultiple={false}
                                        className="filepond"
                                    />
                                    <InputError message={errors.file} />
                                </div>
                            </div>
                        </div>

                        <div className="mt-8 flex justify-end">
                            <Button
                                type="submit"
                                disabled={processing}
                                size="lg"
                            >
                                {processing
                                    ? "Uploading..."
                                    : "Upload Document"}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
