<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>{{ str_replace('.pdf','',$pdfName) }} | {{ getSettingData('company_name') }}</title>
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
      
    <body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;" >
        <table style="border-collapse: collapse; margin: auto;  width: 100%">
            <thead>
                <tr style="border: 1px solid #e31e29; background-color:#e31e29; color: #FFFFFF;">
                    <td colspan="2">
                        <p style="text-align: center;font-size: 14px;margin: 0;"><b>EXPENSE REPORT</b></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 50%; font-size: 12px; text-align: left; padding: 7px; line-height:19px;">
                        <b>Name : </b> {{ $user_data->firstname.' '.$user_data->lastname }}</br>
                        <b>Designation : </b> {{ $user_data->designation->name }}</br>
                        <b>Duration : </b> {{ $start_date.' To '.$end_date }}</br>
                        @if($durations > 0)
                            <b>Durations : </b>{{ $durations. ' Days' }}</br>
                        @endif
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
            @php 
                $dailyAllowanceMain = 0;
            @endphp

            @foreach($tada_data as $date => $tadas)
                <tr style="border: 1px solid #aa0025; background-color:#aa0025; color: #FFFFFF;">
                    <td colspan="10">
                        <p style="font-size: 14px;margin: 0;"><b>Date:</b> {{ $date }}</p>
                    </td>
                </tr>

                @php
                    $firstTada = $tadas->first();
                    $dailyAmt = ($firstTada->tadatype->name === 'Daily Allowance') ? $firstTada->amount??0 : 0;
                    $dailyAllowanceMain += $dailyAmt;
                    //\Log::info('=========>'.$dailyAmt.'/'.$dailyAllowanceMain);
                @endphp
                @include('pdf.expense_details', ['tadas' => $tadas])
            @endforeach
            @php 
                $totalExpense = array_sum(data_get($tada_data,'*.*.amount')) - $dailyAllowanceMain;
                $totalDa = array_sum(data_get($tada_data,'*.0.daily_allowance')) + $dailyAllowanceMain;
                //$daData = data_get($tada_data,'*.0.daily_allowance'));
            @endphp
            <tr  style="border: 1px solid #aa0025; background-color:#aa0025; color: #FFFFFF;">
                <td style="font-size: 12px; text-align: right;" colspan="7"><b>Total</b></td>
                <td style="font-size: 12px; text-align: right;"><b>{!!config('constants.currency_html')!!} {{number_format($totalExpense,2) }}</b></td>
                <td style="font-size: 12px; text-align: right;"><b>{!!config('constants.currency_html')!!} {{number_format($totalDa,2)}}</b></td>
                <td style="font-size: 12px; text-align: right;"><b>{!!config('constants.currency_html')!!} {{number_format(($totalExpense + $totalDa),2)}}</b></td>
            </tr>
        </table>
    </body>
</html>