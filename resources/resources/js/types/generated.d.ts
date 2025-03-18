declare namespace App.Data.Documents {
    export type DocumentSummary = {
        id: number;
        name: string;
        createdAt: string;
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
