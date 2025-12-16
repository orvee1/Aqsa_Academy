<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FooterSettingRequest extends FormRequest
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
            'about'          => ['nullable', 'string'],
            'address'        => ['nullable', 'string', 'max:1000'],
            'phone'          => ['nullable', 'string', 'max:80'],
            'email'          => ['nullable', 'email', 'max:190'],
            'map_embed'      => ['nullable', 'string'],
            'copyright_text' => ['nullable', 'string', 'max:255'],
        ];
    }
}
