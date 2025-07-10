<?php

namespace App\Services;

use App\Http\Resources\CartResource;
use App\Jobs\SendOrderInvoiceJob;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CouponHistory;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderProduct;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PDF;

class CartService
{
    public function storeCart($requestData)
    {
        $userId = Auth::user()->id;
        $distributorId = $requestData['distributor_id'] ?? null;
        $shopId = $requestData['shop_id'] ?? null;
        $status = true;
        $allData = null;
        $message = __('message.getSuccess');

        if (isset($requestData['products'])) {

            $variantIds = data_get($requestData['products'], '*.variant_id');
            $variantProducts = ProductVariant::whereIn('id', $variantIds)->with([
                'variantType:id,name',
                'variantValue:id,name',
                'product:id,name,image,seo_keyword,gst,cgst,sgst,code',
                'variant_price' => function ($query) {
                    $query->where('zone_id', Auth::user()->zone_id);
                },
            ])->get(); // ->toArray();

            \Log::info('variantProducts: '.$variantProducts);

            foreach ($requestData['products'] as $key => $cart) {
                $variantProduct = $variantProducts->where('id', $cart['variant_id'])->first();
                // $variantProduct = $variantProducts->where('product_id', $cart['product_id'])->first();

                // \Log::info($variantProduct->toArray());
                // \Log::info($variantProduct->qty ?? 0);
                // \Log::info($variantProduct->product_name);
                $quantity = (int) $cart['quantity'];

                /*if ($variantProduct->qty < $quantity || $variantProduct->qty <= 0) {
                    return  ['status' => false, 'message' => $variantProduct->product_name.' quantity is Out of stock, You can add other product.', 'data' => $allData];
                } else if ($quantity < 1 ) {
                    return  ['status' => false, 'message' => $variantProduct->product_name.' quantity is invalid.'];
                }*/ if (empty($variantProduct)) {
                    return ['status' => false, 'message' => $variantProduct->product_name.' Invalid Product'];
                } else {
                    $message = 'Product successfully added to cart.';
                    $cartData = Cart::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->where('product_id', $cart['product_id'])->where('variant_id', $cart['variant_id'])->first();

                    if ($quantity === 0) {
                        if (! empty($cartData)) {
                            $cartData->delete();
                        } else {
                            return ['status' => false, 'message' => $variantProduct->product_name.' quantity is invalid.'];
                        }
                    } else {
                        $product = $variantProduct->product;
                        \Log::info($variantProduct);
                        $productPrice = $variantProduct->variant_price;

                        $sellingPrice = $productPrice->price;
                        \Log::info($sellingPrice);
                        $taxData = gstCalculation($sellingPrice, $product->gst, 0);
                        \Log::info($taxData);
                        $totalGst = $taxData['igst'] * $quantity;

                        if (empty($cartData)) {
                            $cartData = new Cart;
                            $cartData->user_id = $userId;
                            $cartData->distributor_id = $requestData['distributor_id'];
                            $cartData->shop_id = $requestData['shop_id'] ?? null;
                            $cartData->meeting_id = $requestData['meeting_id'] ?? null;
                            $cartData->product_id = $cart['product_id'];
                            $cartData->variant_id = $cart['variant_id'];
                            $cartData->category_id = $variantProduct->category_id;
                            $cartData->name = ($shopId) ? $variantProduct->product_name : ($product['name'].' '.($productPrice->case_quantity ?? '').' Case');
                            $cartData->product_code = $product->code;
                            $cartData->image = $product->image;
                            $cartData->mrp = $sellingPrice;
                            $cartData->selling_price = $sellingPrice;
                            $cartData->variant_value = $variantProduct->variantValue->name??null;
                            $cartData->variant_type = ($shopId) ? ($variantProduct->variantType->name??null) : null;
                        }

                        $cartData->seo_url = $product->seo_keyword;
                        $cartData->quantity = $quantity;
                        $cartData->gst_per = $product->gst;
                        $cartData->gst_val = $taxData['igst'];
                        $cartData->cgst_per = $product->cgst;
                        $cartData->cgst_val = ($totalGst / 2);
                        $cartData->sgst_per = $product->sgst;
                        $cartData->sgst_val = ($totalGst / 2);
                        $cartData->total_gst_val = $totalGst;
                        $cartData->with_out_gst_price = $taxData['withoutGstPrice'];
                        $cartData->amount_without_gst = ($taxData['withoutGstPrice'] * $quantity);
                        $cartData->amount = ($sellingPrice * $quantity);

                        \Log::info($cartData);
                        $cartData->save();
                    }
                }
            }
        }

        $allCart = Cart::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->get();
        $couponHistory = CouponHistory::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->whereNull('order_id')->first();

        $billingAddressStateId = Address::select('state_id')->where('default_address', 1)->where('addressable_type', Distributor::class)->where('addressable_id', $distributorId)->first();

        $data['coupon_discount'] = $couponHistory->amount ?? 0;
        $data['totalAmount'] = array_sum(data_get($allCart, '*.amount'));
        $data['totalCGST'] = $billingAddressStateId === getSettingData('company_state') ? number_format(array_sum(data_get($allCart, '*.cgst_val')), 2) : 0;
        $data['totalSGST'] = $billingAddressStateId === getSettingData('company_state') ? number_format(array_sum(data_get($allCart, '*.sgst_val')), 2) : 0;
        $data['totalIGST'] = $billingAddressStateId !== getSettingData('company_state') ? number_format(array_sum(data_get($allCart, '*.total_gst_val')), 2) : 0;

        $gst = $billingAddressStateId !== getSettingData('company_state') ? $data['totalIGST'] : ($data['totalCGST'] + $data['totalSGST']);

        $data['total'] = number_format((($data['totalAmount'] + $gst) - $data['coupon_discount']), 2);
        $data['cart'] = CartResource::collection($allCart);
        $allData = [$data];

        return ['status' => $status, 'message' => $message, 'data' => $allData];
    }

