<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\FlashMessageStatus;
use App\Http\Requests\Documents\CreateBidItemRequest;
use App\Http\Requests\Documents\UpdateBidItemRequest;
use App\Models\BidItem;
use App\Models\Document;
use App\Models\DocumentAnalysis;
use Illuminate\Http\RedirectResponse;

/**
 * @phpstan-type BidItemFormSchema array{
 *     item_number: string,
 *     item_code: string,
 *     item_description: string,
 *     unit_of_measure: string,
 *     estimated_quantity: string
 * }
 */
final class BidItemController extends Controller
{
    public function store(CreateBidItemRequest $request, Document $document, DocumentAnalysis $documentAnalysis): RedirectResponse
    {
        /** @var BidItemFormSchema $validated */
        $validated = $request->validated();
        $documentAnalysis->bidItems()->create([
            'document_analysis_id' => $documentAnalysis->id,
            ...$validated,
        ]);

        return back()->with(FlashMessageStatus::SUCCESS->value, 'Bid item created successfully.');
    }

    public function update(UpdateBidItemRequest $request, BidItem $bidItem): RedirectResponse
    {
        /** @var BidItemFormSchema $validated */
        $validated = $request->validated();
        $bidItem->update($validated);

        return back()->with(FlashMessageStatus::SUCCESS->value, "Bid item $bidItem->item_code updated successfully.");
    }

    public function destroy(Document $document, DocumentAnalysis $documentAnalysis, BidItem $bidItem): RedirectResponse
    {
        $bidItem->delete();

        return back()->with(FlashMessageStatus::SUCCESS->value, "Bid item $bidItem->item_code deleted successfully.");
    }
}
