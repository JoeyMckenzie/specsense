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
import { type KeyboardEvent, useEffect, useState } from "react";

interface AnalysisFormModalProps {
    documentId: number;
}

export function AnalysisFormModal({ documentId }: AnalysisFormModalProps) {
    const [isOpen, setIsOpen] = useState(false);
    const [workScopes, setWorkScopes] = useState<string[]>([]);
    const [currentScope, setCurrentScope] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    const { data, setData, post, processing, reset } = useForm({
        context: "",
        work_scopes: [] as string[],
        document_id: documentId,
    });

    const handleKeyDown = (e: KeyboardEvent<HTMLInputElement>) => {
        if (
            e.key === "Enter" &&
            currentScope.trim() &&
            workScopes.length < 3 &&
            currentScope.length <= 30
        ) {
            e.preventDefault();
            if (workScopes.includes(currentScope.trim())) {
                setErrorMessage("This scope has already been added.");
            } else {
                setWorkScopes([...workScopes, currentScope.trim()]);
                setCurrentScope("");
                setErrorMessage(""); // Clear error message
            }
        } else if (
            e.key === "Backspace" &&
            !currentScope &&
            workScopes.length > 0
        ) {
            setWorkScopes(workScopes.slice(0, -1));
        }
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = e.target.value;
        if (value.length <= 30) {
            setCurrentScope(value);
        }
    };

    const removeScope = (index: number) => {
        setWorkScopes(workScopes.filter((_, i) => i !== index));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (workScopes.length > 0) {
            data.work_scopes = [...workScopes];
        }

        post(route("document-analysis.store", documentId), {
            onSuccess: () => {
                setIsOpen(false);
                reset();
                setWorkScopes([]);
            },
        });
    };

    useEffect(() => {
        if (!isOpen) {
            reset();
            setWorkScopes([]);
        }
    }, [isOpen, reset]);

    return (
        <Dialog open={isOpen} onOpenChange={setIsOpen}>
            <DialogTrigger asChild>
                <Button>Begin Analysis</Button>
            </DialogTrigger>
            <DialogContent className="sm:max-w-[425px]">
                <form onSubmit={handleSubmit}>
                    <DialogHeader>
                        <DialogTitle>Begin document analysis?</DialogTitle>
                        <DialogDescription>
                            Document analysis can take several minutes. Don't
                            worry, we'll notify you once the analysis is
                            complete. Additionally, feel free to provide any
                            context and specific scopes of work you'd like to us
                            to focus on during the analysis.
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
                            {errorMessage && (
                                <p className="text-red-500 text-sm">
                                    {errorMessage}
                                </p>
                            )}
                            <div className="flex flex-wrap gap-2">
                                {workScopes.map((scope, index) => (
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
