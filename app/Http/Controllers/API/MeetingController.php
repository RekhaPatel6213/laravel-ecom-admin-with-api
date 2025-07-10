<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\MeetingEndRequest;
use App\Http\Requests\API\MeetingStartRequest;
use App\Models\Distributor;
use App\Models\Meeting;
use App\Models\Shop;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('meeting.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function list(Request $request, ?Distributor $distributor = null, ?Shop $shop = null)
    {
        try {
            $list = $this->service->apiList($request);

            return $this->sendResponse($list, __('message.getSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function start(MeetingStartRequest $request)
    {
        \Log::info('Meeting Start Start');
        \Log::info($request->all());
        \Log::info('Meeting Start End');
        try {
            $meeting = $this->service->create($request->all());

            return $this->sendResponse($meeting, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function end(MeetingEndRequest $request)
    {
        \Log::info('Meeting End Start');
        \Log::info($request->all());
        \Log::info('Meeting End End');
        try {
            $meeting = $this->service->update($request->all());

            return $this->sendResponse($meeting, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function show(Meeting $meeting)
    {
        try {
            $distributor = $this->service->detail($meeting);

            return $this->sendResponse($distributor, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        try {
            $reportdata = $this->service->apiReport($request);

            return $this->sendResponse($reportdata, __('message.submitSuccess'));
        } catch (Exception $exception) {
            return $this->sendError(__('message.oopsError'), ['error' => $exception->getMessage()]);
        }
    }
}
