<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ShopRequest;
use App\Models\Distributor;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('shop.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request, ?Distributor $distributor)
    {
        try {
            $list = $this->service->apiList($request->all(), $distributor);

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function store(ShopRequest $request)
    {
        try {
            $shop = $this->service->apiCreate($request->all());

            return $this->sendResponse($shop, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
