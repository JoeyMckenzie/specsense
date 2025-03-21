import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";

interface BidItemsTableProps {
    bidItems: App.Data.BidItemSummaryData[];
}

export function BidItemsTable({ bidItems }: BidItemsTableProps) {
    if (bidItems.length === 0) {
        return (
            <div className="py-4 text-center text-muted-foreground text-sm">
                No bid items found
            </div>
        );
    }

    return (
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
                    </TableRow>
                ))}
            </TableBody>
        </Table>
    );
}
