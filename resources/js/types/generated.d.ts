declare namespace App.Data {
    export type DocumentAnalysisSummaryData = {
        status: App.Enums.DocumentAnalysisStatus;
        contractNumber: string | null;
        projectId: string | null;
        engineersEstimate: string | null;
        bidDueDate: string | null;
        numberOfWorkingDays: string | null;
        dbeGoal: string | null;
        dirNumber: string | null;
        jobLocation: string | null;
    };
    export type DocumentSummaryData = {
        id: number;
        name: string;
        description: string;
        createdAt: string;
        updatedAt: string;
        size: number;
        user: App.Data.UserSummaryData;
        previewImage: string | null;
        type: App.Enums.DocumentType;
        analysis: App.Data.DocumentAnalysisSummaryData | null;
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
