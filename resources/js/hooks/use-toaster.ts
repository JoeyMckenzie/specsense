import type { SharedData } from "@/types";
import { usePage } from "@inertiajs/react";
import { useEffect } from "react";
import { toast } from "sonner";

export function useToaster() {
    const { flash } = usePage<SharedData>().props;

    useEffect(() => {
        if (!flash) {
            return;
        }

        if (flash.success) {
            toast.success(flash.success, {
                style: {
                    "--success-icon-color": "var(--success)",
                } as React.CSSProperties,
            });
        }
        if (flash.error) {
            toast.error(flash.error, {
                style: {
                    "--error-icon-color": "var(--destructive)",
                } as React.CSSProperties,
            });
        }
        if (flash.warning) {
            toast.warning(flash.warning, {
                style: {
                    "--warning-icon-color": "var(--warning)",
                } as React.CSSProperties,
            });
        }
        if (flash.info) {
            toast.info(flash.info, {
                style: {
                    "--info-icon-color": "var(--info)",
                } as React.CSSProperties,
            });
        }
    }, [flash]);
}
