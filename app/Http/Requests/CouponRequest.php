<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $couponId = $this->coupon_id ?? null;

        return [
            'name' => 'required|unique:coupons,name,'.$couponId.',id,deleted_at,NULL',
            'code' => 'required|unique:coupons,code,'.$couponId.',id,deleted_at,NULL',
            'discount' => 'required',
            'min_order_value' => 'required',
            'max_discount_allow' => 'required',
            'start_date' => 'required',
            'total_coupon' => 'required|integer|min:1|gte:coupon_use_time',
            'coupon_use_time' => 'required|integer|min:1|lte:total_coupon',
        ];
    }
}
