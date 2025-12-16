<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user')?->id;

        return [
            'name'  => ['required','string','max:120'],
            'email' => ['required','email','max:190',"unique:users,email,{$userId},id,deleted_at,NULL"],
            'phone' => ['required','string','max:30',"unique:users,phone,{$userId},id,deleted_at,NULL"],

            'is_super_admin' => ['nullable','boolean'],
            'role_id' => [
                'nullable','exists:roles,id',
                function($attr,$val,$fail){
                    if (!$this->boolean('is_super_admin') && empty($val)) {
                        $fail('Role is required for non-super admin users.');
                    }
                }
            ],

            // edit এ password optional
            'password' => ['nullable','string','min:6','max:100','confirmed'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_super_admin' => $this->boolean('is_super_admin'),
        ]);
    }
}
