declare namespace App.Data.Documents {
    export type DashboardDocument = {};
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
