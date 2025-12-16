<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuItemRequest extends FormRequest
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
            'menu_id'          => ['required', 'exists:menus,id'],
            'parent_id'        => ['nullable', 'exists:menu_items,id'],

            'label_bn'         => ['required', 'string', 'max:190'],
            'label_en'         => ['nullable', 'string', 'max:190'],

            'type'             => ['required', Rule::in(['url', 'page', 'post_category', 'route'])],

            'url'              => ['nullable', 'string'],
            'page_id'          => ['nullable', 'integer'],
            'post_category_id' => ['nullable', 'integer'],
            'route_name'       => ['nullable', 'string', 'max:190'],

            'position'         => ['nullable', 'integer', 'min:0'],
            'open_new_tab'     => ['nullable', 'boolean'],
            'status'           => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'status'       => $this->boolean('status'),
            'open_new_tab' => $this->boolean('open_new_tab'),
            'position'     => $this->input('position', 0),
        ]);
    }
}
