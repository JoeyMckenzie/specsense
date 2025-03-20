<?php

declare(strict_types=1);

namespace App\ValueObjects;

use Illuminate\Http\Client\Response;
use InvalidArgumentException;
use JsonException;

/**
 * @phpstan-type BidItemSchema array{
 *     item_number: ?string,
 *     item_code: ?string,
 *     item_description: ?string,
 *     unit_of_measure: ?string,
 *     estimated_quantity: ?string,
 * }
 * @phpstan-type WorkScopeSchema array{
 *     scope: string,
 *     summary: string
 * }
 * @phpstan-type StructuredOutputSchema array{
 *     summary: string,
 *     contract_number?: ?string,
 *     project_id?: ?string,
 *     engineers_estimate?: ?string,
 *     bid_due_date?: ?string,
 *     number_of_working_days?: ?string,
 *     dbe_goal?: ?string,
 *     dir_number?: ?string,
 *     job_location?: ?string,
 *     bid_items?: ?array<int, BidItemSchema>,
 *     work_scopes?: ?array<int, WorkScopeSchema>
 * }
 */
final readonly class DocumentMetadata
{
    /**
     * @param  null|BidItemSchema[]  $bidItems
     * @param  null|WorkScopeSchema[]  $workScopes
     */
    public function __construct(
        public ?string $summary,
        public ?string $contractNumber,
        public ?string $projectId,
        public ?string $engineersEstimate,
        public ?string $bidDueDate,
        public ?string $numberOfWorkingDays,
        public ?string $dbeGoal,
        public ?string $dirNumber,
        public ?string $jobLocation,
        public ?array $bidItems,
        public ?array $workScopes,
        public string $llmResponse
    ) {}

    /**
     * @throws JsonException
     * @throws InvalidArgumentException
     */
    public static function from(Response $response): self
    {
        /** @var array{choices: array<int, array{message: array{content: string}}>} $json */
        $json = $response->json();
        $content = $json['choices'][0]['message']['content'];

        // Extract JSON from markdown code blocks if present
        if (str_contains($content, '```')) {
            preg_match('/```(?:json)?\s*(\{.*})\s*```/s', $content, $matches);
            $content = $matches[1] ?? $content;
        }

        /** @var StructuredOutputSchema $parsedContent */
        $parsedContent = json_decode($content, true, flags: JSON_THROW_ON_ERROR);

        /** @var StructuredOutputSchema $parsedContent */
        return new self(
            summary: $parsedContent['summary'],
            contractNumber: self::getNullishValue($parsedContent['contract_number'] ?? null),
            projectId: self::getNullishValue($parsedContent['project_id'] ?? null),
            engineersEstimate: self::getNullishValue($parsedContent['engineers_estimate'] ?? null),
            bidDueDate: self::getNullishValue($parsedContent['bid_due_date'] ?? null),
            numberOfWorkingDays: self::getNullishValue($parsedContent['number_of_working_days'] ?? null),
            dbeGoal: self::getNullishValue($parsedContent['dbe_goal'] ?? null),
            dirNumber: self::getNullishValue($parsedContent['dir_number'] ?? null),
            jobLocation: self::getNullishValue($parsedContent['job_location'] ?? null),
            bidItems: array_map(
                static fn (array $item): array => [
                    'item_number' => self::getNullishValue($item['item_number']),
                    'item_code' => self::getNullishValue($item['item_code']),
                    'item_description' => self::getNullishValue($item['item_description']),
                    'unit_of_measure' => self::getNullishValue($item['unit_of_measure']),
                    'estimated_quantity' => self::getNullishValue($item['estimated_quantity']),
                ],
                $parsedContent['bid_items'] ?? []
            ),
            workScopes: array_map(
                static fn (array $item): array => [
                    'scope' => $item['scope'],
                    'summary' => $item['summary'],
                ],
                $parsedContent['work_scopes'] ?? []
            ),
            llmResponse: $content
        );
    }

    private static function getNullishValue(?string $value): ?string
    {
        if ($value === null || $value === 'null') {
            return null;
        }

        return $value;
    }
}
