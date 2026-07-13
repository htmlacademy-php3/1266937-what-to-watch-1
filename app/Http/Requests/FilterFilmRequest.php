<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\FilmStatus;

class FilterFilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('status') || $this->input('status') === null) {
            $this->merge([
                'status' => FilmStatus::Ready->value,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => [
                'nullable',
                'integer',
                'min:1'
            ],
            'genre' => [
                'nullable',
                'max:255',
                Rule::exists('genres', 'name'),
            ],
            'status' => [
                'nullable',
                'string',
                Rule::enum(FilmStatus::class)
            ],
            'order_by' => [
                'nullable',
                'string',
                Rule::in(['released', 'rating'])
            ],
            'order_to' => [
                'nullable',
                'string',
                Rule::in(['asc', 'desc'])
            ],
        ];
    }
}
