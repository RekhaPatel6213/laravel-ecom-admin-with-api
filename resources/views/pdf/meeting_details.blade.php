@php
    $distributorMeetings = $meetings->groupBy(function ($meeting) {
        return $meeting->distributor_id;
    });
    /*dd($distributorMeetings->toArray());

    $distributor = collect($distributorMeetings)->first()->toArray();
    dd($distributor);*/
    $keyI = 0;
@endphp

@foreach($distributorMeetings as $meeting)

    @php
        $distributor = $meeting->first()->toArray();
    @endphp
    @if($loop->index > 0)
        <tr><td colspan="4" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
    @endif
    <tr style="background-color:#2C679F; color: #FFFFFF;">
        <td style="width: 5%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;"> <b>Sr No.</b></td>
        <td style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
            <b>Distributor Name : </b>{{ $distributor['distributor']['firstname'].' '.$distributor['distributor']['lastname'] }}</br>
            <b>Mobile Number : </b>{{ $distributor['distributor']['mobile'] }}</br>
            <b>Address :</b>{{ $distributor['distributor']['address'] }}</br>
            <b>Country : </b>{{ $distributor['distributor']['country']['name'] }}</br>
        </td>
        <td colspan="2" style="width: 50%; font-size: 14px; text-align: left; padding: 7px; line-height: 19px;">
            <b>State : </b>{{ $distributor['distributor']['state']['name'] }}<br/>
            <b>City : </b>{{ $distributor['distributor']['city']['name'] }}<br/>
            <b>Zone : </b>{{ $distributor['distributor']['zone']['name'] }}<br/>
        </td>
    </tr>

    @foreach($meeting as $key => $mvalue)

        @php 
            $keyI++;
            $startMapLink = ($mvalue['start_latitude'] !== null && $mvalue['start_longitude'] !== null) ? 'https://www.google.com/maps?q='.$mvalue['start_latitude'].','.$mvalue['start_longitude'] : null;
            $endMapLink = ($mvalue['end_latitude'] !== null && $mvalue['end_longitude'] !== null) ? 'https://www.google.com/maps?q='.$mvalue['end_latitude'].','.$mvalue['end_longitude'] : null;

            $start_time = \Carbon\Carbon::parse($mvalue['start_time']);
            $end_time = \Carbon\Carbon::parse($mvalue['end_time']);

            $duration = $start_time->diff($end_time);
        @endphp
        <tr>
            <td style="width: 5%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;"> {{$keyI}}</td>
            <td style="width: 31.66%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                <b>Start Time : </b>{{ \Carbon\Carbon::parse($mvalue['start_time'])->format('h:i A') }}</br>
                <b>End Time : </b>{{ \Carbon\Carbon::parse($mvalue['end_time'])->format('h:i A') }}</br>
                <b>Duration : </b>{{ $duration->h }} Hours, {{ $duration->i }} Minutes</br>
                <b>Purpose Meeting : </b>{{ $mvalue['purpose'] }}</br>
            </td>
            <td style="width: 31.66%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                <b>Comment : </b>{{ $mvalue['comments'] }}</br>
                <b>Meeting Type : </b>{{ $mvalue['type']['name']??'' }}</br>
                <b>Start Location : </b>{!! $startMapLink !== null ? '<a href="'.$startMapLink.'" target="_blank">Link</a>' : ''!!}</br>
                <b>End Location : </b>{!! $endMapLink !== null ? '<a href="'.$endMapLink.'" target="_blank">Link</a>' : ''!!}</br>
            </td>
            <td style="width: 31.66%; font-size: 12px; text-align: left; padding: 7px; line-height: 19px;">
                @if(isset($mvalue['attachment1']) && !empty($mvalue['attachment1']) && file_exists('storage/'.$mvalue['attachment1']))
                    @php
                        $path = asset('storage/'.$mvalue['attachment1']) ;
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <b>Photo :</b></br>
                    <img src="{{ $base64 }}" alt="" style="height:100px;width:100px">
                @endif
            </td>
        </tr>
        @endforeach
        @if(!$loop->parent->last)
            <tr><td colspan="4" style="border-left: 1px solid #FFFFFF; border-right: 1px solid #FFFFFF;"></td></tr>
        @endif
@endforeach