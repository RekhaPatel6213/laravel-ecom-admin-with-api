    @extends('layouts.admin')
    @section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('Setting'), 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])

        <div class="content-body">
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">

                    <form method="POST" action="{{route('setting.submit')}}" class="form-first FormValidate" accept-charset="UTF-8"  autocomplete="off">
                        @csrf

                        <div class="bs-stepper-header">
                            <div class="step" data-target="#account-details-modern" role="tab" id="account-details-modern-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">General</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#image" role="tab" id="image-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Image</span>
                                    </span>
                                </button>
                            </div>

                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#shipping" role="tab" id="shipping-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Order & Shipping</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="account-details-modern" class="content" role="tabpanel" aria-labelledby="account-details-modern-trigger">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_name" class="form-label" :value="__('Company Name')" /><span class="error">*</span>
                                        <x-text-input id="company_name" class="form-control" type="text" name="company_name" :value="old('company_name', getSettingData('company_name'))" />
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_email" class="form-label" :value="__('Company Email')" /><span class="error">*</span>
                                        <x-text-input id="company_email" class="form-control" type="text" name="company_email" :value="old('company_email', getSettingData('company_email'))" />  
                                            <p>If you want multiple Email use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_mobile" class="form-label" :value="__('Company Mobile Number')" /><span class="error">*</span>
                                        <x-text-input id="company_mobile" class="form-control" type="text" name="company_mobile" :value="old('company_mobile', getSettingData('company_mobile'))" />
                                        <p>If you want multiple Mobile use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_landline" class="form-label" :value="__('Company Landline Number')" /><span class="error">*</span>
                                        <x-text-input id="company_landline" class="form-control" type="text" name="company_landline" :value="old('company_landline', getSettingData('company_landline'))" />
                                        <p>If you want multiple Landline use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="customer_care_email" class="form-label" :value="__('Customer Care Email')" />
                                        <x-text-input id="customer_care_email" class="form-control" type="text" name="customer_care_email" :value="old('customer_care_email', getSettingData('customer_care_email'))" />
                                        <p>If you want multiple Email use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="customer_care_mob_no" class="form-label" :value="__('Customer Care Mobile')" />
                                        <x-text-input id="customer_care_mob_no" class="form-control" type="text" name="customer_care_mob_no" :value="old('customer_care_mob_no', getSettingData('customer_care_mob_no'))" />
                                        <p>If you want multiple Mobile use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="administration_address" class="form-label" :value="__('Company Administration Office')" /><span class="error">*</span>
                                        <x-textarea id="administration_address" class="form-control" name="administration_address">{{old('administration_address', getSettingData('administration_address'))}}</x-textarea>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_address" class="form-label" :value="__('Company Address')" />
                                        <x-textarea id="company_address" class="form-control" name="company_address">{{old('company_address', getSettingData('company_address'))}}</x-textarea>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="getting_contact_details_email" class="form-label" :value="__('Getting Contact Details Email')" />
                                        <x-textarea id="getting_contact_details_email" class="form-control" name="getting_contact_details_email">{{old('getting_contact_details_email', getSettingData('getting_contact_details_email'))}}</x-textarea>
                                        <p>If you want multiple Email use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_gst_no" class="form-label" :value="__('Company GST Number')" />
                                        <x-text-input id="company_gst_no" class="form-control" type="text" name="company_gst_no" :value="old('company_gst_no', getSettingData('company_gst_no'))" />
                                    </div>
                                </div>
                            </div>
                            <div id="image" class="content" role="tabpanel" aria-labelledby="image-trigger">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 mb-2">
                                        <div class="row input_image">
                                            <div class="col-md-8">
                                                <x-input-label for="company_logo" class="form-label" :value="__('Company Logo')" />
                                                <div class="input-group">
                                                    <x-text-input id="company_logo" class="form-control selected_image" type="text" name="company_logo"  readonly aria-label="Image" aria-describedby="button-image"/>
                                                    <input type="hidden" name="edit_company_logo" class="edit_image" value="{{getSettingData('company_logo')}}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="company_logo">Choose File</button>
                                                    </div>
                                                </div>
                                                <x-input-error :messages="$errors->first('image')" class="mt-2" />
                                            </div>
                                            @if(!empty(getSettingData('company_logo')))
                                                <div class="col-md-2 my-1" id="image-tag">
                                                    <img src="{{ asset('storage/'.getSettingData('company_logo')) }}" alt="" class="img-fluid">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 mb-2">
                                        <div class="row input_image">
                                            <div class="col-md-8">
                                                <x-input-label for="company_fav_logo" class="form-label" :value="__('Company Fav Logo')" />
                                                <div class="input-group">
                                                    <x-text-input id="company_fav_logo" class="form-control selected_image" type="text" name="company_fav_logo"  readonly aria-label="Image" aria-describedby="button-image"/>
                                                    <input type="hidden" name="edit_company_fav_logo" class="edit_image" value="{{getSettingData('company_fav_logo')}}">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="company_fav_logo">Choose File</button>
                                                    </div>
                                                </div>
                                                <x-input-error :messages="$errors->first('image')" class="mt-2" />
                                            </div>
                                            @if(!empty(getSettingData('company_fav_logo')))
                                                <div class="col-md-2 my-1" id="image-tag">
                                                    <img src="{{ asset('storage/'.getSettingData('company_fav_logo')) }}" alt="" class="img-fluid">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="shipping" class="content" role="tabpanel" aria-labelledby="shipping-trigger">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="company_state" class="form-label" :value="__('Company State')" />
                                        <select class="form-select form-control" aria-label="Default select example" name="company_state" id="company_state">
                                            <option value="">Please Select</option>    
                                            @if($stateList)
                                                @foreach($stateList as $stateId => $stateName)
                                                    <option value="{{$stateId}}" {{ old('company_state', getSettingData('company_state')) == (int)$stateId ? 'selected' : ''}} >{{$stateName}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="order_email" class="form-label" :value="__('Order Email')" /><span class="error">*</span>
                                        <x-text-input id="order_email" class="form-control" type="text" name="order_email" :value="old('order_email', getSettingData('order_email'))" />
                                        <p>If you want multiple Email use comma "," for separation.</p>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="order_prefix" class="form-label" :value="__('Order Prefix')" /><span class="error">*</span>
                                        <x-text-input id="order_prefix" class="form-control" type="text" name="order_prefix" :value="old('order_prefix', getSettingData('order_prefix'))" />
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-12 mb-1">
                                        <x-input-label for="invoice_prefix" class="form-label" :value="__('Invoice Prefix')" /><span class="error">*</span>
                                        <x-text-input id="invoice_prefix" class="form-control" type="text" name="invoice_prefix" :value="old('invoice_prefix', getSettingData('invoice_prefix'))" />
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div class="row mb-1">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-dark">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('body').on('click', '.choose-file-button', function(event) {
                event.preventDefault();
                inputId = $(this).data('id');
                window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
            });
        });

        // input
        let inputId = '';

        // set file link
        function fmSetLink($url) {
            document.getElementById(inputId).value = $url;
        }
    </script>
    @endsection