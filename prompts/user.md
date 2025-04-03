You are analyzing a special provisions document for a construction project. Your MOST CRITICAL TASK is to extract the
COMPLETE bid item list, along with other key project information.

The bid item list will typically be labeled "BID ITEM LIST" and formatted as a table with these columns:

- Item No. (usually 4-digit format like "0001")
- Item Code (usually 6-digit format like "070030")
- Item Description (may span multiple lines)
- Unit of Measure (LS, LUMP SUM, EA, HR, SQYD, LF, etc.)
- Estimated Quantity

Example format: of a bid item list

```
Item Code

BID ITEM LIST

Item Description

Unit of Measure

Estimated Quantity

070030

LEAD COMPLIANCE PLAN

LS

LUMP SUM

090100

TIME-RELATED OVERHEAD (WDAY)

120

090214

SAFETY QUALITY CONTROL MANAGER

LUMP SUM

120090

CONSTRUCTION AREA SIGNS

LUMP SUM

120100

TRAFFIC CONTROL SYSTEM

LUMP SUM

120120

TYPE III BARRICADE

74

120149

TEMPORARY PAVEMENT MARKING (PAINT)

120159

TEMPORARY TRAFFIC STRIPE (PAINT)

120165

CHANNELIZER (SURFACE MOUNTED)

120300

TEMPORARY PAVEMENT MARKER

120320

TEMPORARY BARRIER SYSTEM

128651

PORTABLE CHANGEABLE MESSAGE SIGN (EA)

129105

TEMPORARY CRASH CUSHION TL-2

26

130100

JOB SITE MANAGEMENT

LUMP SUM

130201

WATER POLLUTION CONTROL PROGRAM

LUMP SUM

130620

TEMPORARY DRAINAGE INLET PROTECTION

33

130640

TEMPORARY FIBER ROLL

4,960

130900

TEMPORARY CONCRETE WASHOUT

LUMP SUM

141103

REMOVE YELLOW THERMOPLASTIC TRAFFIC
STRIPE (HAZARDOUS WASTE)

4,430

141120

TREATED WOOD WASTE

Contract No. 04-0K8004
3

1,920
```

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
    "bid_items": [ // Array of materials and items from bid table, if present (EXTRACT EVERY SINGLE ITEM, DO NOT TRUNCATE OR SUMMARIZE)
        {   
            "item_number": string | null, // Item number (e.g., "0001", "0002")
            "item_code": string | null, // Item code (e.g., "070030", "080060")
            "item_description": string | null, // Full description of item
            "unit_of_measure": string | null, // Unit type (e.g., "LS", "LUMP SUM", "EA", "HR", "SQYD", "LF")
            "estimated_quantity": string | null // Required quantity (e.g., "LUMP SUM", "12", "20", "10,500")
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

1. Extracting the COMPLETE bid item list is your HIGHEST PRIORITY - every single item must be included
2. Search for the phrase "BID ITEM LIST" to locate the bid item section
3. Pay attention to the sequential item numbers (0001, 0002, etc.) to identify all items in the list
4. The bid item section may appear many pages into the document - search thoroughly
5. Extract ALL items in the bid list, including their item numbers, codes, descriptions, units, and quantities
6. Some descriptions may continue across multiple lines in the text - ensure you capture the complete description
7. If the document isn't a special provisions document, return only:

```json
{
    "summary": "Not a special provisions document"
}
```

Here is the document text to analyze:
