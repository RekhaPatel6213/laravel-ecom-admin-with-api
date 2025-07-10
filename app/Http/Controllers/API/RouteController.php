<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\EndRouteRequest;
use App\Http\Requests\API\StartRouteRequest;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('route.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function start(StartRouteRequest $request)
    {
        try {
            $startRoute = $this->service->create($request->all());

            return $this->sendResponse($startRoute, 'Route Start successfully!');
        } catch (Exception $exception) {
            return $this->sendError('Route not started please try again!', ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function end(EndRouteRequest $request)
    {
        try {
            $endRoute = $this->service->update($request->all());
            \Log::info($endRoute);

            return $this->handleResponce($endRoute, 'Route End successfully!');
        } catch (Exception $exception) {
            return $this->sendError('Route not end please try again!', ['error' => $exception->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        try {
            $report = $this->service->apiReport($request);

            return $this->sendResponse($report, 'Report successfully created!');
        } catch (Exception $exception) {
            return $this->sendError('Route not end please try again!', ['error' => $exception->getMessage()]);
        }
    }
}
