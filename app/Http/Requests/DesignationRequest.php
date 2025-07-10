<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesignationRequest extends FormRequest
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
        $designationId = $this->designation_id ?? null;

        return [
            'name' => 'required|min:2|max:30|unique:designations,name,'.$designationId.',id,deleted_at,NULL',
            'sort_order' => 'nullable|numeric|unique:designations,sort_order,'.$designationId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
