<tr style="background-color:#ededed; color: #000000;">
    <td style="width:5%; text-align: center;"><b>Sr No.</b></td>
    {{-- <td style="text-align: center;"><b>Date</b></td> --}}
    <td style="width:12%;"><b>From</b></td>
    <td style="width:12%;"><b>To</b></td>
    <td><b>Expense Type {{--Mode of Travel--}}</b></td>
    <td style="width:15%;"><b>Expense Name</b></td>
    <td style="width:5%;""><b>Document</b></td>
    <td style="width:5%;""><b>Rate/Km</b></td>
    <td style="width:8%; text-align: right;"><b>Amount</b></td>
    <td style="width:8%; text-align: right;"><b>Daily Allowance</b></td>
    <td style="width:8%; text-align: right;"><b>Total</b></td>
</tr>
@if($tadas)

    @php 
        //\Log::info('#####################################');
        $expenseAmount = 0; //$tadas->sum('amount');
        $dailyAllowance = 0; //$tadas->first()->daily_allowance;
    @endphp
    @foreach($tadas as $key => $value)

    @php
        $dailyAmt = ($loop->index === 0) ? (($value->tadatype->name === 'Daily Allowance') ? $value->amount??0 : $value->daily_allowance??0) : 0;
        $expanceAmt = ($value->tadatype->name !== 'Daily Allowance') ? $value->amount??0: 0;
        $expenseAmount += $expanceAmt;
        $dailyAllowance += $dailyAmt;

        //\Log::info(($value->tadatype->name??null).' =>'.$dailyAmt.'/'.$expanceAmt.'/'.$expenseAmount.'/'.$dailyAllowance);
    @endphp
        <tr>
            <td style="font-size: 12px; text-align: center;">{{ (int)$key+1 }}</td>
            {{-- <td style="font-size: 12px; text-align: center;">{{ \Carbon\Carbon::parse($value->date)->format('d-m-Y') }}</td> --}}
            <td style="font-size: 12px;">{{ $value->from }}</td>
            <td style="font-size: 12px;">{{ $value->to }}</td>
            <td style="font-size: 12px;">{{ $value->tadatype->name }}{{ $value->km > 0 ? ' - '.$value->km.' KM' : ''}}</td>
            <td style="font-size: 12px;">{{ ((strpos($value->tadatype->name, 'Travel') !== false) && $value->expense_name === null) ? '' : $value->expense_name }}</td>
            <td style="font-size: 12px;">@if($value->photo)<a href="{{ asset('storage/'.$value->photo) }}" target="_blank">Document</a>@endif</td>
            <td style="font-size: 12px; text-align:right">{!! $value->km > 0 ? (config('constants.currency_html').' '.$value->per_km_price) : '' !!}</td>
            <td style="font-size: 12px; text-align:right">{!!config('constants.currency_html')!!} {{ number_format($expanceAmt, 2) }}</td>
            <td style="font-size: 12px; text-align:right"> @if($loop->index === 0) {!!config('constants.currency_html')!!} {{ number_format($dailyAmt, 2) }}@endif</td>
            {{-- <td style="font-size: 12px; text-align:right">{!!config('constants.currency_html')!!} {{ ( $value->tadatype->name !== 'Daily Allowance') ? number_format($value->amount??0, 2) : '0.00' }}</td> 
            <td style="font-size: 12px; text-align:right"> @if($loop->index === 0) {!!config('constants.currency_html')!!} {{( $value->tadatype->name === 'Daily Allowance') ? number_format($value->amount??0, 2) : number_format($value->daily_allowance??0, 2 )}}@endif</td>--}}
            <td style="font-size: 12px; text-align:right">{!!config('constants.currency_html')!!} {{ $loop->index === 0 ? number_format((($value->amount??0) + ($value->daily_allowance??0)),2) : number_format(($value->amount??0),2)}}</td>
        </tr>
    @endforeach
@endif
<tr style="background-color:#ededed; color: #000000;">
    <td style="font-size: 12px; text-align: right;" colspan="7"><b>Total</b></td>
    <td style="font-size: 12px; text-align: right;">{!!config('constants.currency_html')!!} {{number_format($expenseAmount,2) }}</td>
    <td style="font-size: 12px; text-align: right;">{!!config('constants.currency_html')!!} {{number_format($dailyAllowance,2)}}</td>
    <td style="font-size: 12px; text-align: right;">{!!config('constants.currency_html')!!} {{number_format(($dailyAllowance + $expenseAmount),2)}}</td>
</tr>
<tr><td colspan="10" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF; border-bottom: 1px solid #ffffff;"></td></tr>