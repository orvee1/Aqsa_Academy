<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventRequest extends FormRequest
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
        $id = $this->route('event')?->id;

        return [
            'title'       => ['required', 'string', 'max:255'],
            'slug'        => ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($id)],

            'description' => ['nullable', 'string'],

            'event_date'  => ['required', 'date'],
            'event_time'  => ['nullable', 'date_format:H:i'],
            'venue'       => ['nullable', 'string', 'max:255'],

            'cover_image' => ['nullable', 'image', 'max:6144'], // 6MB

            'status'      => ['required', Rule::in(['draft', 'published'])],
        ];
    }
}
