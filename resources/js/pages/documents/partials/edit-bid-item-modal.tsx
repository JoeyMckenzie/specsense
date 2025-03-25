import { Button } from "@/components/ui/button";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useDocument } from "@/pages/documents/partials/document-provider";
import { useForm } from "@inertiajs/react";
import type { FormEvent } from "react";
import { toast } from "sonner";

interface EditBidItemModalProps {
    bidItem: App.Data.BidItemSummaryData;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function EditBidItemModal({
                                     bidItem,
                                     open,
                                     onOpenChange,
                                 }: EditBidItemModalProps) {
    const {
        data,
        setData,
        put,
        errors,
        reset,
        processing,
        recentlySuccessful,
    } = useForm({
        item_number: bidItem.itemNumber,
        item_code: bidItem.itemCode,
        item_description: bidItem.itemDescription,
        unit_of_measure: bidItem.unitOfMeasure,
        estimated_quantity: bidItem.estimatedQuantity,
    });

    const {document} = useDocument();

    const handleSubmit = (e: FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        put(
            route("bid-items.update", {
                document: document?.id,
                documentAnalysis: document?.analysis?.id,
                bidItem: bidItem.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    reset(
                        "item_number",
                        "item_code",
                        "item_description",
                        "unit_of_measure",
                        "estimated_quantity",
                    );
                    onOpenChange(false);

                    if (recentlySuccessful) {
                        toast.success("Bid item updated successfully.");
                    }
                },
            },
        );
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Edit Bid Item</DialogTitle>
                    <DialogDescription>
                        Update the information for this bid item.
                    </DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="space-y-2">
                        <Label htmlFor="itemNumber">Item Number</Label>
                        <Input
                            id="itemNumber"
                            name="itemNumber"
                            defaultValue={bidItem.itemNumber ?? ""}
                        />
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="itemCode">Item Code</Label>
                        <Input
                            id="itemCode"
                            name="itemCode"
                            defaultValue={bidItem.itemCode ?? ""}
                        />
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="itemDescription">Description</Label>
                        <Input
                            id="itemDescription"
                            name="itemDescription"
                            defaultValue={bidItem.itemDescription ?? ""}
                        />
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="unitOfMeasure">Unit of Measure</Label>
                        <Input
                            id="unitOfMeasure"
                            name="unitOfMeasure"
                            defaultValue={bidItem.unitOfMeasure ?? ""}
                        />
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="estimatedQuantity">
                            Estimated Quantity
                        </Label>
                        <Input
                            id="estimatedQuantity"
                            name="estimatedQuantity"
                            defaultValue={bidItem.estimatedQuantity ?? ""}
                        />
                    </div>
                    <DialogFooter>
                        <Button disabled={processing} type="submit">
                            Save Changes
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
