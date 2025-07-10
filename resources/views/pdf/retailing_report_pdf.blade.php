<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>{{ str_replace('.pdf','',$pdfName) }} | {{ getSettingData('company_name') }}</title>
</head>
<style>
    tr,
    td {
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
                <td colspan="3">
                    <p style="text-align: center;font-size: 14px;margin: 0;"><b>RETAILING REPORT</b></p>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 33.33%;  padding: 15px 7px; text-align: left; padding: 7px;">
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Name : </b> {{ $user_data->firstname.' '.$user_data->lastname }}</p>
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Designation : </b> {{ $user_data->designation->name }}</p>
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Report Period : </b>{{ $start_date }} To {{ $end_date }}</p>
                    @if($durations > 0)
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Durations : </b>{{ $durations. ' Days' }}</p>
                    @endif
                </td>
                <td style="width: 33.33%; padding: 7px;text-align: left;">
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Total calls : </b> {{ $count['total_orders']??0 }}</p>
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Productive call : </b> {{ $count['productive_orders']??0 }}</p>
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Unproductive calls : </b> {{ $count['unproductive_orders']??0 }}</p>
                    <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Total Order Amount : <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format($grandTotal,2) }}</b></p>
                </td>
                <td style="width: 33.33%; padding: 7px;text-align: right;">
                    @php
                    $logoPath = public_path('storage/' . getSettingData('company_logo'));
                    if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    } else {
                    $base64 = ''; // Or a default image
                    }
                    @endphp
                    @if($base64)
                    <img src="{{ $base64 }}" alt="Company Logo" style="width: 100px;">
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    @foreach($retailingOrders as $date => $orders)
    @php
    $userOrders = $orders->groupBy(function ($order) {
    return $order->distributor_id;
    });
    @endphp
    <table style="border-collapse: collapse; margin: auto; width: 100%" border="1">
        <tr style="border: 1px solid #aa0025; background-color:#aa0025; color: #FFFFFF;">
            <td colspan="5">
                <p style="font-size: 14px;margin: 0;"><b>Date:</b> {{ $date }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="5" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td>
        </tr>
        @include('pdf.order_details', ['orderTytpe' => 'R'])
    </table>
    @endforeach

    @include('pdf.no_order_report_pdf', ['color' => 'e31e29'])
</body>

</html>