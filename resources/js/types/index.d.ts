import type { LucideIcon } from "lucide-react";
import type { Config } from "ziggy-js";

export interface Auth {
    user: App.Data.UserSummaryData;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

interface FlashMessage {
    success?: string;
    error?: string;
    warning?: string;
    info?: string;
}

type FlashMessageType = "success" | "error" | "warning" | "info";

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    flash: FlashMessage;

    [key: string]: unknown;
}
