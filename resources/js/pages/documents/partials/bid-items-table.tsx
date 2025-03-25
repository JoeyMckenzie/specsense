import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow, } from "@/components/ui/table";
import { useDocument } from "@/pages/documents/partials/document-provider";
import { Pencil, Trash2 } from "lucide-react";
import { useState } from "react";
import { DeleteBidItemModal } from "./delete-bid-item-modal";
import { EditBidItemModal } from "./edit-bid-item-modal";

export function BidItemsTable() {
    const [editingItem, setEditingItem] =
        useState<App.Data.BidItemSummaryData | null>(null);
    const [deletingItem, setDeletingItem] =
        useState<App.Data.BidItemSummaryData | null>(null);
    const {document} = useDocument();
    const bidItems = document?.analysis?.bidItems ?? [];

    if (bidItems.length === 0) {
        return (
            <div className="py-4 text-center text-muted-foreground text-sm">
                No bid items found
            </div>
        );
    }

    return (
        <div className="space-y-4">
            <Table>
                <TableHeader>
                    <TableRow>
                        <TableHead>Item Number</TableHead>
                        <TableHead>Item Code</TableHead>
                        <TableHead>Description</TableHead>
                        <TableHead className="text-right">
                            Unit of Measure
                        </TableHead>
                        <TableHead className="text-right">
                            Estimated Quantity
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {bidItems.map((item) => (
                        <TableRow key={item.id}>
                            <TableCell>{item.itemNumber ?? "-"}</TableCell>
                            <TableCell>{item.itemCode ?? "-"}</TableCell>
                            <TableCell>{item.itemDescription ?? "-"}</TableCell>
                            <TableCell className="text-right">
                                {item.unitOfMeasure ?? "-"}
                            </TableCell>
                            <TableCell className="text-right">
                                {item.estimatedQuantity ?? "-"}
                            </TableCell>
                            <TableCell>
                                <div className="flex gap-2">
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        onClick={() => setEditingItem(item)}
                                    >
                                        <Pencil className="h-4 w-4"/>
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        onClick={() => setDeletingItem(item)}
                                    >
                                        <Trash2 className="text-destructive"/>
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>

            {editingItem && (
                <EditBidItemModal
                    bidItem={editingItem}
                    open={!!editingItem}
                    onOpenChange={(open) => !open && setEditingItem(null)}
                />
            )}

            {deletingItem && (
                <DeleteBidItemModal
                    bidItem={deletingItem}
                    open={!!deletingItem}
                    onOpenChange={(open) => !open && setDeletingItem(null)}
                />
            )}
        </div>
    );
}
