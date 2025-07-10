<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\ExpenseReportRequest;
use App\Http\Requests\API\TadaRequest;
use Illuminate\Http\Request;

class TadaController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('tada.service');
    }

    public function report(ExpenseReportRequest $request)
    {
        try {
            $report = $this->service->apiReport($request);

            return $this->sendResponse($report, 'Report successfully created!');
        } catch (Exception $exception) {
            return $this->sendError('Route not end please try again!', ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        try {
            $list = $this->service->apiList($request);

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function store(TadaRequest $request)
    {
        try {
            $tada = $this->service->apiCreate($request->all());

            return $this->sendResponse($tada, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function tada_type_list(Request $request)
    {
        try {
            $tadaType = $this->service->apiTadaType($request->all());

            return $this->sendResponse($tadaType, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
