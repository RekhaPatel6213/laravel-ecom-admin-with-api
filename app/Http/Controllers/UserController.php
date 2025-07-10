<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('user.service');
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

            return view('user.index');
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
            $zones = getZones();
            $designations = getDesignations();

            return view('user.create', compact('zones', 'designations'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        try {
            $this->service->create($request->all());

            return redirect()->route('user.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('user.admin_show', ['result' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            $zones = getZones();
            $designations = getDesignations();

            return view('user.create', compact('user', 'zones', 'designations'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        try {
            $this->service->update($request->all(), $user);

            return redirect()->route('user.index')->with('success_message', __('message.submitSuccess'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function multiple_delete(Request $request)
    {
        $status = false;
        $message = 'Bad Request';

        if ($request->ajax()) {
            $message = 'Opps Something went wrong';
            $dataIds = json_decode($request->data_id);
            $orderData = User::select('id')->whereIn('id', $dataIds)->whereDoesntHave('orderPending')->get();

            if (count($dataIds) != count(data_get($orderData->toArray(), '*.id'))) {
                User::whereIn('id', $dataIds)->whereDoesntHave('orderPending')->delete();
                $message = __('message.cannotDelete', ['name' => 'User', 'relation' => strtolower('order is pending')]);
            } else {
                if (User::whereIn('id', $dataIds)->delete()) {
                    $status = true;
                    $message = __('message.deleteSuccess');
                }
            }
        }

        return response()->json(['status' => $status, 'message' => $message]);
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
