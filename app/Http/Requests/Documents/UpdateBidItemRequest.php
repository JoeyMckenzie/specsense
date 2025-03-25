<?php

declare(strict_types=1);

namespace App\Http\Requests\Documents;

use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

final class UpdateBidItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        $documentId = $this->route('document');

        if (!is_string($documentId) || $this->user() === null) {
            return false;
        }

        /** @var User $user */
        $user = $this->user();

        $document = Document::query()
            ->firstWhere([
                'id' => $documentId,
                'user_id' => $user->id,
            ])->get(['id']);

        if ($document === null) {
            return false;
        }

        return true;
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
