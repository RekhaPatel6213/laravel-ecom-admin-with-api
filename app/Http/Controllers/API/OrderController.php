<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $service;

    protected $cartService;

    public function __construct()
    {
        $this->service = app('order.service');
        $this->cartService = app('cart.service');
    }

    /**
     * Handle the incoming request.
     */
    public function order_place(Request $request)
    {
        try {
            $order = $this->cartService->storeOrder();

            return $this->handleResponce($order);
            /*if($order['status'] == true) {
                return $this->sendResponse($order['data'], $order['message'], 200);
            } else {
                return $this->sendError($order['message'], 200);
            }*/
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Handle the incoming request.
     */
    public function list(Request $request)
    {
        // return $this->sendResponse($request->all(), '');
        try {
            $list = $this->service->apiList($request);

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        try {
            $generate = $this->service->apiReport($request->all());

            return $this->sendResponse($generate, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function no_order(Request $request)
    {
        try {
            $generate = $this->service->no_order($request->all());

            return $this->sendResponse($generate, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
