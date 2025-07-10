<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
        $cityId = $this->city_id ?? null;

        return [
            'state_id' => 'required',
            'name' => 'required|min:2|max:30|unique:cities,name,'.$cityId.',id,deleted_at,NULL',
            'code' => 'nullable|max:5|unique:cities,code,'.$cityId.',id,deleted_at,NULL',
            'sort_order' => 'nullable|numeric|unique:cities,sort_order,'.$cityId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
