<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{{ getSettingData('company_name') }}</title>
    </head>
    <style>
        tr, td {
            font-size: 14px;
            padding: 5px;
        }

        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        .footer {
            bottom: 0px;
        }
    </style>

    <body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
        <table style="border-collapse: collapse; margin: auto; width: 100%;">
            <thead>
                <tr>
                    <td colspan="2">
                        <p style="text-align: center;font-size: 20px;margin: 0;padding-bottom: 10px; border-bottom: 1px solid #82001e;"><b>ORDER RECEIPT</b></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 30%;  padding: 15px 7px; text-align: left; padding: 7px;">
                        <!-- <a href="{{-- route('home') --}}"><img src="{{ asset('storage/'.getSettingData('company_logo')) }}" style="max-width: 100%;" /></a> -->
                    </td>
                    <td style="font-size: 12px; width: 70%;  text-align: end; padding: 7px;">
                        <p style="margin: 0;font-size: 18px; padding-top: 5px;text-align: right;"><b>{{ getSettingData('company_name') }}</b></p>
                        <p style="margin: 0;font-size: 14px;text-align: right;">
                        <b>Address : </b>{{ getSettingData('company_address') }}</p>
                        @if(!empty(getSettingData('customer_care_email')))<p style="margin: 0;font-size: 14px;text-align: right;"><b>Customer Care Email : </b>{{ getSettingData('customer_care_email') }}</p>@endif
                        @if(!empty(getSettingData('customer_care_mob_no')))<p style="margin: 0;font-size: 14px;text-align: right;"><b>Customer Care No : </b> {{ getSettingData('customer_care_mob_no') }} </p>@endif
                        @if(!empty(getSettingData('company_gst_no')))<p style="margin: 0;font-size: 14px; padding-bottom: 5px;text-align: right;"><b>GSTIN : </b>{{ getSettingData('company_gst_no') }}</p>@endif
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; margin: auto;  width: 100%">
            <thead>
                <tr style="border: 1px solid #000;">
                    <td colspan="2">
                        <p style="font-size: 14px;margin: 0;"><b>Order Details</b></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 50%;  padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Order Date : </b>{{ date('d/m/Y', strtotime($order_data->created_at)) }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Order No : </b> {{ $order_data->order_no }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Receipt No : </b> {{ $order_data->invoice_no }}</p>
                    </td>
                    <td style="width: 50%;    padding: 7px;">
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>No of Items : </b>{{ $order_data->total_quantity }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Payment Method  : </b> @if($order_data->payment_method == 'COD') Cash On Delivery @else Online @endif</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;">@if($order_data->payment_method != 'COD')<b>Payment Type : </b> {{$order_data->online_order_response??'Card'}} @endif</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse;margin: 0 auto; width: 100% " cellpadding="0" cellspacing="0">
            <thead>
                <tr style=" border: 1px solid #000;">
                    <td style="border-right: 1px solid #000;">
                        <p style="font-size: 14px;margin: 0;"><b>Billing Address</b></p>
                    </td>
                    <td style="border-right: 1px solid #000;">
                        <p style="font-size: 14px;margin: 0;"><b>Shipping Address</b></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Name : </b> {{ $order_data->billing_firstname.' '.$order_data->billing_lastname }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Address : </b> {{ $billingaddress->flat.', '.$billingaddress->area.', '.$billingaddress->landmark.', '.$order_data->billing_city_name.', '.$order_data->billing_state_name.', '.$order_data->billing_country_name.', '.$order_data->billing_pincode }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Email : </b> {{ $order_data->billing_email }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Mobile :</b> {{ $order_data->billing_mobile }}</p>
                    </td>
                    <td style="padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Name : </b> {{ $order_data->shipping_firstname.' '.$order_data->shipping_lastname }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Address : </b> {{ $shippingaddress->flat.', '.$shippingaddress->area.', '.$shippingaddress->landmark.', '.$order_data->shipping_city_name.', '.$order_data->shipping_state_name.', '.$order_data->shipping_country_name.', '.$order_data->shipping_pincode }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Email : </b> {{ $order_data->shipping_email }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Mobile :</b> {{ $order_data->shipping_mobile }}</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <table style="border-collapse: collapse; margin: auto; width: 100% " border="1">
            <thead>
                <tr>
                    @if($order_type == 'O')
                        <td colspan="@if(getSettingData('company_state') == $order_data->billing_state_name) 4 @else 5 @endif">
                    @else 
                        <td colspan="@if(getSettingData('company_state') == $order_data->billing_state_name) 7 @else 6 @endif">
                    @endif
                        <p style="text-align: center;font-size: 14px;margin: 0;"><b>Order Summary</b></p>
                    </td>
                </tr>
            </thead>
            @include('order.order_detail_layout',['orderData' => $order_data, 'background' =>'#fff', 'order_type' => $order_type])
        </table>

        <div class="footer">
            <small>This is Computer Generated Order receipt.</small>
        </div>
    </body>
</html>