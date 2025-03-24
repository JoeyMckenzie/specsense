<?php

declare(strict_types=1);

namespace App\Http\Requests\Documents;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;

final class CreateDocumentAnalysisRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, Unique|string>>
     */
    public function rules(): array
    {
        $this->route('document');

        return [
            'additional_info' => ['nullable', 'max:255'],
            'work_scopes' => [
                'nullable',
                'array',
                'max:5',
                'distinct',
            ],
            'work_scopes.*' => ['string', 'max:30'],
            'document_id' => [
                'required',
                Rule::unique('document_analyses')->where(fn (Builder $query) => $query->where('document_id', $this['document_id'])),
            ],
        ];
    }
}
