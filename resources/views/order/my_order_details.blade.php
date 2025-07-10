@extends('layouts.admin')

@section('content')

    

    <!----------------- Order History Section Start ----------------->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="flash_messages">
                    @include('elements.flash_messages')
                </div>
                <div class="col-sm-12">
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-5 col-md-3">
                            <h2 class="mt-0">Order Details</h2>
                        </div>
                        <div class="col-sm-7 col-md-9">
                            <div class="text-end custom_padding download-recept-wrap-btn">
                                @if($order_data->orderstatus_id <= 2)
                                    <!-- <a href="{{--route('cancel_order', $order_data->order_no)--}}" class="btn custom-btn-dark" >Cancel Order</a> -->
                                @endif

                                @if( $order_data->order_pdf != null && file_exists('storage/'.$order_data->order_pdf))
                                    <a href="{{asset('storage/'.$order_data->order_pdf)}}" data-toggle="tooltip" title="Download" class="btn custom-btn" download="{{$order_data->invoice_no}}" data-original-title="" data-bs-original-title=""><span><i class="fa fa-download"></i></span> Download Receipt</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td class="text-start" colspan="2"><b>Order Details</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start" style="width: 50%;">
                                        <b>Order No:</b> #{{$order_data->order_no}}<br>
                                        <b>Date Added:</b> {{date('d/m/Y',strtotime($order_data->created_at))}}
                                    </td>
                                    <td class=" text-start">
                                        @if($order_data->payment_method == 'COD')
                                            <b>Payment Type:</b> Cash on Delivery<br>
                                        @else
                                            <b>Payment Method:</b> Online<br>
                                            <b>Payment Type:</b> {{$order_data->payment_code??'Card'}}<br>
                                        @endif
                                        <b>Order Status:</b> {{$order_data->orderstatus->order_status_name}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class=" table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td class="text-start" style="vertical-align: top; background: #FBFBFB; border: 1px solid #dee2e6;">
                                        <b>Billing Address</b>
                                    </td>
                                    <td class="text-start" style="width: 50%; vertical-align: top; background: #FBFBFB; border: 1px solid #dee2e6;">
                                        <b>Shipping Address</b>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start" style="border: 1px solid #dee2e6;">
                                        {{$order_data->billing_firstname}} {{$order_data->billing_lastname}}<br>
                                        {{$order_data->billingaddress->flat}}, {{$order_data->billingaddress->area}}, {{$order_data->billingaddress->landmark}}<br>{{$order_data->billing_city_name}}, {{$order_data->billing_state_name}}, {{$order_data->billing_country_name}} - {{$order_data->billing_pincode}}<br>
                                        Email:- {{$order_data->billing_email}}<br>
                                        Mobile:- +91 {{$order_data->billing_mobile}}
                                    </td>
                                    <td class="text-start" style="border: 1px solid #dee2e6;">
                                        {{$order_data->shipping_firstname}} {{$order_data->shipping_lastname}}<br>
                                        {{$order_data->shippingaddress->flat}}, {{$order_data->shippingaddress->area}}, {{$order_data->shippingaddress->landmark}}<br>{{$order_data->shipping_city_name}}, {{$order_data->shipping_state_name}}, {{$order_data->shipping_country_name}} - {{$order_data->shipping_pincode}}<br>
                                        Email:- {{$order_data->shipping_email}}<br>
                                        Mobile:- +91 {{$order_data->shipping_mobile}}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered invoice-table-content">
                            @include('order.order_detail_layout',['orderData' => $order_data, 'background' =>'#FBFBFB'])
                        </table>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h3 class="mb-3">Order History</h3>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="text-start">Date Added</td>
                                <td class="text-start">Status</td>
                                <td class="text-start">Comment</td>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($order_data->orderhistory))
                                @foreach($order_data->orderhistory as $key => $value)
                                    <tr>
                                        <td class="text-start">{{date('d/m/Y h:i A',strtotime($value->created_at))}}</td>
                                        <td class="text-start">{{$value->orderstatus->order_status_name}}</td>
                                        <td class="text-start">{{ $value->comment }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <!-- <div class="button-group clearfix">
                            <div class="float-right"><a href="" class="btn btn-green">Continue Shopping</a></div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>
    <!----------------- Order History Section End ----------------->
@endsection