    public function removeCart(array $requestData)
    {
        $userId = Auth::user()->id;
        $cartId = $requestData['cart_id'];
        $cart = Cart::find($cartId);

        if ($cart->user_id === $userId) {
            $cart->delete();

            return ['status' => true, 'message' => 'Product successfully removed from cart.', 'data' => null];
        }

        return ['status' => false, 'message' => 'First add product into cart.', 'data' => null];
    }

    public function storeOrder()
    {
        $request = request();
        $user = Auth::user();
        $userId = Auth::user()->id;
        $distributorId = $request->distributor_id ?? null;
        $shopId = $request->shop_id ?? null;
        $meetingId = $request->meeting_id ?? null;
        $couponHistory = CouponHistory::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->whereNull('order_id')->with('coupon:id,code')->first();
        $shippingAddressId = $request->shipping_address_id ?? null;
        $billingAddressId = $request->billing_address_id ?? null;
        $paymentMode = 'COD';
        $order_type = $request->order_type;

        $cartData = Cart::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->get();
        // check cart data
        if ($cartData->isEmpty() === true) {
            return ['status' => false, 'message' => 'Your cart data is not valid. Please add products again.'];
        }

        // Address Data
        $shippingAddress = Address::where('id', $shippingAddressId)->with(['country:id,name', 'state:id,name', 'city:id,name'])->first();
        if (empty($shippingAddress)) {
            $shippingAddress = Address::where(['default_address' => 1])
                ->where('addressable_type', Distributor::class)->where('addressable_id', $distributorId)
                ->with(['country:id,name', 'state:id,name', 'city:id,name'])->first();
        }

        if (! $shippingAddress) {
            return ['status' => false, 'message' => 'Please select valid shipping address.'];
        }

        // Address Data
        $billingAddress = Address::where('id', $billingAddressId)->with(['country:id,name', 'state:id,name', 'city:id,name'])->first();
        if (empty($billingAddress)) {
            $billingAddress = Address::where(['default_address' => 1])
                ->where('addressable_type', Distributor::class)->where('addressable_id', $distributorId)
                ->with(['country:id,name', 'state:id,name', 'city:id,name'])->first();
        }

        if (! $billingAddress) {
            return ['status' => false, 'message' => 'Please select valid billing address.'];
        }

        $orderCalculation = $this->orderCalculation($cartData->toArray(), $couponHistory);

        return $this->newOrder($user, $shippingAddress, $billingAddress, $paymentMode, $orderCalculation, $cartData, $distributorId, $shopId, $meetingId, $couponHistory, $order_type);
    }

