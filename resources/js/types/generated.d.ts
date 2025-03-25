declare namespace App.Data {
    export type BidItemSummaryData = {
        id: number;
        itemNumber: string | null;
        itemCode: string | null;
        itemDescription: string | null;
        unitOfMeasure: string | null;
        estimatedQuantity: string | null;
    };
    export type DocumentAnalysisSummaryData = {
        id: number;
        status: App.Enums.DocumentAnalysisStatus;
        documentSummary: string | null;
        contractNumber: string | null;
        projectId: string | null;
        engineersEstimate: string | null;
        bidDueDate: string | null;
        numberOfWorkingDays: string | null;
        dbeGoal: string | null;
        dirNumber: string | null;
        jobLocation: string | null;
        workScopes: Array<App.Data.WorkScopeSummaryData>;
        bidItems: Array<App.Data.BidItemSummaryData>;
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
    export type WorkScopeSummaryData = {
        id: number;
        name: string;
        analysis: string | null;
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
