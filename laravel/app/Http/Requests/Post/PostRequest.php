<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'             => 'sometimes|nullable|string',
            'body'              => 'sometimes|nullable|string',
            'images'            => 'sometimes|nullable|array',
            'images.*'          => 'sometimes|nullable|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'videos'            => 'sometimes|nullable|array',
            'videos.*'          => 'sometimes|nullable|file|mimes:mp4,mov,avi,webm|max:51200',
            'comments_enabled'  => 'sometimes|nullable|boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                => 'Title is required.',
            'title.string'                  => 'Title must be a string.',
            'images.*.string'               => 'Images must be an array of strings.',
            'videos.*.string'               => 'Videos must be an array of strings.',
            'comments_enabled.boolean'      => 'Comments enabled must be a boolean.'
        ];
    }
}
