import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from "@/components/ui/dialog";
import { useDocument } from "@/pages/documents/partials/document-provider";
import { useForm } from "@inertiajs/react";
import { useState } from "react";

export function DeleteFormModal() {
    const { document } = useDocument();
    const [isDeleteDialogOpen, setIsDeleteDialogOpen] = useState(false);
    const { delete: destroy, processing } = useForm();

    const handleDelete = () => {
        destroy(route("documents.destroy", document?.id), {
            preserveScroll: true,
            onSuccess: () => {
                setIsDeleteDialogOpen(false);
            },
        });
    };

    return (
        <Dialog open={isDeleteDialogOpen} onOpenChange={setIsDeleteDialogOpen}>
            <DialogTrigger asChild>
                <Button variant="destructive" className="cursor-pointer">
                    Delete
                </Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Document</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this document? This
                        action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter className="gap-2">
                    <Button
                        variant="secondary"
                        onClick={() => setIsDeleteDialogOpen(false)}
                    >
                        Cancel
                    </Button>
                    <Button
                        variant="destructive"
                        onClick={handleDelete}
                        disabled={processing}
                    >
                        {processing ? "Deleting..." : "Confirm Delete"}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
