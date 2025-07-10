<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = app('meeting.service');
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

            return view('meeting.index');
        } catch (Exception $exception) {
            return $this->abortJsonResponse($exception);
        }
    }

    public function show(Meeting $meeting)
    {
        try {
            return view('meeting.show', ['meeting' => $meeting]);
        } catch (Exception $exception) {
            return back()->with('error_message', $exception->getMessage());
        }
    }

    public function generateLocalPdfReport(Request $request)
    {


        try {
            $user = Auth::user();

            $meetingList = $this->getReportMeetings(
                $request->start_date,
                $request->end_date,
                $user->id
            );

            // Parse dates
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $duration = $startDate->diffInDays($endDate) + 1;

            // Generate PDF filename
            $pdfName = 'Meeting_Report_' . 
                       ($user->firstname ?? '') . '_' . 
                       ($user->lastname ?? '') . '_' . 
                       $startDate->format('Y-m-d') . '_to_' . 
                       $endDate->format('Y-m-d') . '.pdf';

            // Generate PDF
            $pdf = PDF::loadView('pdf.meeting_report_pdf', [
                'start_date' => $startDate->format('d-m-Y'),
                'end_date' => $endDate->format('d-m-Y'),
                'durations' => $duration,
                'user_data' => $user,
                'meetingList' => $meetingList,
                'pdfName' => $pdfName
            ]);

            // Storage path
            $storagePath = 'meeting_reports/' . date('Y') . '/' . date('m');
            $fullPath = storage_path('app/public/' . $storagePath);

            // Ensure directory exists
            if (!file_exists($fullPath)) {
                Storage::makeDirectory('public/' . $storagePath);
            }

            // Save PDF
            $pdf->save($fullPath . '/' . $pdfName);

            return [
                'success' => true,
                'message' => 'PDF report generated successfully',
                'data' => [
                    'file_path' => $storagePath . '/' . $pdfName,
                    'file_name' => $pdfName,
                    'full_path' => $fullPath . '/' . $pdfName,
                    'download_url' => asset('storage/' . $storagePath . '/' . $pdfName)
                ]
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to generate PDF: ' . $e->getMessage()
            ];
        }
    }

    private function getReportMeetings($startDate, $endDate, $userId)
    {
        return Meeting::where('user_id', $userId)
            ->whereBetween('meeting_date', [$startDate, $endDate])
            ->orderBy('meeting_date')
            ->get();
    }
}
