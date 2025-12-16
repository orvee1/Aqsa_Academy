<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        $id = $this->route('menu')?->id;

        return [
            'name'     => ['required', 'string', 'max:190'],
            'location' => ['required', 'string', 'max:50'],
            'status'   => ['nullable', 'boolean'],

            // optional unique by location
            // 'name' => ['required','string','max:190', Rule::unique('menus','name')->ignore($id)->where(fn($q)=>$q->where('location',$this->location))],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['status' => $this->boolean('status')]);
    }
}
