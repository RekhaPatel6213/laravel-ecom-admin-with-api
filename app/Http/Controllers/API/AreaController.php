<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\AreaRequest;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('area.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        try {
            $areas = $this->service->apiList($request->all());

            return $this->sendResponse($areas, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function store(AreaRequest $request)
    {
        try {
            $area = $this->service->apiCreate($request->all());

            return $this->sendResponse($area, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
