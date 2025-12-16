<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstituteRequest extends FormRequest
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
            'name'         => ['required', 'string', 'max:190'],
            'slogan'       => ['nullable', 'string', 'max:190'],
            'address'      => ['nullable', 'string', 'max:255'],

            'eiin'         => ['nullable', 'string', 'max:50'],
            'school_code'  => ['nullable', 'string', 'max:50'],
            'college_code' => ['nullable', 'string', 'max:50'],

            'phone_1'      => ['nullable', 'string', 'max:50'],
            'phone_2'      => ['nullable', 'string', 'max:50'],
            'mobile_1'     => ['nullable', 'string', 'max:50'],
            'mobile_2'     => ['nullable', 'string', 'max:50'],

            'link_1'       => ['nullable', 'string'],
            'link_2'       => ['nullable', 'string'],
            'link_3'       => ['nullable', 'string'],

            'status'       => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status'),
        ]);
    }
}
