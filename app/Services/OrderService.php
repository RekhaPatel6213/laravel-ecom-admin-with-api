<?php

namespace App\Services;

use App\Http\Resources\OrderResource;
use App\Models\NoOrder;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\User;
use App\Repositories\OrderRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class OrderService
{
    protected $repository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->repository = $orderRepository;
    }

    public function getReportOrders(string $startDate, string $endDate, int $userId, string $reportType = 'O')
    {
        $orders = $this->repository->getQueryBuilder(null, 'order_no', 'asc')
            ->when($userId, function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->when($reportType == 'P', function ($query) {
                $query->whereNull('shop_id');
            })
            ->when($reportType == 'R', function ($query) {
                $query->whereNotNull('shop_id');
            })
            ->with([
                'orderproduct.product:id,name',
                'distributor' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'mobile', 'country_id', 'state_id', 'city_id', 'zone_id');
                    $query->with(['country:id,name', 'state:id,name', 'city:id,name', 'zone:id,name']);
                },
                'shop' => function ($query) {
                    $query->select('id', 'name', 'contact_person_name', 'mobile', 'area_id', 'country_id', 'state_id', 'city_id', 'zone_id');
                    $query->with(['country:id,name', 'state:id,name', 'city:id,name', 'area:id,name', 'zone:id,name']);
                },
            ])
            ->get();

        $primaryOrders = $orders->whereNull('shop_id');
        $primaryOrders = $primaryOrders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('d-m-Y');
        })->sortKeys();
        $orders = $orders->whereNotNull('shop_id');
        $retailingOrderCount = count($orders);
        $retailingOrders = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('d-m-Y');
        })->sortKeys();

        $grandTotal = array_sum(data_get($retailingOrders, '*.*.grand_total'));

        return [$primaryOrders, $retailingOrders, $grandTotal, $retailingOrderCount];
    }

    public function apiReport(array $requestData)
    {
        $startDate = $requestData['start_date'] ?? date('d-m-Y');
        $endDate = $requestData['end_date'] ?? date('d-m-Y');
        $user = Auth::user();
        //$user = User::where('id', 2)->first();
        $userId = $user->id;
        //$requestData['report_parameter'] = 'O';

        [$primaryOrders, $retailingOrders, $grandTotal, $retailingOrderCount] = $this->getReportOrders($startDate, $endDate, $userId, $requestData['report_parameter'] ?? 'O');

        $count = [$retailingOrders];

        $noOrders = $this->apiNoOrderList($userId, $startDate, $endDate);
        $noOrderCount = noOrderCount($userId, $startDate, $endDate);
        $count['total_orders'] = $noOrderCount + $retailingOrderCount;
        $count['productive_orders'] = $retailingOrderCount;
        $count['unproductive_orders'] = $noOrderCount;
        $meetings = null;

        // report_parameter - P for Primary Order Report, R for Retailing Report, O for Both
        if (isset($requestData['report_parameter']) && $requestData['report_parameter'] == 'P') {
            $pdfFile = 'primary_order_report_pdf';
            $pdfPath = 'primary_order_report';
            $count = [];
            $pdfName = 'Primary Order Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') ('  . $startDate . ' To ' . $endDate . ').pdf'; // rand(111,999).
        } elseif (isset($requestData['report_parameter']) && $requestData['report_parameter'] == 'R') {
            $pdfFile = 'retailing_report_pdf';
            $pdfPath = 'retailing_report';
            $pdfName = 'Retailing Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') ('  . $startDate . ' To ' . $endDate . ').pdf'; // rand(111,999).
        } else {
            $pdfFile = 'order_report';
            $pdfPath = 'common_report';
            $pdfName = 'Daily Sales Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') ('  . $startDate . ' To ' . $endDate . ').pdf';
            // Get Meeting Data
            $meetings = app('meeting.service')->getReportMeetings($startDate, $endDate, $userId);
        }

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $durations = $startDate->diffInDays($endDate) + 1;

        //return view('pdf.'.$pdfFile, ['start_date' => $startDate->format('d-m-Y'), 'end_date' => $endDate->format('d-m-Y'), 'durations' => $durations, 'count' => $count, 'grandTotal' => $grandTotal, 'user_data' => $user, 'retailingOrders' => $retailingOrders, 'primaryOrders' => $primaryOrders, 'meetingList' => null]);
        $pdf = PDF::loadView('pdf.' . $pdfFile, ['start_date' => $startDate->format('d-m-Y'), 'end_date' => $endDate->format('d-m-Y'), 'durations' => $durations, 'count' => $count, 'grandTotal' => $grandTotal, 'user_data' => $user, 'retailingOrders' => $retailingOrders, 'primaryOrders' => $primaryOrders, 'meetingList' => $meetings, 'noOrders' => $noOrders, 'pdfName' => $pdfName]);

        //return $pdf->stream('Nuttoz_Order.pdf');
        $pdfContent = $pdf->download()->getOriginalContent();
        $pdfName = $pdfName;
        $client = Storage::createLocalDriver(['root' => storage_path('app/public') . '/' . $pdfPath]);
        $client->put($pdfName, $pdfContent);

        return [['pdf_path' => asset('report/' . $pdfPath . '/' . $pdfName)]];
    }

    public function AllapiReport(array $requestData)
    {
        $startDate = $requestData['start_date'] ?? date('d-m-Y');
        $endDate = $requestData['end_date'] ?? date('d-m-Y');
        $reportParameter = $requestData['report_parameter'] ?? 'O';
        $salesPersonId = $requestData['sales_person'] ?? null;

        $user = $salesPersonId ? User::find($salesPersonId) : Auth::user();

        if (!$user) {
            throw new \Exception("User not found");
        }

        $userId = $user->id;

        [$primaryOrders, $retailingOrders, $grandTotal, $retailingOrderCount] = $this->getReportOrders(
            $startDate,
            $endDate,
            $userId,
            $reportParameter
        );

        $count = [$retailingOrders];

        $noOrders = $this->apiNoOrderList($userId, $startDate, $endDate);
        $noOrderCount = noOrderCount($userId, $startDate, $endDate);
        $count['total_orders'] = $noOrderCount + $retailingOrderCount;
        $count['productive_orders'] = $retailingOrderCount;
        $count['unproductive_orders'] = $noOrderCount;
        $meetings = null;

        if ($reportParameter == 'P') {
            $pdfFile = 'primary_order_report_pdf';
            $pdfPath = 'primary_order_report';
            $count = [];
            $pdfName = 'Primary Order Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') ('  . $startDate . ' To ' . $endDate . ').pdf';
        } elseif ($reportParameter == 'R') {
            $pdfFile = 'retailing_report_pdf';
            $pdfPath = 'retailing_report';
            $pdfName = 'Retailing Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') ('  . $startDate . ' To ' . $endDate . ').pdf';
        } else {
            $pdfFile = 'order_report';
            $pdfPath = 'common_report';
            $pdfName = 'Daily Sales Report (' . ($user->firstname ?? '') . ' ' . ($user->lastname ?? '') . ') ('  . $startDate . ' To ' . $endDate . ').pdf';
            $meetings = app('meeting.service')->getReportMeetings($startDate, $endDate, $userId);
        }

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);
        $durations = $startDate->diffInDays($endDate) + 1;

        $pdf = PDF::loadView('pdf.' . $pdfFile, [
            'start_date' => $startDate->format('d-m-Y'),
            'end_date' => $endDate->format('d-m-Y'),
            'durations' => $durations,
            'count' => $count,
            'grandTotal' => $grandTotal,
            'user_data' => $user,
            'retailingOrders' => $retailingOrders,
            'primaryOrders' => $primaryOrders,
            'meetingList' => $meetings,
            'noOrders' => $noOrders,
            'pdfName' => $pdfName
        ]);

        $pdfContent = $pdf->download()->getOriginalContent();
        $client = Storage::createLocalDriver(['root' => storage_path('app/public') . '/' . $pdfPath]);
        $client->put($pdfName, $pdfContent);

        return [['pdf_path' => asset('storage/' . $pdfPath . '/' . $pdfName)]];
    }

    public function apiList(Request $requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $distributorId = $requestData->query('distributor_id', null) ?? null;
        $shopId = $requestData->query('shop_id', null) ?? null;
        $startDate = $requestData->query('start_date', null) ?? null;
        $endDate = $requestData->query('end_date', null) ?? null;
        $userId = $requestData->query('user_id', null) ?? null;

        $orders = $this->repository->getQueryBuilder(null, 'id', 'desc')
            ->select('id', 'order_no', 'firstname', 'lastname', 'grand_total', 'orderstatus_id', 'created_at', 'total_quantity', 'distributor_id', 'shop_id', 'meeting_id', 'order_pdf')
            ->with([
                'orderstatus:id,order_status_name',
                'distributor:id,firstname,lastname',
                'shop:id,name',
                'meeting:id,start_latitude,start_longitude,end_latitude,end_longitude',
            ])
            ->when((isset($requestData->report_parameter) && $requestData->report_parameter === 'P'), function ($query) {
                $query->whereNull('shop_id');
            })
            ->when((isset($requestData->report_parameter) && $requestData->report_parameter === 'R'), function ($query) {
                $query->whereNotNull('shop_id');
            })
            ->when((isset($requestData->today) && $requestData->today === '1'), function ($query) {
                $query->whereDate('created_at', date('Y-m-d'));
            })
            ->when($distributorId !== null, function ($query) use ($distributorId) {
                $query->where('distributor_id', $distributorId);
            })
            ->when($userId !== null, function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            }, function (Builder $query) {
                $query->where('user_id', Auth::user()->id);
            })
            // ->where('user_id', Auth::user()->id)
            ->when($shopId !== null, function ($query) use ($shopId) {
                $query->where('shop_id', $shopId);
            })
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->when(($startDate !== null && $endDate === null), function ($query) use ($startDate) {
                $query->whereDate('created_at', date('Y-m-d', strtotime($startDate)));
            })
            ->when(($startDate === null && $endDate !== null), function ($query) use ($endDate) {
                $query->whereDate('created_at', date('Y-m-d', strtotime($endDate)));
            })
            ->where('orderstatus_id', '>', 1)
            ->when($search !== null, function (Builder $query) use ($search) {
                $query->where(function ($queryData) use ($search) {

                    $queryData->where('order_no', 'like', '%' . $search . '%')
                        ->orWhere('firstname', 'like', '%' . $search . '%')
                        ->orWhere('lastname', 'like', '%' . $search . '%')
                        ->orWhereHas('distributor', function ($subQuery1) use ($search) {
                            $subQuery1->where('firstname', 'like', "%$search%");
                            $subQuery1->orWhere('lastname', 'like', "%$search%");
                        })
                        ->orWhereHas('shop', function ($subQuery1) use ($search) {
                            $subQuery1->where('name', 'like', "%$search%");
                        })
                        ->orWhereHas('orderstatus', function ($subQuery1) use ($search) {
                            $subQuery1->where('order_status_name', 'like', "%$search%");
                        });
                });
            })
            ->get();

        return OrderResource::collection($orders);
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'order_no') ?? 'order_no';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $orders = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)
            ->select('id', 'order_no', 'invoice_no', 'firstname', 'lastname', 'payment_method', 'grand_total', 'total_quantity', 'orderstatus_id', 'created_at', 'distributor_id', 'shop_id', 'meeting_id')
            ->with([
                'orderstatus:id,order_status_name',
                'distributor:id,firstname,lastname',
                'shop:id,name',
                'meeting:id,start_latitude,start_longitude,end_latitude,end_longitude',
            ])
            ->when((isset($requestData->today) && $requestData->today === '1'), function ($query) {
                $query->whereDate('created_at', date('Y-m-d'));
            })
            ->where('orderstatus_id', '>', 1)
            ->get();
        $orderArray = [];

        // return $orders;
        if ($orders) {
            foreach ($orders as $key => $order) {
                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="' . $order->id . '" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['order_no'] = $order->order_no;
                $data['order_type'] = ($order->distributor_id != null && $order->shop_id != null) ? 'Retailer' : 'Primary';
                $data['invoice_no'] = $order->invoice_no;
                $data['distributor_name'] = ($order->distributor->firstname ?? '') . ' ' . ($order->distributor->lastname ?? '');
                $data['shop_name'] = $order->shop->name ?? '';
                $data['meeting'] = !empty($order->meeting) ? getGoogleMapLink($order->meeting->start_latitude, $order->meeting->start_longitude, 'Start') . ' ' . getGoogleMapLink($order->meeting->end_latitude, $order->meeting->end_longitude, 'End') : '';
                $data['customer_name'] = $order->firstname . ' ' . $order->lastname;
                // $data['payment_type'] = $order->payment_method;
                $data['order_status'] = $order->orderstatus->order_status_name ?? '';
                $data['total_amount'] = $data['order_type'] === 'Retailer' ? config('constants.currency_symbol') . ' ' . number_format($order->grand_total, 2) : '';
                $data['total_quantity'] = $order->total_quantity;
                $data['order_date'] = $order->created_at->format(config('constants.DATE_FORMATE')); // date('d-m-Y', strtotime($order->created_at));
                $data['action'] = "<a href='" . route('order.edit', $order->id) . "' title='View'><i class='fa fa-eye'></i></a>";
                $orderArray[] = $data;
            }
        }

        return $orderArray;
    }

    public function cancelOrder(string $orderNo)
    {
        $status = true;
        $message = 'Order canceled successfully.';
        $orderData = Order::select('id', 'order_no', 'orderstatus_id', 'payment_method', 'grand_total')->where(['order_no' => $orderNo])->firstOrFail();

        // Order History Update
        $orderHistory = new OrderHistory;
        $orderHistory->order_id = $orderData->id;
        $orderHistory->orderstatus_id = 4;
        $orderHistory->comment = 'Cancel Order';
        $orderHistory->save();

        // Order Status update
        $orderData->orderstatus_id = 4;
        $orderData->save();

        // send Mail
        dispatch(new SendOrderCancelJob($orderData->id));

        return ['status' => $status, 'message' => $message];
    }

    public function no_order(array $requesData)
    {
        $status = true;
        $message = 'No Order added successfully.';

        $noOrder = new NoOrder;
        $noOrder->user_id = Auth::user()->id;
        $noOrder->distributor_id = $requesData['distributor_id'];
        $noOrder->shop_id = $requesData['shop_id'];
        $noOrder->comment = $requesData['comment'];
        $noOrder->latitude =  $requesData['latitude'] ?? null;
        $noOrder->longitude =  $requesData['longitude'] ?? null;
        $noOrder->save();

        return ['status' => $status, 'message' => $message];
    }
    public function no_order_list()
    {
        $no_orders = NoOrder::with(['distributor', 'shop', 'user'])->orderBy('id', 'asc')->get();

        $orderArray = [];

        if ($no_orders) {
            foreach ($no_orders as $key => $order) {
                $data['srno'] = $key + 1;
                $data['user'] = ($order->user->firstname ?? '') . ' ' . ($order->user->lastname ?? '');
                $data['distributor_name'] = ($order->distributor->firstname ?? '') . ' ' . ($order->distributor->lastname ?? '');
                $data['shop_name'] = $order->shop->name ?? '';
                $data['comment'] = $order->comment;
                $data['order_date'] = $order->created_at->format(config('constants.DATE_FORMATE'));

                $orderArray[] = $data;
            }
        }

        return $orderArray;
    }

    public function apiNoOrderList(int $userId, ?string $startDate = null, ?string $endDate = null)
    {
        $noOrders = NoOrder::where('user_id', $userId)
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($endDate)));
            })
            ->with([
                'distributor' => function ($query) {
                    $query->select('id', 'firstname', 'lastname', 'mobile', 'country_id', 'state_id', 'city_id', 'zone_id');
                    $query->with(['country:id,name', 'state:id,name', 'city:id,name', 'zone:id,name']);
                },
                'shop' => function ($query) {
                    $query->select('id', 'name', 'contact_person_name', 'mobile', 'area_id', 'country_id', 'state_id', 'city_id', 'zone_id');
                    $query->with(['country:id,name', 'state:id,name', 'city:id,name', 'area:id,name', 'zone:id,name']);
                },
            ])
            ->get();

        return $noOrders = $noOrders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('d-m-Y');
        })->sortKeys();
    }
}
