@extends('layouts.admin')
@section('content')
    @php
        $type = isset($coupon) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');

        $couponTypes = couponTypes();
    @endphp
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb',  ['route' => route('coupon.index'), 'parentName' => __('Coupon'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])
        
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($coupon) ? route('coupon.update', $coupon->id) : route('coupon.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($coupon))
                                        @method('PUT')
                                        <input type="hidden" id="couponId" name="coupon_id" value="{{ $coupon->id ?? '' }}">
                                    @endif

                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="name" class="form-label" :value="__('Coupon Name')" /><span class="error">*</span>
                                                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $coupon->name??null)"/>
                                                <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="code" class="form-label" :value="__('Coupon Code')" /><span class="error">*</span>
                                                <x-text-input id="code" class="form-control uppercase" type="text" name="code" :value="old('code', $coupon->code??null)"/>
                                                <x-input-error :messages="$errors->first('code')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="type" class="form-label" :value="__('Coupon Type')" /></label>
                                                <select class="form-select form-control" aria-label="Default select example" name="type" id="type">
                                                    @if($couponTypes)
                                                        @foreach($couponTypes as $couponTypeValue => $couponType)
                                                            <option value="{{$couponTypeValue}}" {{ old('type', $coupon->type??config('constants.PERCENTADE')) == $couponTypeValue ? 'selected' : ''}} >{{$couponType}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <x-input-error :messages="$errors->first('type')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="discount" class="form-label" :value="__('Discount')" /><span class="error">*</span>
                                                <x-text-input id="discount" class="form-control only_numbers" type="text" name="discount" :value="old('discount', $coupon->discount??null)"/>
                                                <x-input-error :messages="$errors->first('discount')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="min_order_value" class="form-label" :value="__('Minimum Order Value')" /><span class="error">*</span>
                                                <x-text-input id="min_order_value" class="form-control only_numbers" type="text" name="min_order_value" :value="old('min_order_value', $coupon->min_order_value??null)"/>
                                                <x-input-error :messages="$errors->first('min_order_value')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="max_discount_allow" class="form-label" :value="__('Maximum Discount Allow')" /><span class="error">*</span>
                                                <x-text-input id="max_discount_allow" class="form-control only_numbers" type="text" name="max_discount_allow" :value="old('max_discount_allow', $coupon->max_discount_allow??null)"/>
                                                <x-input-error :messages="$errors->first('max_discount_allow')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="start_date" class="form-label" :value="__('Start Date to End Date')" /><span class="error">*</span>
                                                <x-text-input id="start_date" class="form-control dateRangePickr" placeholder="YYYY-MM-DD" type="text" name="start_date" :value="old('start_date', $coupon->start_date??null)"/>
                                                <x-input-error :messages="$errors->first('start_date')" class="mt-2" />
                                            </div>
                                        </div>
                                        <?php /*<div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="end_date" class="form-label" :value="__('End Date')" />
                                                <x-text-input id="end_date" class="form-control flatpickr-basic" placeholder="YYYY-MM-DD" type="text" name="end_date" :value="old('end_date', $coupon->end_date??null)"/>
                                                <x-input-error :messages="$errors->first('end_date')" class="mt-2" />
                                            </div>
                                        </div>*/ ?>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="total_coupon" class="form-label" :value="__('Number of Coupon')" /><span class="error">*</span>
                                                <x-text-input id="total_coupon" class="form-control only_numbers" type="text" name="total_coupon" :value="old('total_coupon', $coupon->total_coupon??null)"/>
                                                <x-input-error :messages="$errors->first('total_coupon')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="coupon_use_time" class="form-label" :value="__('Coupon Use Time')" /><span class="error">*</span>
                                                <x-text-input id="coupon_use_time" class="form-control only_numbers" type="text" name="coupon_use_time" :value="old('coupon_use_time', $coupon->coupon_use_time??null)"/>
                                                <x-input-error :messages="$errors->first('coupon_use_time')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="status" class="form-label" :value="__('Status')" />
                                                <div class="form-check form-switch">
                                                    <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $coupon->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                                </div>
                                                <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('coupon.index') }}" class="btn btn-outline-secondary">Back</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Basic Floating Label Form section end -->
        </div>
    </div>   
@endsection

@push('script')
    <script type="text/javascript">

        $('.dateRangePickr').flatpickr({
            mode: "range",
            dateFormat: 'Y-m-d',
            defaultDate: ["{{$coupon->start_date??''}}", "{{$coupon->end_date??''}}"]
        });
        $('.FormValidate11').validate({
            rules: {
                "name": {
                    required : true,
                },
                "code": {
                    required : true,
                },
                "discount": {
                    required : true,
                },
                "min_order_value": {
                    required : true,
                },
                "start_date": {
                    required : true,
                },
                /*"end_date": {
                    required : true,
                    greaterThan: "#StartDate" 
                },*/
                "total_coupon": {
                    required : true,
                },
                "max_discount_allow": {
                    required : true,
                },
                "coupon_use_time": {
                    required : true,
                },
                "status": {
                    required : true,
                },
            },
            messages: {
                "name": {
                    required: "Please Enter Coupon Name",
                },
                "code": {
                    required: "Please Enter Coupon Code",
                },
                "discount": {
                    required: "Please Enter Discount",
                },
                "min_order_value": {
                    required: "Please Enter Minimum Order Value",
                },
                "start_date": {
                    required: "Please Select Start Date",
                },
                "end_date": {
                    required: "Please Select End Date",
                },
                "total_coupon": {
                    required: "Please Enter Number of Coupon",
                },
                "max_discount_allow": {
                    required: "Please Enter Maximum Discount Allow",
                },
                "coupon_use_time": {
                    required: "Please Enter Coupon Use Time",
                },
                "status": {
                    required: "Please Select Status",
                },
            }
        });
    </script>
@endpush