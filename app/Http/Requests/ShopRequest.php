<?php

namespace App\Http\Requests;

use App\Models\Shop;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ShopRequest extends FormRequest
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
        $shopId = $this->shop_id ?? null;

        return [
            'name' => ['required', 'string', 'max:50'],
            'distributor_id' => ['required'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(Shop::class)->ignore($shopId)],
            'mobile' => ['required', 'numeric', 'digits:10', 'regex:/^([0-9\s\-\+\(\)]*)$/', Rule::unique(Shop::class)->ignore($shopId)],
            // 'country_id' => ['nullable'],
            'state_id' => ['required'],
            'city_id' => ['required'],
            'area_id' => ['required'],
            'shop_area' => ['required'],
            // 'address' => ['required'],
            'zone_id' => ['required', 'exists:zones,id'],
        ];
    }
}
