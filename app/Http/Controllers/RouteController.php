<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use App\Models\Route;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

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
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                return response()->json(['data' => $this->service->list($request)]);
            }

            return view('route.index');
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    public function show(Route $route)
    {
        try {
            return view('route.show', ['route' => $route]);
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function attendance_report(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = $this->service->attendance_report($request);

                return view('route.ajax_attendance_report', compact('data'));
            }

            $sales_person_list = User::where('role_id', config('constants.COMPANY_ROLE_ID'))->select(DB::raw('CONCAT(firstname, " ", lastname) as full_name'), 'id')->pluck('full_name', 'id');

            return view('route.attendance_report', compact('sales_person_list'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function attendance_export(Request $request)
    {
        if ($request->ajax()) {
            try {
                $date = '01-'.$request->month.'-'.$request->year;
                $fileName = 'attendance_report-'.Carbon::parse($date)->monthName.'-'.$request->year.'.xlsx';

                $filePath = 'public/exports/'.$fileName;

                Excel::store(new AttendanceExport($request->user_id, $request->year, $request->month), $filePath);

                $fileUrl = Storage::url('exports/'.$fileName);

                return response()->json([
                    'success' => true,
                    'message' => 'Export file generated successfully.',
                    'file_url' => asset($fileUrl),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error generating export file: '.$e->getMessage(),
                ], 500);
            }
        }
    }

    public function employee_tracking(Request $request)
    {
        try {
            if ($request->ajax()) {
                $data = $this->service->employee_tracking($request);

                return response()->json($data);
            }

            $sales_person_list = User::where('role_id', config('constants.COMPANY_ROLE_ID'))->select(DB::raw('CONCAT(firstname, " ", lastname) as full_name'), 'id')->pluck('full_name', 'id');

            return view('route.employee_tracking', compact('sales_person_list'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function map(Request $request)
    {
        try {
            $map = $this->service->map($request);

            return view('route.map', compact('map'));
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }
}
