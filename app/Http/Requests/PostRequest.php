<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $id = $this->route('post')?->id;

        return [
            'post_category_id' => ['nullable', 'exists:post_categories,id'],

            'title'            => ['required', 'string', 'max:255'],
            'slug'             => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($id)],

            'excerpt'          => ['nullable', 'string'],
            'content'          => ['nullable', 'string'],

            'featured_image'   => ['nullable', 'image', 'max:5120'], // 5MB

            'status'           => ['required', Rule::in(['draft', 'published'])],
            'published_at'     => ['nullable', 'date'],
        ];
    }
}
