<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tada;
use App\Models\Order;
use App\Models\Meeting;
use App\Models\NoOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\OrderRepository;
use App\Repositories\TadaRepository;
use App\Repositories\MeetingRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ShopRepository;

class DashboardController extends Controller
{
    protected $orderRepository;
    protected $tadaRepository;
    protected $meetingRepository;
    protected $productRepository;
    protected $shopRepository;

    public function __construct(OrderRepository $orderRepository, TadaRepository $tadaRepository, MeetingRepository $meetingRepository, ProductRepository $productRepository, ShopRepository $shopRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->tadaRepository = $tadaRepository;
        $this->meetingRepository = $meetingRepository;
        $this->productRepository = $productRepository;
        $this->shopRepository = $shopRepository;
    }

    public function __invoke(Request $request)
    {
        $total_user = \App\Models\User::where('role_id', config('constants.COMPANY_ROLE_ID'))->where('status', config('constants.ACTIVE'))->count();
        $total_distributor = \App\Models\Distributor::where('status', config('constants.ACTIVE'))->count();
        $product_count = \App\Models\Product::where('status', config('constants.ACTIVE'))->count();
        $category_count = \App\Models\Category::where('status', config('constants.ACTIVE'))->count();

        $order_count = \App\Models\Order::where('orderstatus_id', '>=', 2)->whereNotNull('meeting_id')->count();
        $direct_order_count = \App\Models\Order::where('orderstatus_id', '>=', 2)->whereNull('meeting_id')->count();

        $todayCount = $this->meetingRepository->getTodayMeetingCount();
        $productive = $this->orderRepository->getTodayProductiveCount();
        $noproductive = $this->orderRepository->getTodayUnproductiveCount();
        $primaryorder = $this->orderRepository->getTodayPrimaryOrderCount();
        $todayAmountSum = $this->tadaRepository->getTodayTadaAmountSum();

        $GraphMeetingCount = $this->meetingRepository->getGraphMeetingCount();
        $GraphProductMrp = $this->productRepository->getGraphProductMrp();
        $GraphOrdersCount = $this->orderRepository->getGraphOrdersCount();
        $GraphTadaAmount = $this->tadaRepository->getGraphTadaAmount();
        $GraphNullShopOrders = $this->shopRepository->getGraphNullShopOrders();
        $productiveUnproductiveData = $this->orderRepository->getProductiveUnproductiveData();

        return view('admin_dashboard', compact(
            'total_user',
            'total_distributor',
            'category_count',
            'product_count',
            'order_count',
            'direct_order_count',
            'todayCount',
            'productive',
            'noproductive',
            'primaryorder',
            'todayAmountSum',
            'GraphMeetingCount',
            'GraphProductMrp',
            'GraphOrdersCount',
            'GraphTadaAmount',
            'GraphNullShopOrders',
            'productiveUnproductiveData'
        ));
    }

    public function getMeetingCount(Request $request)
    {
        $validated = $request->validate([
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after_or_equal:start'
        ]);

        $results = $this->meetingRepository->getMonthlyMeetingCounts(
            $validated['start'] ?? null,
            $validated['end'] ?? null
        );

        return response()->json($results);
    }

    public function getProductMrpSummary(Request $request)
    {
        $validated = $request->validate([
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after_or_equal:start'
        ]);

        $results = $this->productRepository->getMonthlyMrpSummary(
            $validated['start'] ?? null,
            $validated['end'] ?? null
        );

        return response()->json($results);
    }

    public function getOrdersCount(Request $request)
    {
        $validated = $request->validate([
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after_or_equal:start'
        ]);

        $results = $this->orderRepository->getMonthlyOrderCounts(
            $validated['start'] ?? null,
            $validated['end'] ?? null
        );

        return response()->json($results);
    }

    public function getTadaAmountSummary(Request $request)
    {
        $validated = $request->validate([
            'start' => 'sometimes|date',
            'end' => 'sometimes|date|after_or_equal:start'
        ]);

        $results = $this->tadaRepository->getMonthlyAmountSummary(
            $validated['start'] ?? null,
            $validated['end'] ?? null
        );

        return response()->json($results);
    }

    public function getNullShopOrdersCount(Request $request)
    {
        $validated = $request->validate([
            'start' => 'sometimes|date|required_with:end',
            'end' => 'sometimes|date|required_with:start|after_or_equal:start',
            'group_by' => 'sometimes|in:month,quarter,year'
        ]);

        $periodFormat = match ($validated['group_by'] ?? 'month') {
            'quarter' => '%Y-Q%q',
            'year' => '%Y',
            default => '%Y-%m'
        };

        $results = $this->orderRepository->getNullShopOrderCounts(
            $validated['start'] ?? null,
            $validated['end'] ?? null,
            12,
            $periodFormat
        );

        return response()->json($results);
    }

    public function getProductiveVsUnproductiveOrders(Request $request)
    {
        $validated = $request->validate([
            'start' => 'sometimes|date|required_with:end',
            'end' => 'sometimes|date|required_with:start|after_or_equal:start'
        ]);

        $productiveQuery = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
            DB::raw('"productive" as type')
        );

        $unproductiveQuery = NoOrder::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count'),
            DB::raw('"unproductive" as type')
        );

        if (isset($validated['start']) && isset($validated['end'])) {
            $productiveQuery->whereBetween('created_at', [$validated['start'], $validated['end']]);
            $unproductiveQuery->whereBetween('created_at', [$validated['start'], $validated['end']]);
        }

        $results = $productiveQuery->groupBy('date')
            ->unionAll($unproductiveQuery->groupBy('date'))
            ->orderBy('date')
            ->get();

        return response()->json($results);
    }
}
