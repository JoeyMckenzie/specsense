<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\LlmConnectorContract;
use App\Support\PromptParser;
use App\ValueObjects\DocumentMetadata;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

final class OpenAIConnector implements LlmConnectorContract
{
    public function getParsedDocumentMetadata(string $pdfContent, string $userPrompt): DocumentMetadata
    {
        $systemPrompt = PromptParser::getPrompt(base_path('prompts/system.md'));
        $content = sprintf(
            "%s\n\n%s\n\n",
            $userPrompt,
            $pdfContent
        );

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.Config::string('openai.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post(Config::string('openai.base_uri').'/chat/completions', [
            'model' => Config::string('openai.model'),
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => $content,
                ],
            ],
            'response_format' => [
                'type' => 'json_schema',
                'json_schema' => [
                    'name' => 'special_provisions_document_summary',
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'summary' => ['type' => 'string'],
                            'contract_number' => ['type' => 'string'],
                            'project_id' => ['type' => 'string'],
                            'engineers_estimate' => ['type' => 'string'],
                            'bid_due_date' => ['type' => 'string'],
                            'number_of_working_days' => ['type' => 'string'],
                            'dbe_goal' => ['type' => 'string'],
                            'dir_number' => ['type' => 'string'],
                            'job_location' => ['type' => 'string'],
                            'bid_items' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'item_number' => ['type' => 'string'],
                                        'item_code' => ['type' => 'string'],
                                        'item_description' => ['type' => 'string'],
                                        'unit_of_measure' => ['type' => 'string'],
                                        'estimated_quantity' => ['type' => 'string'],
                                    ],
                                    'required' => [
                                        'item_number',
                                        'item_code',
                                        'item_description',
                                        'unit_of_measure',
                                        'estimated_quantity',
                                    ],
                                    'additionalProperties' => false,
                                ],
                            ],
                            'work_scopes' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'scope' => ['type' => 'string'],
                                        'summary' => ['type' => 'string'],
                                    ],
                                    'required' => [
                                        'scope',
                                        'summary',
                                    ],
                                    'additionalProperties' => false,
                                ],
                            ],
                        ],
                        'required' => [
                            'summary',
                            'contract_number',
                            'project_id',
                            'engineers_estimate',
                            'bid_due_date',
                            'number_of_working_days',
                            'dbe_goal',
                            'dir_number',
                            'job_location',
                            'bid_items',
                            'work_scopes',
                        ],
                        'additionalProperties' => false,
                    ],
                    'strict' => true,
                ],
            ],
        ]);

        return DocumentMetadata::from($response);
    }
}
