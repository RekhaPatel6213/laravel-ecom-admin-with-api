@if($orderTytpe === 'R')
    @foreach($userOrders as $keyu => $distributorOrders)
        @php
            $decimalPoint = 2;
            $fullColspan = 4;
            $distributor = $distributorOrders->first()->toArray();

            $retailerOrders = $distributorOrders->groupBy(function ($order) {
                return $order->shop_id;
            });
        @endphp
        @if(isset($distributor['distributor']))
            <tr style="background-color:#2C679F; color:rgb(255, 255, 255);">
                <td colspan="2" style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
                    <b>Distributor Name : </b>{{ $distributor['distributor']['firstname'].' ('.$distributor['distributor']['lastname'].')' }}</br>
                    <b>Mobile Number : </b>{{ $distributor['distributor']['mobile'] }}</br>
                </td>
                <td colspan="3" style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
                    <b>Address : </b>{{ $distributor['distributor']['city']['name'].','.$distributor['distributor']['state']['name'].','.$distributor['distributor']['country']['name'] }}</br>
                    <b>Zone : </b>{{ $distributor['distributor']['zone']['name'] }}<br/>
                    @if($distributor['latitude'] !== null && $distributor['longitude'] !== null)
                        @php 
                            $mapLink = 'https://www.google.com/maps?q='.$distributor['latitude'].','.$distributor['longitude'];
                        @endphp
                        <b>Location : </b> {!! $mapLink !== null ? '<a href="'.$mapLink.'" target="_blank" style="color:rgb(255, 255, 255);">Link</a>' : ''!!}
                    @endif
                </td>
            </tr>
            <tr><td colspan="5" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
        @endif
        @php
            $sr_no = 1;
        @endphp
        @foreach($retailerOrders as $keyr => $orders)
            @php
                $shop = $orders->first()->toArray();
            @endphp
            @if(isset($shop['shop']))
                <tr style="background-color:#ededed; color: #000000;">
                    <td colspan="2" style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                        <b>({{ $sr_no++ }}) Retailer Name : </b>{{ $shop['shop']['name'] }}</br>
                        <b>Mobile Number : </b> {{ $shop['shop']['mobile'] }}</br>
                        <b>Area : </b> {{ $shop['shop']['area']['name']??'' }}
                    </td>
                    <td colspan="3" style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                        <b>Order Punching Time: </b>{{ \Carbon\Carbon::parse($shop['created_at'])->format('d-m-Y h:i A') }}</br>
                        <b>Location : </b>{{ $shop['shop']['city']['name'].','.$shop['shop']['state']['name'].','.$shop['shop']['country']['name'] }}
                    </td>
                </tr>
            @endif
            <tr style="background-color:#636363; color: #000000;">
                <td style="width:8%; text-align: center;background: #fff;"><b>Sr No.</b></td>
                <td style="width:42%; background: #fff;" ><b>Product Name </b></td>
                <td style="width:12%; text-align: center;background: #fff;"><b>Qty</b></td>
                <td style="text-align: right;background: #fff;"><b>Unit Price</b></td>
                <td style="width:12%;text-align: right;background: #fff;"><b>Total</b></td>
            </tr>
            @php
                $net_sub_total = $product_qty = $gst_per = $total_gst = 0; $keyCount = 1;
            @endphp
            @foreach($orders as $key => $ovalue)
                @if($ovalue['orderProduct'])
                    @foreach($ovalue['orderproduct'] as $keyo => $product)
                        <tr>
                            <td style="text-align: center;">{{ $keyCount }}.</td>
                            <td style="font-size: 12px;" >{{ $product->product_name }}</td>
                            <td style="font-size: 12px;text-align: center;">{{ $product->product_quantity }}</td>
                            <td style="text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($product->with_out_gst_price,$decimalPoint)}} </td>
                            <td style="text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($product->amount_without_gst,$decimalPoint)}}</td>
                        </tr>
                        @php
                            $keyCount++;
                            $net_sub_total += $product->amount_without_gst;
                            $product_qty += $product->product_quantity;
                            $gst_per = $product->gst_per;
                            $total_gst += $product->total_gst_val;
                        @endphp
                    @endforeach
                @endif
            @endforeach
            {{-- <tr>
                <td style="text-align: right;" colspan="{{$fullColspan}}">Sub Total (without GST) </td>
                <td style="text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($net_sub_total,$decimalPoint)}} </td>
            </tr>
            <tr>
                <td style="text-align: right;" colspan="{{$fullColspan}}">IGST ({{ $gst_per }}%)</td>
                <td style="text-align: right;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($total_gst,$decimalPoint)}} </td>
            </tr> --}}
            <tr>
                <td style="text-align: right;" colspan="2" ><b>Total</b> </td>
                <td style="text-align: center;">{{ $product_qty }}</td>
                <td style="text-align: right;"></td>
                <td style="text-align: right;"><b> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format(($net_sub_total+$total_gst),$decimalPoint) }}</b></td>
            </tr>
            @if(!$loop->parent->last)
            <tr><td colspan="5" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
            @endif
        @endforeach
    @endforeach
@else
    @foreach($userOrders as $keyu => $orders)
    
    @php 
        $orderFirst = $orders->first()->toArray();
    @endphp
        <tr style="background-color:#2C679F; color:rgb(255, 255, 255);">
            <td colspan="2" style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
                <b>Distributor Name : </b>{{ $orderFirst['distributor']['firstname'].' ('.$orderFirst['distributor']['lastname'].')' }}</br>
                <b>Mobile Number : </b>{{ $orderFirst['distributor']['mobile'] }}</br>
            </td>
            <td colspan="1" style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
                <b>Address : </b>{{ $orderFirst['distributor']['city']['name'].','.$orderFirst['distributor']['state']['name'].','.$orderFirst['distributor']['country']['name'] }}</br>
                <b>Zone : </b>{{ $orderFirst['distributor']['zone']['name'] }}<br/>
            </td>
        </tr>
        <tr>
            <td style="width:8%; text-align: center;background: #fff;"><b>Sr No.</b></td>
            <td style="width:42%; background: #fff;"><b>Product Name </b></td>
            <td style="width:50%; text-align: center;background: #fff;"><b>Qty</b></td>
        </tr>
        @php $product_qty = 0; $keyCount = 1; @endphp
        @foreach($orders as $keyo => $ovalue)
            @if($ovalue['orderproduct'])
                @foreach($ovalue['orderproduct'] as $key => $product)
                    <tr>
                        <td style="text-align: center;">{{ $keyCount }}.</td>
                        <td style="font-size: 12px;">{{ $product->product->name }}</td>
                        <td style="font-size: 12px;text-align: center;">{{ $product->product_quantity }} Case</td>
                    </tr>
                    @php 
                        $keyCount++;
                        $product_qty += $product->product_quantity;
                    @endphp
                @endforeach
            @endif
        @endforeach
        <tr>
            <td style="text-align: right;" colspan="2" ><b>Total</b> </td>
            <td style="text-align: center;"><b>{{ $product_qty }} Case</b></td>
        </tr>
        
        @if(!$loop->parent->last)
            <tr><td colspan="3" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
        @endif
    @endforeach
@endif