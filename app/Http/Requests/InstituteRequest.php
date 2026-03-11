<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstituteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['required', 'string', 'max:190'],
            'slogan'           => ['nullable', 'string', 'max:190'],
            'address'          => ['nullable', 'string', 'max:255'],

            'eiin'             => ['nullable', 'string', 'max:50'],
            'school_code'      => ['nullable', 'string', 'max:50'],
            'college_code'     => ['nullable', 'string', 'max:50'],

            'phone_1'          => ['nullable', 'string', 'max:50'],
            'phone_2'          => ['nullable', 'string', 'max:50'],
            'mobile_1'         => ['nullable', 'string', 'max:50'],
            'mobile_2'         => ['nullable', 'string', 'max:50'],

            'logo'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'header_banner'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'online_apply_url' => ['nullable', 'url', 'max:255'],

            'link_1'           => ['nullable', 'url'],
            'link_2'           => ['nullable', 'url'],
            'link_3'           => ['nullable', 'url'],

            'status'           => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status' => $this->boolean('status'),
        ]);
    }
}
