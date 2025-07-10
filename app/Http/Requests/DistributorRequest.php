<?php

namespace App\Http\Requests;

use App\Models\Distributor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DistributorRequest extends FormRequest
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
        $distributorId = $this->route('distributor')->id ?? null;

        $rules = [
            'firstname' => ['required', 'string', 'max:50'],
            'lastname' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Distributor::class)->ignore($distributorId)],
            'mobile' => ['required', 'numeric', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique(Distributor::class)->ignore($distributorId)],
            // 'shop_name' => ['required'],
            'country_id' => ['required'],
            'state_id' => ['required'],
            'city_id' => ['required'],
            'current_dealership' => ['required'],
            'area_of_operation' => ['required'],
            // 'area_id' => ['required'],
            // 'address' => ['required'],
            'zone_id' => ['required', 'exists:zones,id'],
            // 'pan_no' => ['required'],
            // 'cst_gst_no' => ['required'],
        ];

        if (empty($this->edit_pan_doc)) {
            // $rules['pan_no'] =  ['required','max:2048'];
        }

        if (empty($this->edit_gst_doc)) {
            // $rules['gst_doc'] =  ['required','max:2048'];
        }

        return $rules;
    }
}
