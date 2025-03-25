<?php

declare(strict_types=1);

namespace App\Http\Requests\Documents;

use App\Http\Concerns\HasVerifiedUser;
use App\Models\Document;
use App\Models\DocumentAnalysis;
use Illuminate\Foundation\Http\FormRequest;

final class CreateBidItemRequest extends FormRequest
{
    use HasVerifiedUser;

    public function authorize(): bool
    {
        /** @var Document $document */
        $document = $this->route('document');

        /** @var DocumentAnalysis $documentAnalysis */
        $documentAnalysis = $this->route('documentAnalysis');

        return $document !== null && $documentAnalysis !== null && $document->user_id === $this->verifiedUser()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string[]>
     */
    public function rules(): array
    {
        return [
            'item_number' => ['nullable', 'string', 'max:255'],
            'item_code' => ['nullable', 'string', 'max:255'],
            'item_description' => ['nullable', 'string', 'max:255'],
            'unit_of_measure' => ['nullable', 'string', 'max:255'],
            'estimated_quantity' => ['nullable', 'string', 'max:255'],
        ];
    }
}
