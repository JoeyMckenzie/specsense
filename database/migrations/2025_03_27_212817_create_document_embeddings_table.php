<?php

declare(strict_types=1);

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
        Schema::create('document_embeddings', function (Blueprint $table): void {
            $table->id();
            $table->vector('embedding', 1536); // Dimensionality; 1536 for OpenAI's ada-002
            $table->text('content');
            $table->integer('page');
            $table->string('path');
            $table->timestamps();

            $table->foreignIdFor(Document::class)->constrained()->cascadeOnDelete();
        });

        // This is a Postgres-specific index that allows us to do fast nearest-neighbor searches
        // when there are a lot of high-dimensional embeddings in the database.
        DB::statement('CREATE INDEX embedding_index ON document_embeddings USING ivfflat (embedding vector_l2_ops) WITH (lists = 100)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_embeddings');
    }
};
