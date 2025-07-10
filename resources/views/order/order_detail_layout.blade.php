@php
    $igst = (getSettingData("company_state") === $orderData->billing_state_name) ? true : false;
    $fullColspan = 5; //($igst === true) ? 6 : 5;

    $net_sub_total = 0;
    $grand_total = 0;
    $gst_per = 0;
    $total_gst = 0;
    $decimalPoint = 2;
@endphp
<thead>
    <tr>
        <td style="width:5%; text-align: center;background: {{$background}};"><b>Sr No.</b></td>
        <td style="background: {{$background}};"><b>Product Name </b></td>
        <td style="background: {{$background}};"><b>Product Code</b></td>
        <td style="text-align: center;background: {{$background}};"><b>Qty</b></td>
        <td style="text-align: end;background: {{$background}};"><b>Unit Price(Exc. GST)</b></td>
        <?php /*@if($igst === true)
            <td style="text-align: end;background: {{$background}};"><b>CGST %</b></td>
            <td style="text-align: end;background: {{$background}};"><b>SGST %</b></td>
        @else
            <td style="text-align: end;background: {{$background}};"><b>IGST %</b></td>
        @endif*/ ?>
        <td style="text-align: end;background: {{$background}};"><b>Total</b></td>
    </tr>
</thead>
<tbody>
    @if($orderData->orderProduct)
        @foreach($orderData->orderProduct as $key => $product)
            <tr>
                <td style="text-align: center;">{{ $key+1 }}.</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->product_code }}</td>
                <td style="text-align: center;">{{ $product->product_quantity }}</td>                
                <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($product->with_out_gst_price,$decimalPoint)}} </td>
                <?php /*@if($igst === true)
                    <td style="text-align: end;">{{ $product->gst_per / 2 }}</td>
                    <td style="text-align: end;">{{ $product->gst_per / 2 }}</td>
                @else
                    <td style="text-align: end;">{{ $product->gst_per }}</td>
                @endif*/ ?>
                <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($product->amount_without_gst,$decimalPoint)}}</td>
            </tr>
            @php
                $net_sub_total += $product->amount_without_gst;
                $grand_total += $product->amount;
                $gst_per = $product->gst_per;
                $total_gst += $product->total_gst_val;
            @endphp
        @endforeach
    @endif
</tbody>
<tfoot>
    <tr>
        <td style="text-align: right;" colspan="{{$fullColspan}}">Sub Total (without GST) </td>
        <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($net_sub_total,$decimalPoint)}} </td>
    </tr>
    @if($orderData->coupon_id)
        @php
            $subtotal_af_discount = $net_sub_total - $orderData->coupon_discount;
        @endphp
        <tr>
            <td style="text-align: right;" colspan="{{$fullColspan}}">Coupon ({{$orderData->coupon_code}}) </td>
            <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($orderData->coupon_discount,$decimalPoint)}} </td>
        </tr>
        <tr>
            <td style="text-align: right;" colspan="{{$fullColspan}}">Sub Total (after Discount) </td>
            <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($subtotal_af_discount,$decimalPoint)}} </td>
        </tr>
        @php
            $gst_data = gstCalculation($subtotal_af_discount,$gst_per,1);
            $total_gst = $gst_data['igst'];
            $grand_total = ($net_sub_total - $orderData->coupon_discount) + $total_gst;
        @endphp
    @endif
    @if(getSettingData("company_state") == $orderData->billing_state_name)
        <tr>
            <td style="text-align: right;" colspan="{{$fullColspan}}">CGST ({{ $gst_per / 2 }}%) </td>
            <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($total_gst/2,$decimalPoint)}} </td>
        </tr>
        <tr>
            <td style="text-align: right;" colspan="{{$fullColspan}}">SGST ({{ $gst_per / 2 }}%) </td>
            <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($total_gst/2,$decimalPoint)}} </td>
        </tr>
    @else
        <tr>
            <td style="text-align: right;" colspan="{{$fullColspan}}">IGST ({{ $gst_per }}%) </td>
            <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($total_gst,$decimalPoint)}} </td>
        </tr>
    @endif
    @if($orderData->shipping_charge)
        <tr>
            <td style="text-align: right;" colspan="{{$fullColspan}}">Shipping Charge </td>
            <td style="text-align: end;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format($orderData->shipping_charge,$decimalPoint) }} </td>
        </tr>
    @endif
    <tr>
        <td style="text-align: right;" colspan="{{$fullColspan}}" ><b>Total</b> </td>
        <td style="text-align: end;"><b> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format($orderData->grand_total,$decimalPoint) }}</b></td>
    </tr>
    <tr>
        <td colspan="{{$fullColspan}}"><b>Total Invoice Value (In words)</b> : {{ amt_to_words($orderData->grand_total) }}</td>
        <td></td>
    </tr>
</tfoot>