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

    <body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
        <table style="border-collapse: collapse; margin: auto;  width: 100%">
            <thead>
                <tr style="border: 1px solid #e31e29; background-color:#e31e29; color: #FFFFFF;">
                    <td colspan="2">
                        <p style="text-align: center;font-size: 14px;margin: 0;"><b>MEETING REPORT</b></p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 50%;  padding: 15px 7px; text-align: left; padding: 7px;">
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Name : </b> {{ $user_data->firstname.' '.$user_data->lastname }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Designation : </b> {{ $user_data->designation->name }}</p>
                        <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Report Period : </b>{{ $start_date }} To {{ $end_date }}</p>
                        @if($durations > 0)
                            <p style="margin: 0; font-size: 12px; padding-bottom: 5px;"><b>Durations : </b>{{ $durations. ' Days' }}</p>
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

        @if($meetingList)
            <table style="border-collapse: collapse; margin: auto;  width: 100%;" border="1">
                @foreach($meetingList as $date => $meetings)
                    <tr style="border: 1px solid #aa0025; background-color:#aa0025; color: #FFFFFF;">
                        <td colspan="4">
                            <p style="font-size: 14px;margin: 0;"><b>Date:</b> {{ $date }}</p>
                        </td>
                    </tr>
                    <tr><td colspan="4" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>   
                    @include('pdf.meeting_details')
                @endforeach
            </table>
        @endif
    </body>
</html>