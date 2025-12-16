<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatementRequest extends FormRequest
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
            'title'              => ['nullable', 'string', 'max:255'],
            'body'               => ['required', 'string'],

            'author_name'        => ['nullable', 'string', 'max:190'],
            'author_designation' => ['nullable', 'string', 'max:190'],

            'author_photo'       => ['nullable', 'image', 'max:4096'], // 4MB

            'position'           => ['nullable', 'integer', 'min:0'],
            'status'             => ['nullable', 'boolean'],
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
