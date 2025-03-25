import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { useDocument } from "@/pages/documents/partials/document-provider";
import { useForm } from "@inertiajs/react";
import type { FormEventHandler } from "react";

interface DeleteBidItemModalProps {
    bidItem: App.Data.BidItemSummaryData;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function DeleteBidItemModal({
    bidItem,
    open,
    onOpenChange,
}: DeleteBidItemModalProps) {
    const { delete: destroy, processing } = useForm();
    const { document } = useDocument();

    const deleteBidItem: FormEventHandler = (e) => {
        e.preventDefault();

        destroy(
            route("bid-items.destroy", {
                document: document?.id,
                documentAnalysis: document?.analysis?.id,
                bidItem: bidItem.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    onOpenChange(false);
                },
            },
        );
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Bid Item</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this bid item? This
                        action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <form
                    id="delete-bid-item-form"
                    className="py-4"
                    onSubmit={deleteBidItem}
                >
                    <div className="space-y-2">
                        <div>
                            <span className="font-medium">Item Number:</span>{" "}
                            {bidItem.itemNumber ?? "-"}
                        </div>
                        <div>
                            <span className="font-medium">Item Code:</span>{" "}
                            {bidItem.itemCode ?? "-"}
                        </div>
                        <div>
                            <span className="font-medium">Description:</span>{" "}
                            {bidItem.itemDescription ?? "-"}
                        </div>
                    </div>
                </form>
                <DialogFooter>
                    <Button
                        variant="outline"
                        onClick={() => onOpenChange(false)}
                    >
                        Cancel
                    </Button>
                    <Button
                        form="delete-bid-item-form"
                        variant="destructive"
                        disabled={processing}
                        type="submit"
                    >
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
}
