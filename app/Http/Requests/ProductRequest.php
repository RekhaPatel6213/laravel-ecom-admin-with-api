<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $productId = $this->product_id ?? null;

        $validationRules = [
            'category_type_id' => ['required', 'exists:category_types,id'],
            'category_id' => 'required',
            'name' => 'required|min:2|max:100|unique:products,name,'.$productId.',id',
            'code' => 'required|min:4|max:5|unique:products,code,'.$productId.',id',
            'sort_order' => 'nullable|numeric',
            // 'status' => 'required',
            // 'gst' => 'required',
            // 'cgst' => 'required',
            // 'sgst' => 'required',
        ];

        if ($productId === null) {
            $validationRules['image'] = ['required'];
        }

        return $validationRules;
    }
}
