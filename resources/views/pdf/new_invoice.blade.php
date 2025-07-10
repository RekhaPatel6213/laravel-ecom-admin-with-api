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
        <table style="border-collapse: collapse; margin: auto;  width: 100%">
            <thead>
                <tr style="border: 1px solid #e31e29; background-color:#e31e29; color: #FFFFFF;">
                    <td colspan="2" style="border: 1px solid #000;">
                        <p style="text-align: center;font-size: 14px;margin: 0;"><b>{{ $receiptName }}</b></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 50%;  padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Name : </b> {{ $user_data->firstname.' '.$user_data->lastname }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Designation : </b> {{ $user_data->designation->name }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Order Date : </b>{{ \Carbon\Carbon::parse($order_data->created_at)->format('d-m-Y h:i A') }}</p>
                    </td>
                    <td style="width: 50%; padding: 7px;text-align: right;">
                        <!-- <img src="{{ asset('storage/'.getSettingData('company_logo')) }}" style="max-width: 100%;" /> -->
                        <?php
                            $path = asset('storage/'.getSettingData('company_logo')) ;
                            $type = pathinfo($path, PATHINFO_EXTENSION);
                            $data = file_get_contents($path);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                        ?>
                        <img src="{{ $base64 }}" alt="Image" style="width: 100px;">
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table style="border-collapse: collapse; margin: auto; width: 100%" border="1">
            <thead>
                <tr style="background-color:#2C679F;color:#FFFFFF">
                    <td style="width: 50%;  padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 14px; padding-bottom: 5px;"><b>Distributor Name : </b>{{ $order_data->distributor->firstname.' ('.$order_data->distributor->lastname.')' }}</p>
                        <p style="margin: 0; font-size: 14px; padding-bottom: 5px;"><b>Mobile Number : </b> {{ $order_data->distributor->mobile }}</p>
                        <p style="margin: 0; font-size: 14px; padding-bottom: 5px;"><b>Order Purchasing Time: </b>{{ \Carbon\Carbon::parse($order_data->created_at)->format('d-m-Y h:i A') }}</p>
                    </td>
                    <td style="width: 50%;  padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 14px; padding-bottom: 5px"><b>Address : </b>{{ $order_data->distributor['city']['name'].','.$order_data->distributor['state']['name'].','.$order_data->distributor['country']['name'] }}</p>
                        <p style="margin: 0; font-size: 14px; padding-bottom: 5px"><b>Zone : </b>{{ $order_data->distributor['zone']['name'] }}</p>
                        @if($order_data->latitude !== null && $order_data->longitude !== null && $order_data->shop_id !== null)
                            @php 
                                $mapLink = 'https://www.google.com/maps?q='.$order_data->latitude.','.$order_data->longitude;
                            @endphp
                            <p style="margin: 0; font-size: 14px; padding-bottom: 5px"><b>Location : </b> {!! $mapLink !== null ? '<a href="'.$mapLink.'" target="_blank" style="color:rgb(255, 255, 255);">Link</a>' : ''!!}</p>
                        @endif
                    </td>
                </tr>

                @if(isset($order_data->shop))
                    <tr style="background-color:#ededed; color: #000000;">
                        <td style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                            <b>Retailer Name : </b>{{ $order_data->shop['name'] }}</br>
                            <b>Mobile Number : </b> {{ $order_data->shop['mobile'] }}</br>
                            <b>Area : </b> {{ $order_data->shop['area']['name']??'' }}
                        </td>
                        <td style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                            <b>Order Punching Time: </b>{{ \Carbon\Carbon::parse($order_data->shop['created_at'])->format('d-m-Y h:i A') }}</br>
                            <b>Location : </b>{{ $order_data->shop['city']['name'].','.$order_data->shop['state']['name'].','.$order_data->shop['country']['name'] }}
                        </td>
                    </tr>
                @endif
            </thead>
        </table>
        @if($order_data->shop_id !== null)
            <table style="border-collapse: collapse; margin: auto; width: 100%;" border="1">
                @php
                    $decimalPoint = 2;
                    $net_sub_total = $product_qty = $total_gst = 0; 
                @endphp
                <thead>
                    <tr style="background-color:#636363; color: #000000;">
                        <td style="width:8%; text-align: center;background: #fff;"><b>Sr No.</b></td>
                        <td style="width:42%; background: #fff;" ><b>Product Name </b></td>
                        <td style="width:12%; text-align: center;background: #fff;"><b>Qty</b></td>
                        <td style="text-align: right;background: #fff;"><b>Unit Price</b></td>
                        <td style="width:12%;text-align: right;background: #fff;"><b>Total</b></td>
                    </tr>
                </thead>
                <tbody>
                    @if($order_data->orderproduct)
                        @foreach($order_data->orderproduct as $key => $product)
                        <tr>
                            <td style="text-align: center;">{{ $key+1 }}.</td>
                            <td style="font-size: 12px;" >{{ $product->product_name }}</td>
                            <td style="font-size: 12px;text-align: center;">{{ $product->product_quantity }}</td>
                            <td style="text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($product->with_out_gst_price,$decimalPoint)}} </td>
                            <td style="text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($product->amount_without_gst,$decimalPoint)}}</td>
                        </tr>
                        @php
                            $net_sub_total += $product->amount_without_gst;
                            $product_qty += $product->product_quantity; 
                            $total_gst += $product->total_gst_val;
                        @endphp
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: right;" colspan="2" ><b>Total</b> </td>
                        <td style="text-align: center;">{{ $product_qty }}</td>
                        <td style="text-align: right;"></td>
                        <td style="text-align: right;"><b> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format(($net_sub_total+$total_gst),$decimalPoint) }}</b></td>
                    </tr>
                </tfoot>
            </table>
        @else
            <table style="border-collapse: collapse; margin: auto; width: 100%;" border="1">
                @php
                    $decimalPoint = 2;
                    $product_qty = 0; 
                @endphp
                <thead>
                    <tr>
                        <td style="width:8%; text-align: center;background: #fff;"><b>Sr No.</b></td>
                        <td style="width:42%; background: #fff;"><b>Product Name </b></td>
                        <td style="width:50%; text-align: center;background: #fff;"><b>Qty</b></td>
                    </tr>
                </thead>
                <tbody>
                    @if($order_data->orderproduct)
                        @foreach($order_data->orderproduct as $key => $product)
                            <tr>
                                <td style="text-align: center;">{{ $key+1 }}.</td>
                                <td style="font-size: 12px;">{{ $product->product->name }}</td>
                                <td style="font-size: 12px;text-align: center;">{{ $product->product_quantity }} Case</td>
                            </tr>
                            @php 
                                $product_qty += $product->product_quantity;
                            @endphp
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: right;" colspan="2" ><b>Total</b> </td>
                        <td style="text-align: center;"><b>{{ $product_qty }} Case</b></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </body>
</html>