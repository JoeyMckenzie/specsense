import InputError from "@/components/input-error";
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
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useDocument } from "@/pages/documents/partials/document-provider";
import { useForm } from "@inertiajs/react";
import { Plus } from "lucide-react";
import { type FormEvent, useState } from "react";

export function AddBidItemFormModal() {
    const [open, setOpen] = useState(false);
    const {document} = useDocument();
    const documentId = document?.id;
    const documentAnalysisId = document?.analysis?.id;
    const {post, data, setData, errors, reset, processing} = useForm({
        item_number: "",
        item_code: "",
        item_description: "",
        unit_of_measure: "",
        estimated_quantity: "",
    });

    const handleSubmit = (e: FormEvent) => {
        e.preventDefault();
        post(
            route("bid-items.store", {
                document: documentId,
                documentAnalysis: documentAnalysisId,
            }),
            {
                onSuccess: () => {
                    reset(
                        "item_number",
                        "item_code",
                        "item_description",
                        "unit_of_measure",
                        "estimated_quantity",
                    );
                    setOpen(false);
                },
            },
        );
    };

    return (
        <Dialog open={open} onOpenChange={setOpen}>
            <DialogTrigger asChild>
                <Button>
                    <Plus className="mr-2 h-4 w-4"/>
                    Add Item
                </Button>
            </DialogTrigger>
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add Bid Item</DialogTitle>
                    <DialogDescription>
                        Fill out the information for the new bid item.
                    </DialogDescription>
                </DialogHeader>
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="space-y-2">
                        <Label htmlFor="item_number">Item Number</Label>
                        <Input
                            id="item_number"
                            value={data.item_number}
                            onChange={(e) =>
                                setData("item_number", e.target.value)
                            }
                        />
                        <InputError message={errors.item_number}/>
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="item_code">Item Code</Label>
                        <Input
                            id="item_code"
                            value={data.item_code}
                            onChange={(e) =>
                                setData("item_code", e.target.value)
                            }
                        />
                        <InputError message={errors.item_code}/>
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="item_description">Description</Label>
                        <Input
                            id="item_description"
                            value={data.item_description}
                            onChange={(e) =>
                                setData("item_description", e.target.value)
                            }
                        />
                        <InputError message={errors.item_description}/>
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="unit_of_measure">Unit of Measure</Label>
                        <Input
                            id="unit_of_measure"
                            value={data.unit_of_measure}
                            onChange={(e) =>
                                setData("unit_of_measure", e.target.value)
                            }
                        />
                        <InputError message={errors.unit_of_measure}/>
                    </div>
                    <div className="space-y-2">
                        <Label htmlFor="estimated_quantity">
                            Estimated Quantity
                        </Label>
                        <Input
                            id="estimated_quantity"
                            value={data.estimated_quantity}
                            onChange={(e) =>
                                setData("estimated_quantity", e.target.value)
                            }
                        />
                        <InputError message={errors.estimated_quantity}/>
                    </div>
                    <DialogFooter>
                        <Button disabled={processing} type="submit">
                            Add Item
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
