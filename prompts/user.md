You are analyzing a special provisions document for a construction project. The document likely contains specific
details about required work, materials, and project parameters. Please analyze the document and extract key information
in a structured format.

##scopes_of_work##

Format your response as a JSON object following this schema EXACTLY:

```
{
    "summary": string, // 1-2 paragraph overview of the project. If not a special provisions doc, return "Not a special
    provisions document."
    "contract_number": string | null, // Contract/Job number
    "project_id": string | null, // Project/Job ID
    "engineers_estimate": string | null, // Engineer's estimate (include currency/units if specified)
    "bid_due_date": string | null, // Bid submission deadline
    "number_of_working_days": string | null, // Allocated working days for project completion
    "dbe_goal": string | null, // Disadvantaged Business Enterprise (DBE) participation goal
    "dir_number": string | null, // Department of Industrial Relations (DIR) number
    "job_location": string | null, // Physical location/address of the project
    "bid_items": [ // Array of materials and items from bid table, if present
        {   
            "item_number": string | null, // Item reference number
            "item_code": string | null, // Item identification code
            "item_description": string | null, // Detailed description of item
            "unit_of_measure": string | null, // Unit type (e.g., LF, SF, EA)
            "estimated_quantity": string | null // Required quantity
        }
    ],
    "work_scopes": [ // Array of identified work categories and their requirements
        {
            "scope": string | null, // Category (e.g., "concrete", "fencing", "electrical")
            "summary": string | null // Brief description of work required for this scope
        }
    ]
}
```

Special Instructions:

1. All numeric values should be preserved exactly as written in the document
2. Dates should be extracted in their original format
3. Keep descriptions concise but include all critical requirements
4. If a field's information isn't found, use null rather than making assumptions
5. Include material quantities and measurements exactly as specified
6. Extract ALL relevant work scopes mentioned in the document
7. If the document isn't a special provisions document, return only:

```json
{
    "summary": "Not a special provisions document"
}
```

Here is the document text to analyze:
