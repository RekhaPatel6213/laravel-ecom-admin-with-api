<?php

namespace App\Http\Controllers;

use App\Http\Requests\VariantValueRequest;
use App\Models\VariantValue;
use Illuminate\Http\Request;

class VariantValueController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('variant.value.service');
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
                return view('variantvalue.index');
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
            return view('variantvalue.create');
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VariantValueRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('variantvalue.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(VariantValue $variantvalue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VariantValue $variantvalue)
    {
        try {
            return view('variantvalue.create', compact('variantvalue'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VariantValueRequest $request, VariantValue $variantvalue)
    {
        try {
            $this->service->update($request->all(), $variantvalue);

            return redirect()->route('variantvalue.index')->with('success_message', __('message.submitSuccess'));
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
