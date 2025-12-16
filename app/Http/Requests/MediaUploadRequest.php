<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
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
            'disk'    => ['nullable', 'string', 'max:50'],
            'files'   => ['required', 'array', 'min:1'],
            'files.*' => ['file', 'max:10240'], // 10MB each (need bigger? বললেই বাড়িয়ে দেব)
        ];
    }
}
