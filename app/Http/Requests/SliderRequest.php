<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
            'title'    => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],

            'image'    => [$this->isMethod('post') ? 'required' : 'nullable', 'image', 'max:6144'], // 6MB

            'link_url' => ['nullable', 'string', 'max:500'],

            'position' => ['nullable', 'integer', 'min:0'],
            'status'   => ['nullable', 'boolean'],

            'start_at' => ['nullable', 'date'],
            'end_at'   => ['nullable', 'date', 'after_or_equal:start_at'],
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
