<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryRequest extends FormRequest
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
        $countryId = $this->country_id ?? null;

        return [
            'name' => 'required|min:2|max:30|unique:countries,name,'.$countryId.',id,deleted_at,NULL',
            'code' => 'required|max:5|unique:countries,code,'.$countryId.',id,deleted_at,NULL',
            'sort_order' => 'nullable|numeric|unique:countries,sort_order,'.$countryId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
