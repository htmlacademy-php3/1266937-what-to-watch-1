<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class StoreCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'text' => [
                'required',
                'string',
                'min:50',
                'max:400'
            ],
            'rating' => [
                'required',
                'integer',
                'min:1',
                'max:10'
            ],
            'comment_id' => [
                'nullable',
                'integer',
                Rule::exists('comments', 'id'),
            ],
        ];
    }
}
