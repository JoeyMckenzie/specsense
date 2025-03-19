declare namespace App.Data {
    export type DocumentSummaryData = {
        id: number;
        name: string;
        description: string;
        createdAt: string;
        size: number;
        user: App.Data.UserSummaryData;
        thumbnail: string | null;
    };
    export type UserSummaryData = {
        id: number;
        firstName: string;
        lastName: string;
        fullName: string;
        initials: string;
        email: string;
        profileImage: string | null;
        emailVerifiedAt: string | null;
        createdAt: string;
        updatedAt: string;
    };
}
declare namespace App.Enums {
    export type DocumentAnalysisStatus =
        | "Not Started"
        | "In Progress"
        | "Completed"
        | "Cancelled"
        | "Failed";
    export type DocumentType = "Special Provisions" | "Other";
}
