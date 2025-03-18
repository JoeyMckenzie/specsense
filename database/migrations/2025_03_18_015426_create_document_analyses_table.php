<?php

declare(strict_types=1);

use App\Enums\DocumentAnalysisStatus;
use App\Models\Document;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_analyses', function (Blueprint $table) {
            $table->id();
            $table->string('context')->nullable();
            $table->text('parsed_content')->nullable();
            $table->enum('status', DocumentAnalysisStatus::toArray());
            $table->text('document_summary')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('llm_response')->nullable();
            $table->string('contract_number')->nullable();
            $table->string('project_id')->nullable();
            $table->string('engineers_estimate')->nullable();
            $table->string('bid_due_date')->nullable();
            $table->string('number_of_working_days')->nullable();
            $table->string('dbe_goal')->nullable();
            $table->string('dir_number')->nullable();
            $table->string('job_location')->nullable();
            $table->timestamps();

            $table->foreignIdFor(Document::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_analyses');
    }
};
