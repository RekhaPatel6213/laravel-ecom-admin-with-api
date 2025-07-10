    @extends('layouts.admin')
    @section('content')
        <div class="content-wrapper p-0">
            @include('elements.breadcrumb',  ['route' => '', 'parentName' => __('Atttendance Report'), 'name' => '', 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body ">
                                    <form method="POST" action="{{ route('route.attendance_report') }}" class="form jsFormValidate" accept-charset="UTF-8"  autocomplete="off">
                                        @csrf
                                        <div class="row justify-content-center">
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <x-input-label for="month" class="form-label" :value="__('Sales Person')" />
                                                <select class="form-select form-control jsUserId" aria-label="Default select example" name="user_id">
                                                    <option value="">Please Select</option>
                                                    @foreach($sales_person_list as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <x-input-label for="year" class="form-label" :value="__('Year')" /><span class="error">*</span>
                                                <select class="form-select form-control jsYear" aria-label="Default select example" name="year">
                                                    <option value="">Please Select</option>
                                                    @php
                                                        $currentYear = date('Y');
                                                    @endphp

                                                    @for($year = $currentYear; $year >= 2011; $year--)
                                                        <option value="{{ $year }}" {{ old('year') }}>{{ $year }}</option>
                                                    @endfor
                                                </select>
                                                <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <x-input-label for="month" class="form-label" :value="__('Month')" /><span class="error">*</span>
                                                <select class="form-select form-control jsMonth" aria-label="Default select example" name="month">
                                                    <option value="">Please Select</option>
                                                    @foreach(config('constants.MONTH') as $key => $value)
                                                        <option value="{{ $key }}" {{ old('month') }}>{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                <a href="{{ route('route.attendance_report') }}" class="btn btn-outline-secondary mt-2">Reset</a>
                                                <button type="button" class="btn btn-primary mt-2 jsExport" style="display:none">Export</button>
                                                <a href="javascript:void(0);" class="btn btn-outline-secondary mt-2 jsWait" style="display:none">Please Wait</a>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" id="AttendanceReport"></div>
                    </div>
                </section>
            </div>
        </div>
    @endsection

    @push('script')
        <script type="text/javascript">
            $('.jsFormValidate').validate({
                rules:{
                    'year': {
                        required: true
                    },
                    'month': {
                        required: true
                    }
                },
                messages:{
                    'year': {
                        required: 'Please Select Year'
                    },
                    'month': {
                        required: 'Please Select Month'
                    },
                },
                submitHandler: function(form) {
                    var formData = $(form).serialize();

                    $.ajax({
                        type:'post',
                        url:$(form).attr('action'),
                        data: formData,
                        dataType:'html',
                        success: function(response) {
                            $('#AttendanceReport').html(response);
                            $('.jsExport').show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });

            $(document).on('click','.jsExport',function(){
                var user_id = $('.jsUserId').val();
                    year = $('.jsYear').val();
                    month = $('.jsMonth').val();
                
                $.ajax({
                    url:"{{ route('route.attendance_export') }}",
                    method:"POST",
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data:{user_id:user_id,year:year,month:month},
                    dataType:"json",
                    beforeSend:function() {
                        $('.jsWait').show();
                        $('.jsExport').hide();
                    },
                    success:function(response) {
                        $('.jsWait').hide();
                        $('.jsExport').show();

                        if (response.success && response.file_url) {
                            window.location.href = response.file_url;
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        $('.jsWait').hide();
                        $('.jsExport').show();
                        console.error("Error: ", error);
                        alert('An error occurred while exporting attendance.');
                    }
                });
            });
        </script>
    @endpush