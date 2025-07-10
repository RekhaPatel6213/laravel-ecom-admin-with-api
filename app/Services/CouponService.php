<?php

namespace App\Services;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponHistory;
use App\Repositories\CouponRepository;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    protected $repository;

    public function __construct(CouponRepository $couponRepository)
    {
        $this->repository = $couponRepository;
    }

    public function list($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'name') ?? 'name';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';
        $coupons = $this->repository->getQueryBuilder($search, $sortOn, $sortOrder)->get();
        $couponArray = [];

        if ($coupons) {
            foreach ($coupons as $key => $coupon) {
                $currentDate = strtotime(date('Y-m-d'));
                $startDate = strtotime($coupon->start_date);
                $endDate = strtotime($coupon->end_date);

                if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {
                    $isExpire = '<div class="demo-inline-spacing"><span class="badge bg-success m-0">Live</span></div>';
                } elseif (($currentDate < $startDate) && ($currentDate < $endDate)) {
                    $isExpire = '<div class="demo-inline-spacing"><span class="badge bg-info m-0">Up Coming</span></div>';
                } else {
                    $isExpire = '<div class="demo-inline-spacing"><span class="badge bg-danger m-0">Expire</span></div>';
                }

                $data['checkbox'] = '<input type="checkbox" name="data[data_id][]" value="'.$coupon->id.'" class="form-check-input checkboxes">';
                $data['srno'] = $key + 1;
                $data['name'] = $coupon->name;
                $data['code'] = $coupon->code;
                $data['type'] = $coupon->type == config('constants.PERCENTADE') ? config('constants.PERCENTADE') : config('constants.FIXED_AMOUNT');
                $data['discount'] = $coupon->discount;
                $data['start_date'] = $coupon->start_date->format(config('constants.DATE_FORMATE'));
                $data['end_date'] = $coupon->end_date->format(config('constants.DATE_FORMATE'));
                $data['is_expire'] = $isExpire;
                $data['status'] = "<div class='form-check form-switch'><input type='checkbox' class='form-check-input on_off' value='".$coupon->id."' ".($coupon->status == config('constants.ACTIVE') ? 'checked' : '').'/></div>';
                $data['action'] = "<a href='".route('coupon.edit', $coupon->id)."' title='Edit'><i class='fa fa-edit'></i></a>";
                $couponArray[] = $data;
            }
        }

        return $couponArray;
    }

    public function create(array $requestData)
    {
        // dd($requestData);
        $startDate = explode(' to ', $requestData['start_date']);
        $requestData['start_date'] = $startDate[0];
        $requestData['end_date'] = $startDate[1] ?? $startDate[0];

        return $this->repository->updateOrCreate($requestData, null);
    }

    public function update(array $requestData, Coupon $coupon)
    {
        $startDate = explode(' to ', $requestData['start_date']);
        $requestData['start_date'] = $startDate[0];
        $requestData['end_date'] = $startDate[1] ?? $startDate[0];

        return $this->repository->updateOrCreate($requestData, $coupon);
    }

    public function bulkDelete(array $requestData)
    {
        return $this->repository->bulkDelete($requestData, 'state', 'Country');
    }

    public function bulkUpdate(string $columnName, array $requestData)
    {
        $status = false;
        $message = __('message.oopsError');

        if ($this->repository->bulkUpdate($columnName, $requestData)) {
            $status = true;
            $message = __('message.statusSuccessUpdate');
        }

        return ['status' => $status, 'message' => $message];
    }

    public function history($requestData)
    {
        $search = $requestData->query('search', null) ?? null;
        $sortOn = $requestData->query('sortOn', 'created_at') ?? 'created_at';
        $sortOrder = $requestData->query('sort', 'asc') ?? 'asc';

        $histories = CouponHistory::when($search !== null, function ($query1) use ($search) {
            $query1->where(function ($query2) use ($search) {
                foreach ($this->model::SEARCH_FIELDS as $field) {
                    $query2->orWhere($field, 'LIKE', '%'.$search.'%');
                }
            });
        })
            ->with([
                'order:id,order_no,grand_total',
                // 'user:id,firstname,lastname'
                'coupon:id,name,code',
                'shop:id,name',
                'distributor:id,firstname,lastname',
            ])
            ->whereNotNull('order_id')
            ->orderBy($sortOn, $sortOrder)
            ->get();

        $historyArray = [];

        if ($histories) {
            foreach ($histories as $key => $history) {
                $data['srno'] = $key + 1;
                $data['order_no'] = $history->order->order_no ?? '';
                // $data['sales_person'] = ($history->distributor->firstname??'').' '.($history->distributor->lastname??'');
                $data['distributor'] = ($history->distributor->firstname ?? '').' '.($history->distributor->lastname ?? '');
                $data['shop'] = $history->shop->name ?? '';
                $data['counpon_name'] = $history->coupon->name ?? '';
                $data['counpon_code'] = $history->coupon->code ?? '';
                $data['discount_amount'] = config('constants.currency_symbol').' '.number_format($history->amount ?? 0, 2);
                $data['order_amount'] = config('constants.currency_symbol').' '.number_format($history->order->grand_total ?? 0, 2);
                $data['created_at'] = $history->created_at->format(config('constants.DATE_FORMATE'));
                $data['action'] = '';
                $historyArray[] = $data;
            }
        }

        return $historyArray;
    }

    /*public function sessionStore(string $key, array $data)
    {
        session()->put($key, [
            'id' => $data['id'],
            'code' => $data['code'],
            'value' => $data['value'],
        ]);
    }*/

    public function checkValidCoupon(array $requestData)
    {
        $allData = null;
        $userId = Auth::user()->id;
        $discount = 0;
        $couponCode = $requestData['coupon_code'];
        $distributorId = $requestData['distributor_id'] ?? null;
        $shopId = $requestData['shop_id'] ?? null;
        $status = false;
        $message = __('message.invalidCoupon');

        $coupon = Coupon::where('code', $couponCode)
            ->where('status', 1)
            ->where('start_date', '<=', date('Y-m-d'))
            ->where('end_date', '>=', date('Y-m-d'))
            ->with(['history' => function ($query) use ($userId, $distributorId, $shopId) {
                $query->select('coupon_id', 'order_id', 'user_id', 'distributor_id', 'shop_id');
                $query->loginUser($userId)->distributorUser($distributorId)->shopUser($shopId);
                // $query->whereNotNull('order_id');
            }])
            ->first();

        if ($coupon) {

            if ($coupon->total_coupon <= $coupon->history->count()) {
                $message = __('message.noCouponAvailable');

                return ['status' => $status, 'message' => $message];
            }

            $couponHistoryCount = $coupon->history->whereNotNull('order_id')->count();
            if ($coupon->coupon_use_time <= $couponHistoryCount) {
                $message = __('message.couponAlreadyUsed', ['value' => $coupon->coupon_use_time]);

                return ['status' => $status, 'message' => $message];
            }

            $carts = Cart::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->get();

            $totalPrice = $carts->sum('amount_without_gst');

            if ($totalPrice) {
                $message = __('message.minimumPurchesed', ['value' => $coupon->min_order_value]);

                if ($totalPrice >= $coupon->min_order_value) {

                    $discount = $coupon->discount;
                    if ($coupon->type === config('constants.PERCENTADE')) {
                        $discount = ($coupon->discount / 100) * $totalPrice;
                    }

                    if ($discount > $coupon->max_discount_allow) {
                        $discount = $coupon->max_discount_allow;
                    }

                    $discount = round($discount);

                    $couponHistory = $coupon->history->whereNull('order_id')->first();
                    if (empty($couponHistory)) {
                        $couponHistory = new CouponHistory;
                        $couponHistory->coupon_id = $coupon->id;
                        $couponHistory->user_id = $userId;
                        $couponHistory->distributor_id = $distributorId;
                        $couponHistory->shop_id = $shopId;
                    }
                    $couponHistory->amount = $discount;
                    $couponHistory->save();

                    $status = true;
                    $message = __('message.couponAppliedSuccess');
                    $billingAddressStateId = Address::select('state_id')->where('default_address', 1)->where('addressable_type', Distributor::class)->where('addressable_id', $distributorId)->first()->state_id;

                    $data['coupon_discount'] = $discount;
                    $data['totalAmount'] = array_sum(data_get($carts, '*.amount'));
                    $data['totalCGST'] = $billingAddressStateId === getSettingData('company_state') ? array_sum(data_get($allCart, '*.cgst_val')) : 0;
                    $data['totalSGST'] = $billingAddressStateId === getSettingData('company_state') ? array_sum(data_get($allCart, '*.sgst_val')) : 0;
                    $data['totalIGST'] = $billingAddressStateId !== getSettingData('company_state') ? array_sum(data_get($allCart, '*.total_gst_val')) : 0;

                    $gst = $billingAddressStateId !== getSettingData('company_state') ? $data['totalIGST'] : ($data['totalCGST'] + $data['totalSGST']);

                    $data['total'] = number_format((($data['totalAmount'] + $gst) - $data['coupon_discount']), 2);
                    $data['cart'] = CartResource::collection($carts);
                    $allData = [$data];
                }
            }
        }

        return ['status' => $status, 'message' => $message, 'data' => $allData];
    }

    public function removeCoupon(array $requestData)
    {
        $userId = Auth::user()->id;
        $status = false;
        $message = __('message.invalidCoupon');
        $distributorId = $requestData['distributor_id'] ?? null;
        $shopId = $requestData['shop_id'] ?? null;

        $couponHistory = CouponHistory::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->whereNull('order_id')->first();

        if ($couponHistory) {
            $couponHistory->delete();
            $status = true;
            $message = __('message.couponRemovedSuccess');
        }

        return ['status' => $status, 'message' => $message, 'data' => null];
    }
}
