<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\InventoryTrait;

class AllReportController extends Controller
{
    use InventoryTrait;

    protected $service;

    public function __construct()
    {
        $this->service = app('order.service');
    }

    public function index()
    {
        return view('allreport.index');
    }

    public function getDistributorsForReport(Request $request)
    {
        $reportType = $request->report_type;

        $query = User::query();

        if ($reportType == 'R') {
            $query->where('role_id', 2);
        } elseif ($reportType == 'P') {
            $query->where('role_id', 3);
        } else {
            $query->whereIn('role_id', [2, 3]);
        }

        return response()->json(
            $query->select('id', 'firstname', 'lastname')
                ->orderBy('firstname')
                ->get()
        );
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:P,R,O',
            'sales_person' => 'required|exists:users,id',
            'start_date' => 'required|date_format:d-m-Y',
            'end_date' => 'required|date_format:d-m-Y|after_or_equal:start_date',
        ]);

        try {
            $requestData = [
                'report_parameter' => $request->report_type,
                'sales_person' => $request->sales_person,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            // Call the method through the service
            $result = $this->service->AllapiReport($requestData);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error generating report: ' . $e->getMessage()
            ], 500);
        }
    }
}
