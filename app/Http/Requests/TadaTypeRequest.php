<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TadaTypeRequest extends FormRequest
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
        $tadaTypeId = $this->route('tadatype')->id ?? null;

        return [
            'name' => 'required|min:2|max:30|unique:tada_types,name,'.$tadaTypeId.',id,deleted_at,NULL',
            // 'type' => ['required', Rule::in([config('constants.PHOTO'), config('constants.KM')])],
        ];
    }
}
