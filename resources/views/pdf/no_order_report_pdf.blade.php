<table style="border-collapse: collapse; margin: auto;  width: 100%">
    <thead>
        <tr><td colspan="2" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
        <tr style="border: 1px solid #{{ $color }}; background-color:#{{ $color }}; color: #FFFFFF;">
            <td colspan="2">
                <p style="text-align: center;font-size: 14px;margin: 0;"><b>UNPRODUCTIVE CALL REPORT</b></p>
            </td>
        </tr>
        <tr><td colspan="2" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
    <thead>
</table>

<table style="border-collapse: collapse; margin: auto; width: 100%" border="1">
    
    {{-- <tr><td colspan="5" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr> --}}
    @foreach($noOrders as $date => $orders)
        @php
            $userOrders = $orders->groupBy(function ($order) {
                return $order->distributor_id;
            });

            $keyI = 0;
        @endphp
        <tr style="border: 1px solid #aa0025; background-color:#aa0025; color: #FFFFFF;">
            <td colspan="2">
                <p style="font-size: 14px;margin: 0;"><b>Date:</b> {{ $date }}</p>
            </td>
        </tr>
        <tr><td colspan="2" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
        
        @foreach($userOrders as $keyu => $distributorOrders)
            @php
                $decimalPoint = 2;
                $fullColspan = 4;
                $distributor = $distributorOrders->first()->toArray();

                $noNoOrders = $distributorOrders->groupBy(function ($order) {
                    return $order->shop_id;
                });
            @endphp
            @if(isset($distributor['distributor']))
                <tr style="background-color:#2C679F; color:rgb(255, 255, 255);">
                    <td style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
                        <b>Distributor Name : </b>{{ $distributor['distributor']['firstname'].' ('.$distributor['distributor']['lastname'].')' }}</br>
                        <b>Mobile Number : </b>{{ $distributor['distributor']['mobile'] }}</br>
                    </td>
                    <td style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
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
                <tr><td colspan="2" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
            @endif
            @php
                $sr_no = 1;
            @endphp
            @foreach($noNoOrders as $keyr => $orders)
                @php
                    $keyI++;
                    $shop = $orders->first()->toArray();
                @endphp
                @if(isset($shop['shop']))
                    <tr style="background-color:#ededed; color: #000000;">
                        <td  style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                            <b>({{ $keyI }}) Retailer Name : </b>{{ $shop['shop']['name'] }}</br>
                            <b>Mobile Number : </b> {{ $shop['shop']['mobile'] }}</br>
                            <b>Area : </b> {{ $shop['shop']['area']['name']??'' }}
                        </td>
                        <td style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                            <b>Order Punching Time: </b>{{ \Carbon\Carbon::parse($shop['created_at'])->format('d-m-Y h:i A') }}</br>
                            <b>Location : </b>{{ $shop['shop']['city']['name'].','.$shop['shop']['state']['name'].','.$shop['shop']['country']['name'] }}
                        </td>
                    </tr>
                @endif
                <tr style="background-color:#636363; color: #000000;">
                    {{-- <td style="text-align: center;background: #fff;"><b>Sr No.</b></td> --}}
                    <td colspan="2" style="background: #fff;"><b>Reason</b></td>
                </tr>
                @php
                    $keyCount = 1;
                @endphp
                @foreach($orders as $key => $ovalue)
                    <tr>
                        {{-- <td style="text-align: center;">{{ $keyCount }}.</td> --}}
                        <td colspan="2" style="font-size: 12px;" >{{ $keyCount++ }}. &nbsp;&nbsp;{{ $ovalue->comment }}</td>
                    </tr>
                @endforeach
                
                <tr><td colspan="2" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
            @endforeach
        @endforeach
    @endforeach
</table>