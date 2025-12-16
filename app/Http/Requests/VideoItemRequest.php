<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoItemRequest extends FormRequest
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
            'title'       => ['required', 'string', 'max:255'],
            'youtube_url' => ['required', 'string', 'max:500', function ($attr, $value, $fail) {
                $v = trim((string) $value);

                $ok =
                preg_match('~youtu\.be/([A-Za-z0-9_-]{6,})~', $v) ||
                preg_match('~youtube\.com/watch\?v=([A-Za-z0-9_-]{6,})~', $v) ||
                preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~', $v) ||
                preg_match('~youtube\.com/shorts/([A-Za-z0-9_-]{6,})~', $v);

                if (! $ok) {
                    $fail('Please provide a valid YouTube URL.');
                }

            }],

            'thumbnail'   => ['nullable', 'image', 'max:4096'], // 4MB

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
