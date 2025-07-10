<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariantTypeRequest extends FormRequest
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
        $variantTypeId = $this->varianttype_id ?? null;

        return [
            'name' => 'required|min:2|max:30|unique:variant_types,name,'.$variantTypeId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
