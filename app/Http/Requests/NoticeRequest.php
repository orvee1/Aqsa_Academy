<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NoticeRequest extends FormRequest
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
        $id = $this->route('notice')?->id;

        return [
            'title' => ['required','string','max:255'],
            'slug'  => ['nullable','string','max:255', Rule::unique('notices','slug')->ignore($id)],
            'body'  => ['nullable','string'],

            'file'  => ['nullable','file','max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png'],

            'published_at' => ['nullable','date'],
            'expires_at'   => ['nullable','date','after_or_equal:published_at'],

            'is_published' => ['nullable','boolean'],
            'is_hidden'    => ['nullable','boolean'],
            'is_pinned'    => ['nullable','boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_published' => $this->boolean('is_published'),
            'is_hidden'    => $this->boolean('is_hidden'),
            'is_pinned'    => $this->boolean('is_pinned'),
        ]);
    }
}
