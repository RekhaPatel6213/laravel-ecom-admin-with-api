<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
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
        $orderstatusId = $this->route('orderstatus')->id ?? null;

        return [
            'order_status_name' => 'required|min:2|max:30|unique:order_statuses,order_status_name,'.$orderstatusId.',id,deleted_at,NULL',
            // 'status' => 'required',
        ];
    }
}
