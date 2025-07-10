<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StateRequest extends FormRequest
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
        $stateId = $this->state_id ?? null;

        return [
            'country_id' => 'required',
            'name' => 'required|min:2|max:30|unique:states,name,'.$stateId.',id,deleted_at,NULL',
            'code' => 'nullable|max:5|unique:states,code,'.$stateId.',id,deleted_at,NULL',
            'sort_order' => 'nullable|numeric|unique:states,sort_order,'.$stateId.',id,deleted_at,NULL',
            'zone_id' => 'nullable',
            // 'status' => 'required',
        ];
    }
}
