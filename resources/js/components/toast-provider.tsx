import { Toaster } from "@/components/ui/sonner";
import type { SharedData } from "@/types";
import { usePage } from "@inertiajs/react";
import { type ReactNode, createContext, useContext, useEffect } from "react";
import { toast } from "sonner";

type ToastProviderProps = {
    children: ReactNode;
};

const ToastProviderContext = createContext(null);

export function ToastProvider({ children, ...props }: ToastProviderProps) {
    const { flash } = usePage<SharedData>().props;

    useEffect(() => {
        if (typeof window === "undefined" || !flash) {
            return;
        }

        if (flash.success) {
            toast.success(flash.success);
        }

        if (flash.error) {
            toast.error(flash.error);
        }

        if (flash.warning) {
            toast.warning(flash.warning);
        }

        if (flash.info) {
            toast.info(flash.info);
        }
    }, [flash]);

    return (
        <ToastProviderContext.Provider {...props} value={null}>
            {children}
            <Toaster richColors />
        </ToastProviderContext.Provider>
    );
}

export const useToaster = () => {
    const context = useContext(ToastProviderContext);

    if (context === undefined) {
        throw new Error("useToaster must be used within a theme provider.");
    }

    return context;
};
