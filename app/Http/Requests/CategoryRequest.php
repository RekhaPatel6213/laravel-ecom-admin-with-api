<?php

namespace App\Http\Requests;

use App\Rules\IsParentCategoryRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $categoryId = $this->category_id ?? null;
        $this->is_parent = $this->is_parent ?? 0;

        return [
            'name' => 'required|min:2|max:30|unique:categories,name,'.$categoryId.',id,deleted_at,NULL',
            'is_parent' => [new IsParentCategoryRule($this->input('parent_category_id'), $this->is_parent)], // 'required_with:parent_category_id',
            'parent_category_id' => ['required_if:is_parent,0'],
            'category_type_id' => ['required', 'exists:category_types,id'],
            'sort_order' => 'nullable|numeric',
            // 'status' => 'required',
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
            'is_parent.in' => 'Is Parent should be Yes.',
            'parent_category_id.required_if' => 'The parent category id field is required when is parent is No.',
        ];
    }
}
