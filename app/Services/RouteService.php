<?php

namespace App\Services;

use App\Models\Route;
use App\Models\User;
use App\Models\Meeting;
use App\Repositories\MeetingRepository;
use App\Repositories\OrderRepository;
use App\Repositories\RouteRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class RouteService
{
    protected $repository;

    protected $orderRepository;

    protected $meetingRepository;

    public function __construct(RouteRepository $routeRepository, OrderRepository $orderRepository, MeetingRepository $meetingRepository)
    {
        $this->repository = $routeRepository;
        $this->orderRepository = $orderRepository;
        $this->meetingRepository = $meetingRepository;
    }

    public function create(array $requestData)
    {
        $user = Auth::user();

        $route = Route::where('user_id', Auth::user()->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->first();
        if (empty($route)) {
            $requestData['user_id'] = $user->id;
            $route = $this->repository->updateOrCreate($requestData, null);
        }

        return [['route_id' => $route->id]];
    }

    public function update(array $requestData)
    {
        $status = false;
        $message = '';
        $meeting = Meeting::where('user_id', Auth::user()->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->first();

        if($meeting)
        {
            return ['status' => $status, 'data' => [['meeting_id' => $meeting->id]], 'message' => 'Meeting allready started. Please end your meeting first'];
        }

        $route = Route::where('user_id', Auth::user()->id)->whereNull(['end_time', 'end_latitude', 'end_longitude'])->first();
        $report = '';
        if ($route) {

            if ($requestData['comment'] === null) {
                $endDate = date('Y-m-d', strtotime($requestData['end_time']));
                //$report = app('order.service')->apiReport(['start_date' => $endDate, 'end_date' => $endDate, 'report_parameter' => 'O']);
                $report = $this->apiReport(['start_date' => $endDate, 'end_date' => $endDate]);
                $report = $report[0]['pdf_path'];
            }

            if (($report !== '' && $requestData['comment'] === null) || ($report === '' && $requestData['comment'] !== null)) {
                $route = $this->repository->updateOrCreate($requestData, $route);
                $status = true;
                $message = 'Route End successfully!';
            }

            if ($report === '' && $requestData['comment'] === null) {
                $status = true;
                $message = 'No order found.';

            }
        }

        return ['status' => $status, 'data' => [['route_id' => $route->id ?? null, 'report_path' => $report]], 'message' => $message];
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'start_time') ?? 'start_time';
        $sortOrder = $requestData->query('sort', 'desc') ?? 'desc';

        $routes = $this->repository->getQueryBuilder(null, $sortOn, $sortOrder)
            ->select('id', 'user_id', 'start_time', 'start_latitude', 'start_longitude', 'end_time', 'end_latitude', 'end_longitude')
            ->when($search !== null, function (Builder $query) use ($search) {
                $query->where(function ($subQuery1) use ($search) {
                    $subQuery1->where('start_time', 'like', '%' . $search . '%');
                    $subQuery1->orWhereHas('user', function ($subQuery2) use ($search) {
                        $subQuery2->where('firstname', 'like', "%$search%");
                        $subQuery2->orWhere('lastname', 'like', "%$search%");
                    });
                });
            })
            ->with(['user:id,firstname,lastname,mobile'])
            ->get();

        $routeArray = [];

        if ($routes) {
            foreach ($routes as $key => $route) {

                $routeDate = date(config('constants.DATE_FORMATE'), strtotime($route->start_time));

                $data['srno'] = $key + 1;
                $data['sale_person'] = ($route->user->firstname ?? '') . ' ' . ($route->user->lastname ?? '');
                $data['mobile'] = $route->user->mobile ?? '';
                $data['start_time'] = $routeDate . ' ' . Carbon::parse($route->start_time)->format(config('constants.TIME_FORMATE'));
                $data['end_time'] = $route->end_time !== null ? $routeDate . ' ' . Carbon::parse($route->end_time)->format(config('constants.TIME_FORMATE')) : null;
                $data['action'] = "<a href='" . route('route.show', $route->id) . "' title='Show'><i class='fa fa-eye'></i></a>";
                $data['start_map_link'] = getGoogleMapLink($route->start_latitude, $route->start_longitude);
                $data['end_map_link'] = getGoogleMapLink($route->end_latitude, $route->end_longitude);
                // $data['route'] = $route;
                $routeArray[] = $data;
            }
        }

        return $routeArray;
    }

    public function attendance_report($requestData)
    {
        $startDate = Carbon::createFromFormat('Y-m', $requestData->year . '-' . $requestData->month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $requestData->year . '-' . $requestData->month)->endOfMonth();

        $attendance = User::select('id', 'firstname', 'lastname')->with([
            'route' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        ])
            ->where('role_id', '!=', config('constants.ADMIN_ROLE_ID'));

        if ($requestData->user_id) {
            $attendance->where('id', $requestData->user_id);
        }

        $attendance = $attendance->get();

        return ['attendance' => $attendance, 'year' => $requestData->year, 'month' => $requestData->month, 'monthYear' => $requestData->year . '-' . $requestData->month];
    }

    public function employee_tracking($requestData)
    {
        $trackingdata = Route::where('user_id', $requestData->user_id)->whereDate('created_at', $requestData->date)->get();

        if (!$trackingdata->isEmpty()) {
            return [
                'status' => true,
                'url' => route('route.map', [
                    'user_id' => $requestData->user_id,
                    'date' => $requestData->date,
                ]),
            ];
        }

        return [
            'status' => false,
            'user' => null,
        ];
    }

    public function map($requestData)
    {
        $map = Route::where('user_id', $requestData->user_id)->whereDate('created_at', $requestData->date)->get();

        $map_arr = [];
        if (!$map->isEmpty()) {

            foreach ($map as $key => $value) {
                $map_arr[] = [Carbon::parse($value->start_time)->format('h:i A'), $value->start_latitude, $value->start_longitude];
            }
        }

        return $map_arr;
    }

    public function apiReport($requestData)
    {
        $startDate = $requestData['start_date'];
        $endDate = $requestData['end_date'];
        $user = Auth::user();
        $userId = $user->id;

        // Get Orders Data
        [$primaryOrders, $retailingOrders, $grandTotal, $retailingOrderCount] = app('order.service')->getReportOrders($startDate, $endDate, $userId);

        $count = [$retailingOrders];

        $noOrders = app('order.service')->apiNoOrderList($userId, $startDate, $endDate);
        $noOrderCount = noOrderCount($userId, $startDate, $endDate);
        $count['total_orders'] = $noOrderCount + $retailingOrderCount;
        $count['productive_orders'] = $retailingOrderCount;
        $count['unproductive_orders'] = $noOrderCount;

        // Get Meeting Data
        $meetings = app('meeting.service')->getReportMeetings($startDate, $endDate, $userId);

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $durations = $startDate->diffInDays($endDate) + 1;
        $pdfName = 'Daily Sales Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') .') ('  . $startDate->format('d-m-Y').' To '.$endDate->format('d-m-Y') . ').pdf';

        //return view('pdf.order_report', ['start_date' => $startDate, 'end_date' => $endDate, 'user_data' => $user, 'grandTotal' => $grandTotal, 'primaryOrders' => $primaryOrders, 'retailingOrders' => $retailingOrders, 'meetingList' => $meetings]);
        $pdf = PDF::loadView('pdf.order_report', ['start_date' => $startDate->format('d-m-Y'), 'end_date' => $endDate->format('d-m-Y'), 'durations' => $durations, 'user_data' => $user, 'count' => $count, 'grandTotal' => $grandTotal, 'primaryOrders' => $primaryOrders, 'retailingOrders' => $retailingOrders, 'meetingList' => $meetings, 'noOrders' =>$noOrders, 'pdfName' => $pdfName]);
        //$pdf->setPaper('a4', 'landscape');
        $pdfContent = $pdf->download()->getOriginalContent();

        
        // return $pdf->stream($pdfName);

        $client = Storage::createLocalDriver(['root' => storage_path('app/public') . '/daily_report']);
        $client->put($pdfName, $pdfContent);

        return [['pdf_path' => asset('report/daily_report/' . $pdfName)]];
    }
}
