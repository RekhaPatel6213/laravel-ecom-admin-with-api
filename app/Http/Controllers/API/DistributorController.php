<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\DistributorRequest;
use App\Http\Requests\API\DistributorUpdateRequest;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('distributor.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        try {
            $list = $this->service->apiList($request->all());

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function store(DistributorRequest $request)
    {
        try {
            $distributor = $this->service->apiCreate($request->all());

            return $this->sendResponse($distributor, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function update(DistributorUpdateRequest $request)
    {
        try {
            $distributor = $this->service->apiUpdate($request->all());

            return $this->sendResponse($distributor, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
