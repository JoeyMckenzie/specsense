<?php

use App\Enums\DocumentType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('name');
            $table->string('original_filename');
            $table->string('filename');
            $table->string('path');
            $table->string('size');
            $table->enum('type', DocumentType::toArray());
            $table->timestamps();

            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
