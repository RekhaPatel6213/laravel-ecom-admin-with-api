<?php

namespace App\Http\Requests\API;

use App\Models\TadaType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TadaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function all($key = null)
    {
        $request = parent::all($key);
        $tadaType = TadaType::find($request['tadatype_id']);
        $request['is_date'] = $tadaType->is_date ?? 0;
        $request['is_amount'] = $tadaType->is_amount ?? 0;
        $request['is_photo'] = $tadaType->is_photo ?? 0;
        $request['is_location'] = $tadaType->is_location ?? 0;
        $request['is_from_to_location'] = $tadaType->is_from_to_location ?? 0;
        $request['is_km'] = $tadaType->is_km ?? 0;
        $request['is_expense_name'] = $tadaType->is_expense_name ?? 0;


        \Log::info($request);

        // $request['type'] = !empty($tadaType) ? $tadaType->type : config('constants.KM');
        return $request;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'tadatype_id' => ['required'],
            // 'daily_allowance' => ['required', 'decimal:0,2'],
        ];

        // if($this->is_date == 1) {
        $rules['date'] = ['required', 'date'];
        // }

        if ($this->is_expense_name == 1) {
            $rules['expense_name'] = ['required'];
        }

        if ($this->is_amount == 1) {
            $rules['amount'] = ['required'];
        }

        if ($this->is_photo == 1) {
            $rules['photo'] = ['required', 'max:2048']; // ,'image','mimes:jpeg,png,jpg,gif,svg',
        }

        if ($this->is_location == 1) {
            $rules['location'] = ['required'];
            // $rules['to'] = ['required'];
        }

        if ($this->is_from_to_location == 1) {
            $rules['from'] = ['required'];
            $rules['to'] = ['required'];
        }

        if ($this->is_km == 1) {
            $rules['km'] = ['required'];
            $rules['per_km_price'] = ['required'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'tadatype_id.required' => 'The TadaType ID is required.',
            'start.required' => 'The date is required.',
            'date.date' => 'The date must be a valid date.',
            /* 'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.', */
            'amount.required' => 'The amount is required.',
            'photo.required' => 'Image is required.',
            'photo.image' => 'The must be an image.',
            'photo.mimes' => 'The image must be one of the following types: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'The image must not be larger than 2MB.',
            'from.required' => 'The from location is required.',
            'to.required' => 'The to location is required.',
            'km.required' => 'The kilometer is required.',
            'per_km_price.required' => 'The Price per kilometer is required.',
            'expense_name.required' => 'The expense name is required.',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     *
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $response = [
            'success' => false,
            'message' => 'Validation Error.',
            'data' => $validator->errors(),
        ];

        throw new HttpResponseException(
            response()->json($response, 422)
        );
    }
}
