import { AppContent } from "@/components/app-content";
import { AppShell } from "@/components/app-shell";
import { AppSidebar } from "@/components/app-sidebar";
import { AppSidebarHeader } from "@/components/app-sidebar-header";
import { Toaster } from "@/components/ui/sonner";
import type { BreadcrumbItem, SharedData } from "@/types";
import { usePage } from "@inertiajs/react";
import { type PropsWithChildren, useEffect } from "react";
import { toast } from "sonner";

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: PropsWithChildren<{ breadcrumbs?: BreadcrumbItem[] }>) {
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

    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                {children}
                <Toaster richColors />
            </AppContent>
        </AppShell>
    );
}
