<?php

namespace Tests\Unit\Jobs;

use App\Contracts\DocumentAnalyzerContract;
use App\Enums\DocumentAnalysisStatus;
use App\Jobs\ProcessDocumentForAnalysis;
use App\Models\DocumentAnalysis;
use App\Models\WorkScope;
use App\ValueObjects\DocumentMetadata;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;
use Exception;

class ProcessDocumentForAnalysisTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface|DocumentAnalyzerContract $openAiService;
    private DocumentAnalysis $documentAnalysis;
    private ProcessDocumentForAnalysis $job;

    protected function setUp(): void
    {
        parent::setUp();

        $this->openAiService = Mockery::mock(DocumentAnalyzerContract::class);
        $this->documentAnalysis = DocumentAnalysis::factory()->create();
        $this->job = new ProcessDocumentForAnalysis($this->documentAnalysis);
    }

    /** @test */
    public function it_updates_document_with_parsed_content()
    {
        // Arrange
        $parsedContent = 'Test parsed content';
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateDocumentWithParsedContent', [$parsedContent]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals($parsedContent, $this->documentAnalysis->parsed_content);
    }

    /** @test */
    public function it_updates_document_analysis_when_not_special_provisions()
    {
        // Arrange
        $metadata = new DocumentMetadata(
            'Not a special provisions document.',
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'No special provisions found'
        );
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateDocumentAnalysis', [$metadata]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals(DocumentAnalysisStatus::CANCELLED, $this->documentAnalysis->status);
        $this->assertEquals('The document does not appear to be a special provisions document.', $this->documentAnalysis->failure_reason);
    }

    /** @test */
    public function it_updates_document_analysis_results_with_metadata()
    {
        // Arrange
        $metadata = new DocumentMetadata(
            'Test summary',
            'CN123',
            'P456',
            '$100,000',
            '2024-12-31',
            '30',
            '15%',
            'DIR789',
            'Test location',
            null,
            null,
            'LLM analysis complete'
        );
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateDocumentAnalysisResults', [$metadata]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals(DocumentAnalysisStatus::COMPLETED, $this->documentAnalysis->status);
        $this->assertEquals('Test summary', $this->documentAnalysis->document_summary);
        $this->assertEquals('CN123', $this->documentAnalysis->contract_number);
        $this->assertEquals('P456', $this->documentAnalysis->project_id);
        $this->assertEquals('$100,000', $this->documentAnalysis->engineers_estimate);
        $this->assertEquals('2024-12-31', $this->documentAnalysis->bid_due_date);
        $this->assertEquals('30', $this->documentAnalysis->number_of_working_days);
        $this->assertEquals('15%', $this->documentAnalysis->dbe_goal);
        $this->assertEquals('DIR789', $this->documentAnalysis->dir_number);
        $this->assertEquals('Test location', $this->documentAnalysis->job_location);
        $this->assertEquals('LLM analysis complete', $this->documentAnalysis->llm_response);
    }

    /** @test */
    public function it_updates_bid_items()
    {
        // Arrange
        $bidItems = [
            [
                'item_number' => '1',
                'item_code' => 'CODE1',
                'item_description' => 'Item 1',
                'unit_of_measure' => 'EA',
                'estimated_quantity' => '10'
            ],
            [
                'item_number' => '2',
                'item_code' => 'CODE2',
                'item_description' => 'Item 2',
                'unit_of_measure' => 'LF',
                'estimated_quantity' => '100'
            ]
        ];
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateBidItems', [$bidItems]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertCount(2, $this->documentAnalysis->bidItems);

        $firstBidItem = $this->documentAnalysis->bidItems->first();
        $this->assertEquals('1', $firstBidItem->item_number);
        $this->assertEquals('CODE1', $firstBidItem->item_code);
        $this->assertEquals('Item 1', $firstBidItem->item_description);
        $this->assertEquals('EA', $firstBidItem->unit_of_measure);
        $this->assertEquals('10', $firstBidItem->estimated_quantity);
    }

    /** @test */
    public function it_handles_missing_bid_item_fields()
    {
        // Arrange
        $bidItems = [
            [
                'item_number' => null,
                'item_code' => null,
                'item_description' => null,
                'unit_of_measure' => null,
                'estimated_quantity' => null
            ]
        ];
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateBidItems', [$bidItems]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertCount(1, $this->documentAnalysis->bidItems);

        $bidItem = $this->documentAnalysis->bidItems->first();
        $this->assertEquals('', $bidItem->item_number);
        $this->assertEquals('', $bidItem->item_code);
        $this->assertEquals('', $bidItem->item_description);
        $this->assertEquals('', $bidItem->unit_of_measure);
        $this->assertEquals('', $bidItem->estimated_quantity);
    }

    /** @test */
    public function it_updates_work_scope_analyses()
    {
        // Arrange
        $workScopes = [
            [
                'scope' => 'Scope 1',
                'summary' => 'Summary 1'
            ],
            [
                'scope' => 'Scope 2',
                'summary' => 'Summary 2'
            ]
        ];

        $workScope1 = WorkScope::factory()->create(['name' => 'Scope 1']);
        $workScope2 = WorkScope::factory()->create(['name' => 'Scope 2']);

        $this->documentAnalysis->workScopes()->attach([$workScope1->id, $workScope2->id]);

        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateWorkScopeAnalyses', [$workScopes]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals('Summary 1', $this->documentAnalysis->workScopes->firstWhere('name', 'Scope 1')->pivot->analysis);
        $this->assertEquals('Summary 2', $this->documentAnalysis->workScopes->firstWhere('name', 'Scope 2')->pivot->analysis);
    }

    /** @test */
    public function it_handles_empty_work_scopes()
    {
        // Arrange
        $workScopes = [];
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'updateWorkScopeAnalyses', [$workScopes]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEmpty($this->documentAnalysis->workScopes);
    }

    /** @test */
    public function it_handles_nonexistent_work_scope()
    {
        // Arrange
        $workScopes = [
            [
                'scope' => 'Nonexistent Scope',
                'summary' => 'Summary'
            ]
        ];

        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act & Assert
        $this->invokePrivateMethod($job, 'updateWorkScopeAnalyses', [$workScopes]);
        // Should not throw an error when work scope doesn't exist
    }

    /** @test */
    public function it_handles_error_and_updates_status()
    {
        // Arrange
        $error = new \Exception('Test error');
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        // Act
        $this->invokePrivateMethod($job, 'handleError', [$error]);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals(DocumentAnalysisStatus::FAILED, $this->documentAnalysis->status);
        $this->assertEquals('Test error', $this->documentAnalysis->failure_reason);
    }

    /** @test */
    public function it_processes_document_successfully()
    {
        // Arrange
        $parsedContent = 'Test parsed content';
        $metadata = new DocumentMetadata(
            'Test summary',
            'CN123',
            'P456',
            '$100,000',
            '2024-12-31',
            '30',
            '15%',
            'DIR789',
            'Test location',
            [
                [
                    'item_number' => '1',
                    'item_code' => 'CODE1',
                    'item_description' => 'Item 1',
                    'unit_of_measure' => 'EA',
                    'estimated_quantity' => '10'
                ]
            ],
            [
                [
                    'scope' => 'Scope 1',
                    'summary' => 'Summary 1'
                ]
            ],
            'LLM analysis complete'
        );

        $workScope = WorkScope::factory()->create(['name' => 'Scope 1']);
        $this->documentAnalysis->workScopes()->attach($workScope->id);

        $this->openAiService->shouldReceive('parseDocument')
            ->once()
            ->with($this->documentAnalysis->document_content)
            ->andReturn($parsedContent);

        $this->openAiService->shouldReceive('analyzeDocument')
            ->once()
            ->with($parsedContent)
            ->andReturn($metadata);

        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);
        $this->app->instance(DocumentAnalyzerContract::class, $this->openAiService);

        // Act
        $job->handle($this->openAiService);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals($parsedContent, $this->documentAnalysis->parsed_content);
        $this->assertEquals(DocumentAnalysisStatus::COMPLETED, $this->documentAnalysis->status);
        $this->assertEquals('Test summary', $this->documentAnalysis->document_summary);
        $this->assertCount(1, $this->documentAnalysis->bidItems);
        $this->assertEquals('Summary 1', $this->documentAnalysis->workScopes->first()->pivot->analysis);
    }

    /** @test */
    public function it_handles_error_during_document_processing()
    {
        // Arrange
        $error = new \Exception('Processing error');
        $job = new ProcessDocumentForAnalysis($this->documentAnalysis);

        $this->openAiService->shouldReceive('parseDocument')
            ->once()
            ->with($this->documentAnalysis->document_content)
            ->andThrow($error);

        // Act
        $job->handle($this->openAiService);

        // Assert
        $this->documentAnalysis->refresh();
        $this->assertEquals(DocumentAnalysisStatus::FAILED, $this->documentAnalysis->status);
        $this->assertEquals('Processing error', $this->documentAnalysis->failure_reason);
    }

    /** @test */
    public function it_updates_document_with_empty_bid_items(): void
    {
        $documentMetadata = new DocumentMetadata(
            'Test summary',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            null,
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('update')
            ->once()
            ->with([
                'status' => DocumentAnalysisStatus::COMPLETED,
                'document_summary' => 'Test summary',
                'contract_number' => 'Contract123',
                'project_id' => 'Project123',
                'engineers_estimate' => '1000000',
                'bid_due_date' => '2024-12-31',
                'number_of_working_days' => '30',
                'dbe_goal' => '10%',
                'dir_number' => 'DIR123',
                'job_location' => 'Test Location',
                'llm_response' => 'LLM analysis complete'
            ]);

        $this->invokePrivateMethod($this->job, 'updateDocumentAnalysisResults', [$documentMetadata]);
    }

    /** @test */
    public function it_updates_document_with_partial_bid_items(): void
    {
        $bidItems = [
            [
                'item_number' => '1',
                // Missing item_code
                'item_description' => 'Test Item',
                // Missing unit_of_measure
                'estimated_quantity' => '100'
            ]
        ];

        $documentMetadata = new DocumentMetadata(
            'Test summary',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            $bidItems,
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('shouldReceive')->once();
        $this->documentAnalysis->shouldReceive('bidItems->createMany')
            ->once()
            ->with(Mockery::on(function ($items) {
                return $items[0]['item_code'] === '' && $items[0]['unit_of_measure'] === '';
            }));
        $this->documentAnalysis->shouldReceive('bidItems->saveMany')->once();

        $this->invokePrivateMethod($this->job, 'updateDocumentAnalysisResults', [$documentMetadata]);
    }

    /** @test */
    public function it_updates_document_with_empty_work_scopes(): void
    {
        $documentMetadata = new DocumentMetadata(
            'Test summary',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            null,
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('update')->once();
        $this->documentAnalysis->shouldReceive('getAttribute')
            ->with('workScopes')
            ->andReturn(collect([]));

        $this->invokePrivateMethod($this->job, 'updateWorkScopeAnalyses', [[]]);
    }

    /** @test */
    public function it_handles_nonexistent_work_scope_safely(): void
    {
        $workScopes = [
            ['scope' => 'NonexistentScope', 'summary' => 'Test Summary']
        ];

        $this->documentAnalysis->shouldReceive('getAttribute')
            ->with('workScopes')
            ->andReturn(collect([]));

        $this->invokePrivateMethod($this->job, 'updateWorkScopeAnalyses', [$workScopes]);
    }

    /** @test */
    public function it_handles_error_with_detailed_message(): void
    {
        $error = new Exception('Test error message');

        $this->documentAnalysis->shouldReceive('update')
            ->once()
            ->with([
                'status' => DocumentAnalysisStatus::FAILED->value,
                'failure_reason' => 'Test error message'
            ]);

        $this->invokePrivateMethod($this->job, 'handleError', [$error]);
    }

    /** @test */
    public function it_handles_not_special_provisions_document(): void
    {
        $documentMetadata = new DocumentMetadata(
            'Not a special provisions document.',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            null,
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('update')
            ->once()
            ->with([
                'status' => DocumentAnalysisStatus::CANCELLED,
                'failure_reason' => 'The document does not appear to be a special provisions document.'
            ]);

        $this->invokePrivateMethod($this->job, 'updateDocumentAnalysis', [$documentMetadata]);
    }

    /** @test */
    public function it_updates_document_with_missing_bid_item_fields(): void
    {
        $bidItems = [
            [
                'item_number' => null,
                'item_code' => null,
                'item_description' => null,
                'unit_of_measure' => null,
                'estimated_quantity' => null
            ]
        ];

        $documentMetadata = new DocumentMetadata(
            'Test summary',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            $bidItems,
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('update')->once();
        $this->documentAnalysis->shouldReceive('bidItems->createMany')
            ->once()
            ->with(Mockery::on(function ($items) {
                return $items[0]['item_number'] === '' &&
                    $items[0]['item_code'] === '' &&
                    $items[0]['item_description'] === '' &&
                    $items[0]['unit_of_measure'] === '' &&
                    $items[0]['estimated_quantity'] === '';
            }));
        $this->documentAnalysis->shouldReceive('bidItems->saveMany')->once();

        $this->invokePrivateMethod($this->job, 'updateDocumentAnalysisResults', [$documentMetadata]);
    }

    /** @test */
    public function it_updates_document_with_null_bid_items(): void
    {
        $documentMetadata = new DocumentMetadata(
            'Test summary',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            null,
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('update')->once();
        $this->documentAnalysis->shouldNotReceive('bidItems->createMany');
        $this->documentAnalysis->shouldNotReceive('bidItems->saveMany');

        $this->invokePrivateMethod($this->job, 'updateDocumentAnalysisResults', [$documentMetadata]);
    }

    /** @test */
    public function it_updates_document_with_empty_bid_items_array(): void
    {
        $documentMetadata = new DocumentMetadata(
            'Test summary',
            'Contract123',
            'Project123',
            '1000000',
            '2024-12-31',
            '30',
            '10%',
            'DIR123',
            'Test Location',
            [],
            null,
            'LLM analysis complete'
        );

        $this->documentAnalysis->shouldReceive('update')->once();
        $this->documentAnalysis->shouldNotReceive('bidItems->createMany');
        $this->documentAnalysis->shouldNotReceive('bidItems->saveMany');

        $this->invokePrivateMethod($this->job, 'updateDocumentAnalysisResults', [$documentMetadata]);
    }

    /** @test */
    public function it_updates_work_scopes_with_missing_fields(): void
    {
        $workScopes = [
            ['scope' => 'TestScope', 'summary' => null]
        ];

        $this->documentAnalysis->shouldReceive('getAttribute')
            ->with('workScopes')
            ->andReturn(collect([
                (object)['name' => 'TestScope']
            ]));

        $this->documentAnalysis->workScopes->first()
            ->shouldReceive('update')
            ->once()
            ->with(['analysis' => null]);

        $this->invokePrivateMethod($this->job, 'updateWorkScopeAnalyses', [$workScopes]);
    }

    /** @test */
    public function it_updates_work_scopes_with_empty_array(): void
    {
        $this->documentAnalysis->shouldReceive('getAttribute')
            ->with('workScopes')
            ->andReturn(collect([]));

        $this->invokePrivateMethod($this->job, 'updateWorkScopeAnalyses', [[]]);
    }

    /** @test */
    public function it_updates_work_scopes_with_invalid_scope(): void
    {
        $workScopes = [
            ['scope' => 'NonexistentScope', 'summary' => 'Test Summary']
        ];

        $this->documentAnalysis->shouldReceive('getAttribute')
            ->with('workScopes')
            ->andReturn(collect([
                (object)['name' => 'DifferentScope']
            ]));

        $this->invokePrivateMethod($this->job, 'updateWorkScopeAnalyses', [$workScopes]);
    }

    /** @test */
    public function it_handles_error_with_null_message(): void
    {
        $error = new Exception();

        $this->documentAnalysis->shouldReceive('update')
            ->once()
            ->with([
                'status' => DocumentAnalysisStatus::FAILED->value,
                'failure_reason' => ''
            ]);

        $this->invokePrivateMethod($this->job, 'handleError', [$error]);
    }

    private function invokePrivateMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);
        return $method->invokeArgs($object, $parameters);
    }
}
