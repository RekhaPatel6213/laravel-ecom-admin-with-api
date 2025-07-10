<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeetingTypeRequest;
use App\Models\MeetingType;
use Illuminate\Http\Request;

class MeetingTypeController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('meeting.type.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request)]);
            } else {
                return view('meetingtype.index');
            }
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('meetingtype.create');
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MeetingTypeRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('meetingtype.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MeetingType $meetingtype)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MeetingType $meetingtype)
    {
        try {
            return view('meetingtype.create', compact('meetingtype'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MeetingTypeRequest $request, MeetingType $meetingtype)
    {
        try {
            $this->service->update($request->all(), $meetingtype);

            return redirect()->route('meetingtype.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function multiple_delete(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkDelete($request->all()));
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkUpdate('status', $request->all()));
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }
}
