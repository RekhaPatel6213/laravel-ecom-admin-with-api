    @extends('layouts.admin')
    @section('content')
        <div class="content-wrapper p-0">
            @include('elements.breadcrumb',  ['route' => '', 'parentName' => __('Employee Tracking'), 'name' => '', 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])
            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body ">
                                    <form method="POST" action="{{ route('route.employee_tracking') }}" class="form jsFormValidate" accept-charset="UTF-8"  autocomplete="off">
                                        @csrf
                                        <div class="row justify-content-center">
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <x-input-label for="month" class="form-label" :value="__('Sales Person')" /><span class="error">*</span>
                                                <select class="form-select form-control" aria-label="Default select example" name="user_id">
                                                    <option value="">Please Select</option>
                                                    @foreach($sales_person_list as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12">
                                                <div class="mb-1">
                                                    <x-input-label for="date" class="form-label" :value="__('Select Date')" />
                                                    <x-text-input id="date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" type="text" name="date" :value="old('date', $coupon->date??null)"/>
                                                    <x-input-error :messages="$errors->first('date')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                                <a href="{{ route('route.employee_tracking') }}" class="btn btn-outline-secondary mt-2">Reset</a>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="text-center">
                                                    <a href="javascript:void(0);" class="btn btn-primary mt-2" id="jsViewMap" target="_blank" style="display:none">View Tracking Map</a>
                                                    <p class="text-danger jsErrorMessage" style="display:none">No Data Found</p>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    @endsection

    @push('script')
        <script type="text/javascript">
            $('.jsFormValidate').validate({
                rules:{
                    'user_id': {
                        required: true
                    },
                    'date': {
                        required: true
                    }
                },
                messages:{
                    'user_id': {
                        required: 'Please Select Sales Person'
                    },
                    'date': {
                        required: 'Please Select Date'
                    },
                },
                submitHandler: function(form) {
                    var formData = $(form).serialize();

                    $.ajax({
                        type:'post',
                        url:$(form).attr('action'),
                        data: formData,
                        dataType:'json',
                        beforeSend: function() {
                            $('#jsViewMap').hide();
                            $('.jsErrorMessage').hide();
                        },
                        success: function(response) {
                            if(response.status)
                            {
                                $('#jsViewMap').attr('href',response.url);
                                $('#jsViewMap').show();
                                $('.jsErrorMessage').hide();
                            }
                            else
                            {
                                $('#jsViewMap').hide();
                                $('.jsErrorMessage').show();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                        }
                    });
                }
            });
        </script>
    @endpush