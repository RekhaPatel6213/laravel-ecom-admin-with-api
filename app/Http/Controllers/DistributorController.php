<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistributorRequest;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request)]);
            }

            return view('distributor.index');
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
            $countries = getCountries();
            $meetingTypes = getMeetingTypes();

            return view('distributor.create', compact('countries', 'meetingTypes'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DistributorRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('distributor.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Distributor $distributor)
    {
        return view('distributor.admin_show', ['result' => $distributor]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distributor $distributor)
    {
        try {
            $countries = getCountries();
            $meetingTypes = getMeetingTypes();

            return view('distributor.create', compact('distributor', 'countries', 'meetingTypes'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DistributorRequest $request, Distributor $distributor)
    {
        try {
            $this->service->update($request->all(), $distributor);

            return redirect()->route('distributor.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function delete_image(Request $request)
    {
        if ($request->ajax()) {
            try {
                return response()->json($this->service->bulkUpdate($request->column_name, ['status_id' => $request->id, 'status' => null]));
            } catch (Exception $e) {
                return response()->json(['result' => false, 'message' => $e->getMessage(),
                ]);
            }
        } else {
            return back()->with('error', __('message.oopsError'));
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

    public function distributor_list(Request $request)
    {
        if ($request->ajax()) {
            try {
                $distributors = Distributor::select('firstname as fullname', 'id')->where('zone_id', $request->zone_id)->where('status', config('constants.ACTIVE'))->orderBy('fullname', 'ASC')->pluck('fullname', 'id');

                $options = ''; // '<option value="">Please Select</option>';

                if ($request->type === 'single') {
                    $options .= '<option value="">Please Select</option>';
                }

                foreach ($distributors as $distributorId => $distributorName) {

                    // $selected = (int) $request->distributor_id === (int) $distributorId ? 'selected' : '';
                    $selected = in_array($distributorId, explode(',', $request->distributor_id)) ? 'selected' : '';

                    $options .= '<option value="'.$distributorId.'" '.$selected.'>'.$distributorName.'</option>';
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
