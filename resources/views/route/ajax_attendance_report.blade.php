    <div class="card">
        <div class="card-header">
            <h2 class="text-center">{{ \Carbon\Carbon::parse($data['monthYear'])->format('F-Y') }}</h2>
            <div class="d-flex">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item">
                        <strong>Note : </strong>
                    </li>
                    <li class="list-inline-item">
                        <strong>P</strong> - Present
                    </li>
                    <li class="list-inline-item">
                        <strong>A</strong> - Absent
                    </li>
                    <li class="list-inline-item">
                        <strong>H</strong> - Holiday
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sales Person Name</th>
                                    @for ($day = 1; $day <= \Carbon\Carbon::create($data['year'], $data['month'])->daysInMonth; $day++)
                                        <th>{{ \Carbon\Carbon::create($data['year'], $data['month'], $day)->format('D') }} {{ $day }}</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['attendance'] as $akey => $avalue)
                                    @php
                                        $present_days = 0;
                                        $absent_days = 0;
                                        $holiday_days = 0;
                                        $attendanceDate = [];

                                        foreach ($avalue->route as $rvalue) {
                                            $attendanceDate[] = \Carbon\Carbon::parse($rvalue['created_at'])->format('Y-m-d');
                                        }

                                        for ($day = 1; $day <= \Carbon\Carbon::create($data['year'], $data['month'])->daysInMonth; $day++) {
                                            
                                            $currentDate = \Carbon\Carbon::create($data['year'], $data['month'], $day);
                                            $formattedDate = $currentDate->format('Y-m-d');
                                            
                                            if ($currentDate->isSunday()) {
                                                $holiday_days++;
                                            } elseif (in_array($formattedDate, $attendanceDate)) {
                                                $present_days++;
                                            } else {
                                                $absent_days++;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td style="text-wrap-mode: nowrap;">
                                            {{ $avalue->firstname.' '.$avalue->lastname }}<br>
                                            <strong>Total Days</strong> ({{ \Carbon\Carbon::create($data['year'], $data['month'])->daysInMonth }}), <strong>Present Days</strong> ({{ $present_days }}), <br> <strong>Absent Days</strong> ({{ $absent_days }}), <strong>Holiday Days</strong> ({{ $holiday_days }})
                                        </td>
                                        @for ($day = 1; $day <= \Carbon\Carbon::create($data['year'], $data['month'])->daysInMonth; $day++)
                                            @php
                                                $currentDate = \Carbon\Carbon::create($data['year'], $data['month'], $day);
                                                $formattedDate = $currentDate->format('Y-m-d');
                                            @endphp
                                            @if ($currentDate->isSunday())
                                                <td class="text-white" style="background-color: orange">H</td>
                                                @php $holiday_days++; @endphp
                                            @elseif (in_array($formattedDate, $attendanceDate))
                                                <td class="bg-success text-white">P</td>
                                                @php $present_days++; @endphp
                                            @else
                                                <td class="bg-danger text-white">A</td>
                                                @php $absent_days++; @endphp
                                            @endif
                                        @endfor
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>