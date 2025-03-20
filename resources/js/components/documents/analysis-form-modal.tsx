import { Badge } from "@/components/ui/badge";
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
import { Textarea } from "@/components/ui/textarea";
import { useForm } from "@inertiajs/react";
import { X } from "lucide-react";
import { type KeyboardEvent, useState } from "react";

interface AnalysisFormModalProps {
    documentId: number;
    trigger: React.ReactNode;
}

export function AnalysisFormModal({
    documentId,
    trigger,
}: AnalysisFormModalProps) {
    const [isOpen, setIsOpen] = useState(false);
    const [scopes, setScopes] = useState<string[]>([]);
    const [currentScope, setCurrentScope] = useState("");
    const { data, setData, post, processing, reset } = useForm({
        context: "",
        scopes: [] as string[],
    });

    const handleKeyDown = (e: KeyboardEvent<HTMLInputElement>) => {
        if (
            e.key === "Enter" &&
            currentScope.trim() &&
            scopes.length < 5 &&
            currentScope.length <= 30
        ) {
            e.preventDefault();
            setScopes([...scopes, currentScope.trim()]);
            setCurrentScope("");
        } else if (
            e.key === "Backspace" &&
            !currentScope &&
            scopes.length > 0
        ) {
            setScopes(scopes.slice(0, -1));
        }
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        if (value.length <= 30) {
            setCurrentScope(value);
        }
    };

    const removeScope = (index: number) => {
        setScopes(scopes.filter((_, i) => i !== index));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(route("documents.analyze", documentId), {
            onSuccess: () => {
                setIsOpen(false);
                reset();
                setScopes([]);
            },
        });
    };

    return (
        <Dialog open={isOpen} onOpenChange={setIsOpen}>
            <DialogTrigger asChild>{trigger}</DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Begin Document Analysis</DialogTitle>
                        <DialogDescription>
                            Provide additional context and specific scopes of
                            work you'd like to analyze.
                        </DialogDescription>
                    </DialogHeader>
                    <div className="grid gap-4 py-4">
                        <div className="space-y-2">
                            <label
                                htmlFor="context"
                                className="font-medium text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                Additional Context
                            </label>
                            <Textarea
                                id="context"
                                value={data.context}
                                onChange={(e) =>
                                    setData("context", e.target.value)
                                }
                                placeholder="Provide any additional context for the analysis..."
                                className="min-h-[100px]"
                            />
                        </div>
                        <div className="space-y-2">
                            <label
                                htmlFor="scopes"
                                className="font-medium text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                Scopes of Work
                            </label>
                            <Input
                                id="scopes"
                                value={currentScope}
                                onChange={handleChange}
                                onKeyDown={handleKeyDown}
                                placeholder="Type a scope and press Enter"
                            />
                            <div className="flex flex-wrap gap-2">
                                {scopes.map((scope, index) => (
                                    <Badge
                                        key={scope}
                                        variant="secondary"
                                        className="flex items-center gap-1"
                                    >
                                        {scope}
                                        <button
                                            type="button"
                                            onClick={() => removeScope(index)}
                                            className="ml-1 hover:text-destructive"
                                        >
                                            <X className="h-3 w-3" />
                                        </button>
                                    </Badge>
                                ))}
                            </div>
                            <input
                                type="hidden"
                                name="scopes"
                                value={JSON.stringify(scopes)}
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => setIsOpen(false)}
                        >
                            Cancel
                        </Button>
                        <Button type="submit" disabled={processing}>
                            {processing ? "Analyzing..." : "Begin Analysis"}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
}
