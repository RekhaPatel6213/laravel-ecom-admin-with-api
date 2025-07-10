<?php

use App\Models\CategoryType;
use App\Models\Country;
use App\Models\Designation;
use App\Models\Distributor;
use App\Models\MeetingType;
use App\Models\NoOrder;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Shop;
use App\Models\State;
use App\Models\VariantType;
use App\Models\VariantValue;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

function getSettingData($key)
{
    $setting = Cache::get('configSetting');
    if (empty($setting)) {
        $setting = Setting::select('config_key', 'config_value')->pluck('config_value', 'config_key')->toArray();
        Cache::put('configSetting', $setting);
    }

    if (! empty($setting[$key])) {
        return $setting[$key];
    }

    return null;
}

function getImage($image_path)
{
    if (isset($image_path) && ! empty($image_path)) {
        $image = asset('storage/'.$image_path);
    } else {
        $image = asset('admin/assets/images/default.png');
    }

    return $image;
}

function variant_type()
{
    $variantTypes = \Cache::get('variantTypes');
    if (empty($variantTypes)) {
        $variantTypes = VariantType::where('status', 1)->pluck('name', 'id');
        \Cache::put('variantTypes', $variantTypes);
    }

    return $variantTypes;
}

function variant_value()
{
    $variantValues = \Cache::get('variantValues');
    if (empty($variantValues)) {
        $variantValues = VariantValue::where('status', 1)->pluck('name', 'id');
        \Cache::put('variantValues', $variantValues);
    }

    return $variantValues;
}

