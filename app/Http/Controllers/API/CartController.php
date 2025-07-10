<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\CartIdRequest;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('cart.service');
    }

    /**
     * Handle the incoming request.
     */
    public function cart(Request $request)
    {
        try {
            $responce = $this->service->storeCart($request->all());

            return $this->handleResponce($responce);
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Remove Product from cart
     */
    public function remove_cart(CartIdRequest $request)
    {
        try {
            $responce = $this->service->removeCart($request->all());

            return $this->handleResponce($responce);
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