    private function orderCalculation(array $cartData, ?CouponHistory $couponHistory = null)
    {
        $orderCalculation = [];
        $total_quantity = $sub_total = $gst_per = $shipping_charge = $grand_total = $total_gst = $coupon_id = $coupon_code = $coupon_discount = 0;

        foreach ($cartData as $key => $value) {
            $total_quantity += $value['quantity'];
            $sub_total += $value['amount_without_gst'];
            $gst_per = $value['gst_per'];
        }

        // check coupon data
        if (! empty($couponHistory)) {
            $orderCalculation['coupon_id'] = $couponHistory->coupon_id;
            $orderCalculation['coupon_code'] = $couponHistory->coupon->code;
            $orderCalculation['coupon_discount'] = $couponHistory->amount;
            $sub_total = $sub_total - $couponHistory->amount;
        }

        $total_gst = gstCalculation($sub_total, $gst_per, 1)['igst'];

        $orderCalculation['total_quantity'] = $total_quantity;
        $orderCalculation['sub_total'] = $sub_total;
        $orderCalculation['total_gst'] = $total_gst;
        $orderCalculation['shipping_charge'] = $shipping_charge;
        $orderCalculation['grand_total'] = $sub_total + $total_gst + $shipping_charge;
        $orderCalculation['cgst'] = $orderCalculation['sgst'] = $total_gst / 2;

        return $orderCalculation;
    }

