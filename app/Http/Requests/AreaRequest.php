<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
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
        $areaId = $this->area_id ?? null;

        return [
            'distributor_id' => 'required',
            'name' => 'required|min:2|max:30|unique:areas,name,'.$areaId.',id,deleted_at,NULL',
            // 'pincode' => 'nullable|max:6',
            'sort_order' => 'nullable|numeric|unique:areas,sort_order,'.$areaId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
