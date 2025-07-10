<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CouponRequest;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('coupon.service');
    }

    /**
     * Handle the incoming request.
     */
    public function check_coupon(CouponRequest $request)
    {
        try {
            $responce = $this->service->checkValidCoupon($request->all());

            return $this->handleResponce($responce);
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove Coupon from cart
     */
    public function remove_coupon(Request $request)
    {
        try {
            $responce = $this->service->removeCoupon($request->all());

            return $this->handleResponce($responce);
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