if (! function_exists('getCountries')) {
    function getCountries(?array $ids = null)
    {
        return Cache::get('countries', function () use ($ids) {
            return Country::where('status', config('constants.ACTIVE'))
                ->when($ids !== null, function (Builder $query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        });
    }
}

if (! function_exists('getStates')) {
    function getStates(string $pluck = 'id', ?array $ids = null)
    {
        return Cache::get('states', function () use ($ids, $pluck) {
            return State::where('status', config('constants.ACTIVE'))
                ->when($ids !== null, function (Builder $query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->orderBy('name', 'asc')
                ->pluck('name', $pluck)
                ->toArray();
        });
    }
}

if (! function_exists('getCategoryTypes')) {
    function getCategoryTypes(?array $ids = null)
    {
        return Cache::get('categoryTypes', function () use ($ids) {
            return CategoryType::where('status', config('constants.ACTIVE'))
                ->when($ids !== null, function (Builder $query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        });
    }
}

if (! function_exists('getDistributors')) {
    function getDistributors(?array $ids = null)
    {
        return Cache::get('distributors', function () use ($ids) {
            return Distributor::where('status', config('constants.ACTIVE'))
                ->when($ids !== null, function (Builder $query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->orderBy('fullname', 'asc')
                ->pluck(DB::raw('CONCAT(firstname," ",lastname) as fullname'), 'id')
                // ->pluck('firstname as fullname', 'id')
                ->toArray();
        });
    }
}

if (! function_exists('getZones')) {
    function getZones(?array $ids = null)
    {
        return Cache::get('zones', function () use ($ids) {
            return Zone::where('status', config('constants.ACTIVE'))
                ->when($ids !== null, function (Builder $query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        });
    }
}

if (! function_exists('getMeetingTypes')) {
    function getMeetingTypes(?array $ids = null)
    {
        return Cache::get('meetingTypes', function () {
            return MeetingType::select('id', 'name')->where('status', config('constants.ACTIVE'))->orderBy('name', 'ASC')->pluck('name', 'id')->toArray();
        });
    }
}

if (! function_exists('getDesignations')) {
    function getDesignations(?array $ids = null)
    {
        return Cache::get('designations', function () use ($ids) {
            return Designation::where('status', config('constants.ACTIVE'))
                ->when($ids !== null, function (Builder $query) use ($ids) {
                    $query->whereIn('id', $ids);
                })
                ->orderBy('name', 'asc')
                ->pluck('name', 'id')
                ->toArray();
        });
    }
}

if (! function_exists('getGoogleMapLink')) {
    function getGoogleMapLink(?string $latitude = null, ?string $longitude = null, ?string $name = null)
    {
        $mapLink = null;
        if ($latitude !== null && $longitude !== null) {
            $mapUrl = 'https://www.google.com/maps?q='.$latitude.','.$longitude;
            $mapLink = '<a href="'.$mapUrl.'" target="_blank">'.($name ?? 'Map Link').'</a>';
        }

        return $mapLink;
    }
}

if (! function_exists('couponTypes')) {
    function couponTypes()
    {
        return $coupon_type = [
            config('constants.PERCENTADE') => config('constants.PERCENTADE'),
            config('constants.FIXED_AMOUNT') => config('constants.FIXED_AMOUNT'),
        ];
    }
}

if (! function_exists('noOrderCount')) {
    function noOrderCount(int $userId, ?string $startDate = null, ?string $endDate = null)
    {
        return NoOrder::where('user_id', $userId)
            ->when(($startDate !== null && $endDate !== null), function ($query) use ($startDate, $endDate) {
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($startDate)));
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($endDate)));
            })->count();
    }
}

/**
 * @param  float  $price  product price
 * @param  float  $percent  pass gst percentage
 * @param  int  $type  pass type if 0 indicate Include GST && 1 indicate Exclude GST
 * @return array
 */
function gstCalculation($price, $percent, $type)
{
    $return_array = [];
    $withoutgst = $total = 0;

    if ($type == 1) {
        $gst_amount = ($price * $percent) / 100;
        $total = $price + $gst_amount;
        $percentcgst = number_format($gst_amount / 2, 3);
        $percentsgst = number_format($gst_amount / 2, 3);
        $withoutgst = $price - $gst_amount;
        $return_array = [
            'igst' => $gst_amount,
            'cgst' => $percentcgst,
            'sgst' => $percentsgst,
            'withGstPrice' => $total,
            'withoutGstPrice' => $withoutgst,
        ];
    } else {
        $gst_amount = $price - ($price * (100 / (100 + $percent)));

        $percentcgst = number_format($gst_amount / 2, 3);
        $percentsgst = number_format($gst_amount / 2, 3);
        $withoutgst = $price - $gst_amount;
        $total = $withoutgst + $gst_amount;

        $return_array = [
            'igst' => $gst_amount,
            'cgst' => $percentcgst,
            'sgst' => $percentsgst,
            'withGstPrice' => $total,
            'withoutGstPrice' => $withoutgst,
        ];
    }

    return $return_array;
}

function generateOrderNumber($name,$distributorId,$shopId=null)
{
    $latestOrder = Order::orderBy('id', 'DESC')->first();
    $prefix = $name;

    if ($latestOrder) {
        $inserted_orderno = str_pad(($latestOrder->id + 1), 5, '0', STR_PAD_LEFT);
    } else {
        $inserted_orderno = str_pad(1, 5, '0', STR_PAD_LEFT);
    }

    if($shopId != null)
    {
        $shopdata = Shop::where('id', $shopId)->first();
        return $orderno = 'Retail_Order_'.$shopdata->name.'-'.$inserted_orderno;
    }
    else
    {
        $distributordata = Distributor::where('id', $distributorId)->first();
        return $orderno = 'Primary_Order_'.$distributordata->firstname.'_'.$distributordata->lastname.'-'.$inserted_orderno;
    }

    /* if ($latestOrder) {
        return $prefix.str_pad(($latestOrder->id + 1), 5, '0', STR_PAD_LEFT);
    } else {
        return $prefix.str_pad(1, 5, '0', STR_PAD_LEFT);
    } */
}

function generateInvoiceNumber($name)
{
    $latestOrder = Order::orderBy('id', 'DESC')->first();
    $prefix = $name;

    if ($latestOrder) {
        return $prefix.str_pad(($latestOrder->id + 1), 5, '0', STR_PAD_LEFT);
    } else {
        return $prefix.str_pad(1, 5, '0', STR_PAD_LEFT);
    }
}

function amt_to_words($number)
{
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $digits_length = strlen($no);
    $i = 0;
    $str = [];
    $words = [
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety',
    ];

    $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];

    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $str[] = ($number < 21) ? $words[$number].' '.$digits[$counter].$plural : $words[floor($number / 10) * 10].' '.$words[$number % 10].' '.$digits[$counter].$plural;
        } else {
            $str[] = null;
        }
    }

    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? ' And '.($words[$decimal - $decimal % 10]).' '.($words[$decimal % 10]).' Paise ' : '';

    return ($Rupees ? $Rupees.' Rupees ' : '').$paise.' Only';
}
