<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'  => ['required','string','max:120'],
            'email' => ['required','email','max:190','unique:users,email,NULL,id,deleted_at,NULL'],
            'phone' => ['required','string','max:30','unique:users,phone,NULL,id,deleted_at,NULL'],

            'is_super_admin' => ['nullable','boolean'],
            'role_id' => [
                // super admin না হলে role লাগবে
                'nullable',
                'exists:roles,id',
                function($attr,$val,$fail){
                    if (!$this->boolean('is_super_admin') && empty($val)) {
                        $fail('Role is required for non-super admin users.');
                    }
                }
            ],

            'password' => ['required','string','min:6','max:100','confirmed'],
        ];
    }

     protected function prepareForValidation(): void
    {
        $this->merge([
            'is_super_admin' => $this->boolean('is_super_admin'),
        ]);
    }
}
