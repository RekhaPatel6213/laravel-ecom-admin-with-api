@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>
        <div class="content-header row">
            <div class="col-12">
                <div class="breadcrumb-wrapper-box without-btn-breadcrumb mt-2 mb-2">
                    <div class="row align-items-center">
                        <div class="content-header-left col-xl-9 col-md-12 col-12">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0">Order </h2>
                                    <div class="breadcrumb-wrapper">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                            <li class="breadcrumb-item"><a href="{{ route('order.index') }}">Order</a></li>
                                            <li class="breadcrumb-item"><a href="javascript:void(0);">View</a></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Vertical Wizard -->
            <section class="vertical-wizard order_ver">
                <div class="bs-stepper vertical vertical-wizard-example">
                    <div class="bs-stepper-header">
                        <div class="step" data-target="#account-details-vertical" role="tab" id="account-details-vertical-trigger">
                            <button type="button" class="step-trigger p-0 w-100">
                                <!-- <span class="bs-stepper-box">1</span> -->
                                <span class="bs-stepper-label w-100">
                                    <span class="bs-stepper-title cb">Order Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#payment-details-vertical" role="tab" id="payment-details-vertical-trigger">
                            <button type="button" class="step-trigger p-0 w-100">
                                <!-- <span class="bs-stepper-box">1</span> -->
                                <span class="bs-stepper-label w-100">
                                    <span class="bs-stepper-title cb">Billing Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#shipping-details-vertical" role="tab" id="shipping-details-vertical-trigger">
                            <button type="button" class="step-trigger p-0 w-100">
                                <!-- <span class="bs-stepper-box">1</span> -->
                                <span class="bs-stepper-label w-100">
                                    <span class="bs-stepper-title cb">Shipping Details</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#personal-info-vertical" role="tab" id="personal-info-vertical-trigger">
                            <button type="button" class="step-trigger p-0 w-100">
                                <!-- <span class="bs-stepper-box">2</span> -->
                                <span class="bs-stepper-label w-100">
                                    <span class="bs-stepper-title cb">Order Product</span>
                                </span>
                            </button>
                        </div>
                        <div class="step" data-target="#history-details-vertical" role="tab" id="history-details-vertical-trigger">
                            <button type="button" class="step-trigger p-0 w-100">
                                <!-- <span class="bs-stepper-box">2</span> -->
                                <span class="bs-stepper-label w-100">
                                    <span class="bs-stepper-title cb">Order History</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="bs-stepper-content">
                        <div id="account-details-vertical" class="content" role="tabpanel" aria-labelledby="account-details-vertical-trigger">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th> <label class="form-label" for="vertical-username">Order Id</label> </th>
                                        <th> <label class="form-label" for="vertical-email">{{ $order->id }}</label></th>
                                    </tr>
                                    <tr>
                                        <th> <label class="form-label" for="vertical-username">Invoice Number</label> </th>
                                        <th> <label class="form-label" for="vertical-email">{{ $order->invoice_no }}</label></th>
                                    </tr>
                                    <tr>
                                        <th>  <label class="form-label" for="vertical-username">Customer Name</label> </th>
                                        <th>  <label class="form-label" for="vertical-email">{{ $order->firstname.' '.$order->lastname }}</label></th>
                                    </tr>
                                    <tr>
                                        <th> <label class="form-label" for="vertical-username">Email</label> </th>
                                        <th>  <label class="form-label" for="vertical-email">{{ $order->email }}</label></th>
                                    </tr>
                                    <tr>
                                        <th>  <label class="form-label" for="vertical-username">Mobile Number</label> </th>
                                        <th> <label class="form-label" for="vertical-email">{{ $order->mobile }}</label></th>
                                    </tr>
                                    <tr>
                                        <th>  <label class="form-label" for="vertical-username">Order Date</label> </th>
                                        <th> <label class="form-label" for="vertical-email">{{ date('d-m-Y',strtotime($order->created_at)) }}</label></th>
                                    </tr>
                                    @if(!empty($order->latitude) && !empty($order->longitude))
                                    <tr>
                                        <th>  <label class="form-label" for="vertical-username">Location</label> </th>
                                       <th>
                                            <a href="https://www.google.com/maps?q={{ $order->latitude }},{{ $order->longitude }}" target="_blank" class="form-label">
                                                View on Google Maps
                                            </a>
                                        </th>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th> <label class="form-label" for="vertical-username">Download</label> </th>
                                        <th> <a href="{{ asset('storage/'.$order->order_pdf) }}" target='_blank' class="cb"><i class='fa fa-download'></i>&nbsp;Download</a></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="payment-details-vertical" class="content" role="tabpanel" aria-labelledby="payment-details-vertical-trigger">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th> <label class="form-label" for="vertical-username">Customer Name</label></th>
                                        <th> <label class="form-label" for="vertical-email">{{ $order->billing_firstname.' '.$order->billing_lastname }}</label></th>
                                    </tr>
                                    <tr>
                                        <th> <label class="form-label" for="vertical-username">Email</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->billing_email }}</label></th>
                                    </tr>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Mobile Number</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->billing_mobile }}</label></th>
                                    </tr>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Billing Address</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->billingaddress->flat }}, {{ $order->billingaddress->area }}, {{ $order->billingaddress->landmark }}{{ $order->billing_city_name }}, {{ $order->billing_state_name }}, {{ $order->billing_country_name }} - {{ $order->billing_pincode }}</label></th>
                                    </tr>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Payment Method</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->payment_method }}</label></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="shipping-details-vertical" class="content" role="tabpanel" aria-labelledby="shipping-details-vertical-trigger">
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Customer Name</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->shipping_firstname.' '.$order->shipping_lastname }}</label></th>
                                    </tr>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Email</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->shipping_email }}</label></th>
                                    </tr>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Mobile Number</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->shipping_mobile }}</label></th>
                                    </tr>
                                    <tr>
                                        <th><label class="form-label" for="vertical-username">Shipping Address</label></th>
                                        <th><label class="form-label" for="vertical-email">{{ $order->shippingaddress->flat }}, {{ $order->shippingaddress->area }}, {{ $order->shippingaddress->landmark }}{{ $order->shipping_city_name }}, {{ $order->shipping_state_name }}, {{ $order->shipping_country_name }} - {{ $order->shipping_pincode }}</label></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="personal-info-vertical" class="content overflow-auto" role="tabpanel" aria-labelledby="personal-info-vertical-trigger">
                            <table class="table table-bordered full_p-deta">
                                <thead>
                                    <tr>
                                        <th style="text-align:center">Sr. No</th>
                                        <th style="text-align:center">Product Image</th>
                                        <th style="text-align:center">Product Name</th>
                                        <th style="text-align:center">Qty</th>
                                        @if($order->shop_id != null)
                                            <th style="text-align:center">MRP</th>
                                            <th style="text-align:center">Selling Price</th>
                                            @if(getSettingData("company_state") == $order->billing_state_name)
                                                <th style="text-align:center">CGST %</th>
                                                <th style="text-align:center">SGST %</th>
                                            @else
                                                <th style="text-align:center">IGST %</th>
                                            @endif
                                            <th style="text-align:center">Item Total</th>
                                        @endif
                                    </tr>
                                    @php
                                        $sr = 1;
                                        $total_qty = 0;
                                        $total_item = 0;
                                        $net_sub_total = 0;
                                        $gst_per = 0;
                                        $total_gst = 0;
                                    @endphp
                                    @foreach($order->orderproduct as $key => $opvalue)
                                        <tr class="align-middle">
                                            <td align="center">{{ $sr }}</td>
                                            <td align="center"><img src="{{ ($opvalue->product_image && file_exists('storage/'.$opvalue->product_image)) ? asset('storage/'.$opvalue->product_image) : asset('frontend/images/product-default.png') }}" style="height: 100px;width: 100px;"></td>
                                            <td align="center">{{ $opvalue->product_name }}</td>
                                            <td align="center">{{ $opvalue->product_quantity }}</td>
                                            @if($order->shop_id != null)
                                                <td align="center">{{config('constants.currency_symbol')}}&nbsp;{{ $opvalue->product_mrp }}</td>
                                                <td align="center">{{config('constants.currency_symbol')}}&nbsp;{{ $opvalue->product_selling_price }}</td>
                                                @if(getSettingData("company_state") == $order->billing_state_name)
                                                    <td align="center">{{config('constants.currency_symbol')}}&nbsp;{{ number_format($opvalue->gst_per / 2,2) }}</td>
                                                    <td align="center">{{config('constants.currency_symbol')}}&nbsp;{{ number_format($opvalue->gst_per / 2,2) }}</td>
                                                @else
                                                    <td align="center">{{config('constants.currency_symbol')}}&nbsp;{{ number_format($opvalue->gst_per,2) }}</td>
                                                @endif
                                                <td align="center">{{config('constants.currency_symbol')}}&nbsp;{{ $opvalue->total_amount }}</td>
                                            @endif
                                        </tr>
                                        @php
                                            $net_sub_total += $opvalue->amount_without_gst;
                                            $total_qty += $opvalue->product_quantity;
                                            $total_item += $opvalue->total_amount;
                                            $gst_per = $opvalue->gst_per;
                                            $total_gst += $opvalue->total_gst_val;
                                            $sr++;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td colspan="3" align="right"><b>Total Quantity</b></td>
                                        <td align="center">{{ $total_qty }}</td>
                                        @if($order->shop_id != null)
                                            <td colspan="5"></td>
                                        @endif
                                    </tr>
                                </thead>
                                @if($order->shop_id != null)
                                    <tfoot>
                                        <tr>
                                            <td colspan="@if(getSettingData('company_state') == $order->billing_state_name) 7 @else 6 @endif"></td>
                                            <td align="right" style="color:#000066;"><b>Sub Total (without GST)</b></td>
                                            <td align="center" style="color:#000066;"><b>{{config('constants.currency_symbol')}}&nbsp;{{ number_format($net_sub_total,2) }}</b></td>
                                        </tr>
                                        @if($order->coupon_id)
                                            @php
                                                $subtotal_af_discount = $net_sub_total - $order->coupon_discount;
                                            @endphp
                                            <tr>
                                                <td colspan="6"></td>
                                                <td align="right" style="color:#000066;"><b>Coupon ({{$order->coupon_code}})</b></td>
                                                <td align="center"  style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($order->coupon_discount,2)}}</b></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td align="right" style="color:#000066;"><b>Sub Total (after Discount)</b></td>
                                                <td align="center" style="color:#000066;"><b>{{ config('constants.currency_symbol') }} {{ number_format($subtotal_af_discount,2) }}</b></td>
                                            </tr>
                                            @php
                                                $gst_data = gstCalculation($subtotal_af_discount,$gst_per,1);
                                                $total_gst = $gst_data['igst'];
                                                $grand_total = ($net_sub_total - $order->coupon_discount) + $total_gst;
                                            @endphp
                                            @if(getSettingData("company_state") == $order->billing_state_name)
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td align="right"  style="color:#000066;"><b>CGST ({{ $gst_per / 2 }}%)</b></td>
                                                    <td align="center"  style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($total_gst / 2,2)}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td align="right" style="color:#000066;"><b>SGST ({{$gst_per / 2}}%)</b></td>
                                                    <td align="center" style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($total_gst / 2,2)}}</b></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td align="right" style="color:#000066;"><b>IGST ({{$gst_per}}%)</b></td>
                                                    <td align="center" style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($total_gst,2)}}</b></td>
                                                </tr>
                                            @endif
                                        @else
                                            @if(getSettingData("company_state") == $order->billing_state_name)
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td align="right" style="color:#000066;"><b>CGST ({{ $gst_per / 2 }}%)</b></td>
                                                    <td align="center"  style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($total_gst / 2,2)}}</b></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td align="right"  style="color:#000066;"><b>SGST ({{$gst_per / 2}}%)</b></td>
                                                    <td align="center"  style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($total_gst / 2,2)}}</b></td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td align="right"  style="color:#000066;"><b>IGST ({{ $gst_per }}%)</b></td>
                                                    <td align="center"  style="color:#000066;"><b>{{ config('constants.currency_symbol') }} {{ number_format($total_gst, 2) }}</b></td>
                                                </tr>
                                            @endif
                                        @endif
                                        @if($order->shipping_charge)
                                            <tr>
                                                <td colspan="@if(getSettingData('company_state') == $order->billing_state_name) 7 @else 8 @endif"></td>
                                                <td align="right" style="color:#000066;"><b>Shipping</b></td>
                                                <td align="center" style="color:#000066;"><b>{{ config('constants.currency_symbol') }} {{ number_format($order->shipping_charge,2) }}</b></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="@if(getSettingData('company_state') == $order->billing_state_name) 7 @else 6 @endif">Total Invoice Value (In words) : {{amt_to_words($order->grand_total)}}</td>
                                            <td align="right" style="color:#000066;"><b>Grand Total</b></td>
                                            <td align="center" style="color:#000066;"><b>{{config('constants.currency_symbol')}} {{number_format($order->grand_total,2)}}</b></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
                        </div>

                        <div id="history-details-vertical" class="content" role="tabpanel" aria-labelledby="history-details-vertical-trigger">
                            <div class="card mb-2">
                                <div class="card-body p-1">
                                    <table class="table table-bordered table-hover mb-3">
                                        <tr>
                                            <th>Order Status</th>
                                            <th>Date Added</th>
                                            <th>Comment</th>
                                        </tr>
                                        @foreach($order->orderhistory as $ohkey => $ohvalue)
                                            <tr>
                                                <td>{{ $ohvalue->orderstatus->order_status_name }}</td>
                                                <td>{{ $ohvalue->created_at }}</td>
                                                <td>{{ $ohvalue->comment }}</td>
                                            </tr>
                                        @endforeach
                                    </table>


                                    <form method="POST" action="{{route('orderhistory.store')}}" class="form-first FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id ?? '' }}">
    
                                        <div class="row">
                                            <div class="col-xl-4 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <x-input-label for="description" class="form-label" :value="__('Comment')" /><span class="error">*</span>
                                                    <x-textarea id="comment" class="form-control" name="comment">{{old('description')}}</x-textarea>
                                                </div>
                                            </div>
                                            <div class="col-xl-4 col-md-6 col-12 ">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Order Status <span class="error">*</span></label>


                                                    <select class="form-select" aria-label="Default select example" name="orderstatus_id" id="orderstatus_id">
                                                        <option value="">Select Order Status</option>    
                                                        @if($orderStatus)
                                                            @foreach($orderStatus as $statusId => $statusName)
                                                                <option value="{{$statusId}}" {{ old('parent_category_id') == $statusId ? 'selected' : ''}} >{{$statusName}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('order.index') }}" class="btn btn-outline-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /Vertical Wizard -->
        </div>
    </div>
@endsection
@push('script')
    <script type="text/javascript">
        $('.FormValidate').validate({
            rules: {
                "orderstatus_id": {
                    required : true,
                },
                "comment": {
                    required : true,
                },
            },
            messages: {
                "orderstatus_id": {
                    required: "Please Select Order Status",
                },
                "comment": {
                    required: "Please Enter comment",
                },
            }
        });
    </script>
@endpush