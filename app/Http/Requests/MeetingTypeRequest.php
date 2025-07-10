<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MeetingTypeRequest extends FormRequest
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
        $meetingTypeId = $this->meetingtype_id ?? null;

        return [
            'name' => 'required|min:2|max:30|unique:meeting_types,name,'.$meetingTypeId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
