import {createContext, type JSX, useContext, useEffect, useState,} from "react";

type DocumentState = {
    document?: App.Data.DocumentSummaryData | null;
    setDocument: (document?: App.Data.DocumentSummaryData | null) => void;
};

const DocumentProviderContext = createContext<DocumentState>({
    document: null,
    setDocument: () => {
    },
});

export function DocumentProvider({
                                     children,
                                     currentDocument,
                                 }: {
    children: JSX.Element;
    currentDocument?: App.Data.DocumentSummaryData | null;
}) {
    const [document, setDocument] = useState<
        App.Data.DocumentSummaryData | null | undefined
    >(currentDocument);

    useEffect(() => {
        // TODO: this doesn't feel right, should be doing value equality
        if (currentDocument !== document) {
            setDocument(currentDocument);
        }
    }, [document, currentDocument]);

    return (
        <DocumentProviderContext.Provider
            value={{
                document,
                setDocument,
            }}
        >
            {children}
        </DocumentProviderContext.Provider>
    );
}

export const useDocument = () => {
    const context = useContext(DocumentProviderContext);

    if (context === undefined) {
        throw new Error("useDocument must be used within a document provider.");
    }

    return context;
};
