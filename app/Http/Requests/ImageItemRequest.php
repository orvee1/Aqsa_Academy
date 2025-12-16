<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageItemRequest extends FormRequest
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
            'album_id' => ['required', 'exists:image_albums,id'],

            // bulk upload
            'images'   => ['nullable', 'array'],
            'images.*' => ['image', 'max:6144'], // 6MB each

            // single item update/create
            'image'    => ['nullable', 'image', 'max:6144'],

            'title'    => ['nullable', 'string', 'max:255'],
            'caption'  => ['nullable', 'string', 'max:255'],

            'position' => ['nullable', 'integer', 'min:0'],
            'status'   => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status'   => $this->boolean('status'),
            'position' => $this->input('position', 0),
        ]);
    }
}
