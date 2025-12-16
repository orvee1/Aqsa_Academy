<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FooterLinkRequest extends FormRequest
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
            'group_title' => ['nullable', 'string', 'max:190'],
            'title'       => ['required', 'string', 'max:190'],
            'url'         => ['required', 'string', 'max:800'],
            'position'    => ['nullable', 'integer', 'min:0'],
            'status'      => ['nullable', 'boolean'],
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
