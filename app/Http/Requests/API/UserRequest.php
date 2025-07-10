<?php

namespace App\Http\Requests\API;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = Auth::user()->id ?? null;
        $firstName = $this->firstname ?? null;
        $lastName = $this->lastname ?? null;

        $rules = [
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            // 'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($userId)],
            // 'mobile' => ['required', 'numeric', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique(User::class)->ignore($userId)],
            // 'designation_id' => ['numeric', 'not_in:0']
        ];

        if ($userId === null || ($this->password !== null && $this->confirm_password !== null)) {
            $rules['password'] = [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',      // at least one uppercase letter
                'regex:/[a-z]/',      // at least one lowercase letter
                'regex:/[0-9]/',      // at least one digit
                'regex:/[@$!%*?&]/',  // at least one special character
                function ($attribute, $value, $fail) use ($firstName, $lastName) {
                    if (stripos($value, $firstName) !== false || stripos($value, $lastName) !== false) {
                        $fail('The :attribute should not contain your first or last name.');
                    }
                },
                // 'confirmed',
                // Password::defaults()
            ];
            $rules['confirm_password'] = ['required', 'same:password'];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a valid string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     *
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'success' => false,
            'message' => 'Validation Error.',
            'data' => $validator->errors(),
        ];

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}
