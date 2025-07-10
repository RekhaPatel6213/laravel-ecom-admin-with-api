<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('address.service');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $userType, $userId)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request, $userType, $userId)]);
            }

            return view('address.index', compact('userType', 'userId'));
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($userType, $userId)
    {
        try {
            $countries = getCountries();

            return view('address.create', compact('countries', 'userType', 'userId'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressRequest $request, $userType, $userId)
    {
        try {
            $this->service->create($request->all(), $userType, $userId);

            return redirect()->route('address.index', [$userType, $userId])->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return view('address.admin_show', ['result' => $address]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($userType, $userId, Address $address)
    {
        try {
            $countries = getCountries();

            return view('address.create', compact('address', 'countries', 'userType', 'userId'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AddressRequest $request, $userType, $userId, Address $address)
    {
        try {
            $this->service->update($request->all(), $address);

            return redirect()->route('address.index', [$userType, $userId])->with('success_message', __('message.submitSuccess'));
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
}