    private function newOrder(User $user, Address $shippingAddress, Address $billingAddress, string $paymentMode, array $orderCalculation, Collection $cartData, ?int $distributorId, ?int $shopId, ?int $meetingId, ?CouponHistory $couponHistory, $order_type)
    {
        $request = request();

        $userId = $user->id;

        $order = new Order;
        $order->user_id = $userId;
        $order->distributor_id = $distributorId;
        $order->shop_id = $shopId;
        $order->meeting_id = $meetingId;
        $order->order_no = generateOrderNumber(getSettingData('order_prefix'), $distributorId, $shopId);
        $order->invoice_no = generateInvoiceNumber(getSettingData('invoice_prefix'));
        $order->firstname = $user->firstname ?? null;
        $order->lastname = $user->lastname ?? null;
        $order->email = $user->email;
        $order->mobile = $user->mobile;
        $order->latitude = $request->latitude ?? null;
        $order->longitude = $request->longitude ?? null;

        // Shipping Address
        $order->shipping_address_id = $shippingAddress->id;
        $order->shipping_firstname = $shippingAddress->first_name;
        $order->shipping_lastname = $shippingAddress->last_name;
        $order->shipping_email = $shippingAddress->email;
        $order->shipping_mobile = $shippingAddress->mobile_no;
        $order->shipping_country_name = $shippingAddress->country->name;
        $order->shipping_state_name = $shippingAddress->state->name;
        $order->shipping_city_name = $shippingAddress->city->name;
        $order->shipping_pincode = $shippingAddress->pincode;

        // Billing Address
        $order->billing_address_id = $billingAddress->id;
        $order->billing_firstname = $billingAddress->first_name;
        $order->billing_lastname = $billingAddress->last_name;
        $order->billing_email = $billingAddress->email;
        $order->billing_mobile = $billingAddress->mobile_no;
        $order->billing_country_name = $billingAddress->country->name;
        $order->billing_state_name = $billingAddress->state->name;
        $order->billing_city_name = $billingAddress->city->name;
        $order->billing_pincode = $billingAddress->pincode;

        $order->payment_method = $paymentMode;
        $order->payment_code = null;
        $order->orderstatus_id = 1;
        $order->is_paid = 0;
        $order->sub_total = round($orderCalculation['sub_total'], 2);
        $order->total_gst = round($orderCalculation['total_gst'], 2);
        $order->cgst = $orderCalculation['cgst'];
        $order->sgst = $orderCalculation['sgst'];
        $order->shipping_charge = round($orderCalculation['shipping_charge'], 2);
        $order->grand_total = round($orderCalculation['grand_total']);
        $order->total_quantity = $orderCalculation['total_quantity'];
        $order->order_pdf = null;
        $order->coupon_id = $orderCalculation['coupon_id'] ?? null;
        $order->coupon_code = $orderCalculation['coupon_code'] ?? null;
        $order->coupon_discount = round($orderCalculation['coupon_discount'] ?? 0, 2);

        if ($order->save()) {

            // Order History Update
            $orderHistory = new OrderHistory;
            $orderHistory->order_id = $order->id;
            $orderHistory->orderstatus_id = 1;
            $orderHistory->comment = 'New Order Placed';
            $orderHistory->save();

            foreach ($cartData as $orderkey => $ordervalue) {

                $variantData = ProductVariant::where('id', $ordervalue['variant_id'])->first();
                $variantType = $variantValue = '';
                if ($variantData) {
                    $variantType = $variantData['variant_type'];
                    $variantValue = $variantData['variant_value'];
                }

                $orderProduct = new OrderProduct;
                $orderProduct->order_id = $order->id;
                $orderProduct->category_id = $ordervalue['category_id'];
                $orderProduct->product_id = $ordervalue['product_id'];
                $orderProduct->product_name = $ordervalue['name'];
                $orderProduct->product_code = $ordervalue['product_code'];
                $orderProduct->variant_id = $ordervalue['variant_id'];
                $orderProduct->variant_type = $variantType;
                $orderProduct->variant_value = $variantValue;
                $orderProduct->product_quantity = $ordervalue['quantity'];
                $orderProduct->product_image = $ordervalue['image'];
                $orderProduct->product_mrp = $ordervalue['mrp'];
                $orderProduct->product_selling_price = $ordervalue['selling_price'];
                $orderProduct->gst_per = $ordervalue['gst_per'];
                $orderProduct->gst_val = $ordervalue['gst_val'];
                $orderProduct->total_amount = $ordervalue['amount'];
                $orderProduct->amount_without_gst = $ordervalue['amount_without_gst'];
                $orderProduct->total_gst_val = $ordervalue['total_gst_val'];
                $orderProduct->with_out_gst_price = $ordervalue['with_out_gst_price'];
                $orderProduct->save();
            }

            if ($paymentMode == 'Online') {
                // CashFree Integration
                // return app('cashfree.service')->createOrder($order);
            } else {

                // Order History Update
                $order_history_data = new OrderHistory;
                $order_history_data->order_id = $order->id;
                $order_history_data->orderstatus_id = 2;
                $order_history_data->comment = 'Order In Progress';
                $order_history_data->save();

                // Coupon History Update
                if (isset($orderCalculation['coupon_code'])) {
                    $couponHistory->order_id = $order->id;
                    $couponHistory->save();
                }

                dispatch(new SendOrderInvoiceJob($order->id));

                // Cart::where('user_id', $userId)->delete();
                // $cartData->delete();
                $cartData = Cart::loginUser($userId)->distributorUser($distributorId)->shopUser($shopId)->delete();

                if($shopId != null)
                {
                    $order->load('shop');
                    $pdfName = 'Retailing Order (' . $order->shop->name.').pdf';
                    $receiptName = 'RETAILER ORDER';
                }
                else
                {
                    $order->load('distributor');
                    $pdfName = 'Primary Order ('.$order->distributor->firstname.').pdf';
                    $receiptName = 'PRIMARY ORDER';
                }

                $pdf = PDF::loadView('pdf.new_invoice', ['receiptName' => $receiptName,'order_data' => $order, 'user_data' => $user, 'billingaddress' => $order->billingaddress, 'shippingaddress' => $order->shippingaddress, 'order_type' => $order_type]);
                $pdfContent = $pdf->download()->getOriginalContent();

                $client = Storage::createLocalDriver(['root' => storage_path('app/public').'/invoices']);
                $client->put($pdfName, $pdfContent);

                $order->update(['orderstatus_id' => 2, 'order_pdf' => 'invoices/'.$pdfName]);

                return ['status' => true, 'data' => ['order_no' => $order->order_no, 'order_pdf' => asset('storage/'.$order->order_pdf)], 'message' => 'Your Order Place Successfully.'];
            }
        }

        return ['status' => false, 'message' => __('message.oopsError')];
    }
}
