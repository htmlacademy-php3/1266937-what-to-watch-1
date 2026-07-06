<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\FilmStatus;

class UpdateFilmRequest extends FormRequest
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
        $film = $this->route('film');

        return [
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'poster_image' => [
                'nullable',
                'string',
                'max:255'
            ],
            'preview_image' => [
                'nullable',
                'string',
                'max:255'
            ],
            'background_image' => [
                'nullable',
                'string',
                'max:255'
            ],
            'background_color' => [
                'nullable',
                'string',
                'max:9'
            ],
            'video_link' => [
                'nullable',
                'string',
                'max:255'
            ],
            'preview_video_link' => [
                'nullable',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ],
            'directors' => [
                'nullable',
                'array'
            ],
            'directors.*' => [
                'string',
                'max:255'
            ],
            'starring' => [
                'nullable',
                'array'
            ],
            'starring.*' => [
                'string',
                'max:255'
            ],
            'genre' => [
                'nullable',
                'array'
            ],
            'genre.*' => [
                'string',
                'max:255'
            ],
            'run_time' => [
                'nullable',
                'integer',
            ],
            'released' => [
                'nullable',
                'integer',
            ],

            'imdb_id' => [
                'required',
                'string',
                'regex:/^tt\d+$/',
                Rule::unique('films', 'imdb_id')->ignore($film?->id),
            ],

            'status' => [
                'required',
                'string',
                Rule::enum(FilmStatus::class)
            ],
        ];
    }
}
