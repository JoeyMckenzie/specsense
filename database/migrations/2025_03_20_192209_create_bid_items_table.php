<?php

declare(strict_types=1);

use App\Models\DocumentAnalysis;
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
        Schema::create('bid_items', function (Blueprint $table): void {
            $table->id();
            $table->string('item_number')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_description')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->string('estimated_quantity')->nullable();
            $table->timestamps();

            $table->foreignIdFor(DocumentAnalysis::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bid_items');
    }
};
