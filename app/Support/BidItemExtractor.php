<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Client\Factory as Client;

final readonly class BidItemExtractor
{
    public function __construct(
        private Client $client,
    ) {
        //
    }

    /**
     * Extracts bid item list from the provided text. Attempts to locate and parse the bid item list section using
     * pattern matching. If pattern matching fails or the extracted items are invalid, it falls back to an LLM-based extraction method.
     */
    public function extract(string $text)
    {
        // Look for bid item list section
        if (preg_match('/BID\s+ITEM\s+LIST(.+?)(?:SCOPE|SECTION|APPENDIX|$)/si', $text, $matches)) {
            $bidItemSection = $matches[1];

            // Extract items using pattern matching
            $items = $this->extractItemsFromSection($bidItemSection);

            // Validate extraction (check for sequential item numbers)
            if ($this->validateExtraction($items)) {
                return $items;
            }

            // Fallback to LLM for extraction if pattern matching fails
            return $this->extractWithLLM($text);
        }

        // Bid item list not found with pattern matching, use LLM
        return $this->extractWithLLM($text);
    }

    /**
     * Extracts bid items from a given section using pattern matching.
     *
     * Leverages regular expressions to identify bid items based on their structured format,
     * including item number, item code, description, unit of measure, and estimated quantity.
     * @return array{item_number: (non-falsy-string & numeric-string), item_code: (non-falsy-string & numeric-string), item_description: string, unit_of_measure: non-empty-string, estimated_quantity: non-empty-string}[]
     */
    private function extractItemsFromSection(string $section): array
    {
        // Implement pattern matching for bid items
        $pattern = '/(\d{4})\s+(\d{6})\s+([\w\s\-\(\)\.,:;]+?)\s+((?:LS|LUMP SUM|EA|HR|SQYD|LF|TON|GAL|LB))\s+((?:LUMP SUM|\d+(?:,\d+)*(?:\.\d+)?))/i';
        preg_match_all($pattern, $section, $matches, PREG_SET_ORDER);

        $items = [];
        foreach ($matches as $match) {
            $items[] = [
                'item_number' => $match[1],
                'item_code' => $match[2],
                'item_description' => trim($match[3]),
                'unit_of_measure' => $match[4],
                'estimated_quantity' => $match[5],
            ];
        }

        return $items;
    }

    /**
     * @param  string[]  $items
     */
    private function validateExtraction(array $items): bool
    {
        // Check for sequential item numbers
        $expectedCount = count($items);
        $actualCount = 0;
        $lastNum = 0;

        foreach ($items as $item) {
            $num = (int) $item['item_number'];
            if ($num === $lastNum + 1) {
                $actualCount++;
            }
            $lastNum = $num;
        }

        // If we found at least 90% of expected sequential items
        return ($actualCount / $expectedCount) > 0.9;
    }

    /**
     * Fallback to LLM with focused prompt for bid item extraction.
     */
    private function extractWithLLM(string $text)
    {
        $prompt = 'Extract the complete bid item list from the following special provisions document. Focus ONLY on extracting the bid items table with item numbers, codes, descriptions, units, and quantities. Do not summarize or truncate the list.';
        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'BID_ITEM_EXTRACTION_PROMPT',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt."\n\n".$text,
                ],
            ],
            'response_format' => ['type' => 'json_object'],
        ]);

        /** @var array{choices: array<array{message: array{content: string}}>} $json */
        $json = $response->json();

        return json_decode((string) $json->choices[0]->message->content, true)['bid_items'] ?? [];
    }
}
