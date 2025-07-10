<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TadaController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('tada.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request)]);
            }

            return view('tada.index');
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkUpdate('is_confirm', $request->all()));
            } catch (Exception $e) {
                return response()->json([
                    'result' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }
}
