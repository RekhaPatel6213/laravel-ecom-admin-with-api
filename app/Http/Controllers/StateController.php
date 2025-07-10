<?php

namespace App\Http\Controllers;

use App\Http\Requests\StateRequest;
use App\Models\Country;
use App\Models\State;
use App\Models\Zone;
use Illuminate\Http\Request;

class StateController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('state.service');
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
                return view('state.index');
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
            $sortId = $this->service->getLastSortId();
            $countryList = Country::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
            $zoneList = Zone::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');

            return view('state.create', compact('sortId', 'countryList', 'zoneList'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StateRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('state.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(State $state)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(State $state)
    {
        try {
            $countryList = Country::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');
            $zoneList = Zone::where('status', 1)->orderBy('name', 'asc')->pluck('name', 'id');

            return view('state.create', compact('state', 'countryList', 'zoneList'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StateRequest $request, State $state)
    {
        try {
            $this->service->update($request->all(), $state);

            return redirect()->route('state.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(State $state)
    {
        //
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

    public function state_list(Request $request)
    {
        if ($request->ajax()) {
            try {
                $states = State::where('country_id', $request->country_id)->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->pluck('name', 'id');
                $options = '<option value="">Please Select</option>';

                foreach ($states as $stateId => $stateName) {

                    $selected = (int) $request->state_id === (int) $stateId ? 'selected="selected"' : '';

                    $options .= '<option value="'.$stateId.'" '.$selected.'>'.$stateName.'</option>';
                }

                return response()->json(['result' => false, 'message' => __('Data Get Successfully'), 'data' => $options]);
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(), 'data' => null]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
        }
    }
}
