<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile_no' => 'nullable|digits:10|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'flat' => 'required',
            // 'area' => 'required',
            // 'landmark' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            // 'area_id' => 'required',
            'pincode' => 'required', // |min:6',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First Name field is required.',
            'last_name.required' => 'Last Name field is required.',
            'last_name.required' => 'Last Name field is required.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Email field must be email address.',
            'mobile_no.required' => 'Mobile Number field is required.',
            'mobile_no.digits' => 'Please Enter 10 Digit Mobile Number.',
            'mobile_no.min' => 'Please Enter Minimum 10 Digit Mobile Number.',
            'flat.required' => 'Please Enter Flat, House, Building, Company, Apartment.',
            'area.required' => 'Please Enter Area, Colony, Street, Sector, Village',
            'landmark.required' => 'Please Enter Landmark',
            'country_id.required' => 'Please Select Country',
            'state_id.required' => 'Please Select State',
            'city_id.required' => 'Please Select City',
            // 'area_id.required' => 'Please Select Area',
            'pincode.required' => 'Please Enter Pincode',
            'pincode.min' => 'Please Enter Minimum 6 Digit Pincode',
        ];
    }
}
