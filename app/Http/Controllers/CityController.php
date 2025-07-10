<?php

namespace App\Http\Controllers;

use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('city.service');
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
                return view('city.index');
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
            $stateList = getStates('id');

            return view('city.create', compact('sortId', 'stateList'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CityRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('city.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(City $city)
    {
        try {
            $stateList = getStates('id');

            return view('city.create', compact('city', 'stateList'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CityRequest $request, City $city)
    {
        try {
            $this->service->update($request->all(), $city);

            return redirect()->route('city.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city)
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

    public function city_list(Request $request)
    {
        if ($request->ajax()) {
            try {
                $cities = City::where('state_id', $request->state_id)->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->pluck('name', 'id');
                $options = '<option value="">Please Select</option>';

                foreach ($cities as $cityId => $cityName) {

                    $selected = (int) $request->city_id === (int) $cityId ? 'selected' : '';

                    $options .= '<option value="'.$cityId.'" '.$selected.'>'.$cityName.'</option>';
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
