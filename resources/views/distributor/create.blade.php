@extends('layouts.admin')
@section('content')
    @php
        $type = isset($distributor) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
        $isInterested = 1;

        $countryIdSelect = old('country_id', $distributor->country_id ?? 1);
        $isInterestedSelect = old('is_interested', $distributor->is_interested ?? '');
        $stateId = old('state_id', $distributor->state_id ?? null);
        $cityId = old('city_id', $distributor->city_id ?? null);
        $areaId = old('area_id', $distributor->area_id ?? null);
        $zoneSelectId = old('zone_id', $distributor->zone_id ?? null);

        $zones = getZones();
    @endphp
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => route('distributor.index'), 'parentName' => __('Distributor'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <form method="POST"
                        action="{{ isset($distributor) ? route('distributor.update', $distributor->id) : route('distributor.store')}}"
                        class="form FormValidate" accept-charset="UTF-8" autocomplete="off">
                        @csrf
                        @if(isset($distributor))
                            @method('PUT')
                        @endif

                        <div class="bs-stepper-header">
                            <div class="step" data-target="#general" role="tab" id="general-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Profile</span>
                                    </span>
                                </button>
                            </div>

                            <!-- <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                                <div class="step" data-target="#documents" role="tab" id="documents-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Documents</span>
                                        </span>
                                    </button>
                                </div> -->
                        </div>
                        <div class="bs-stepper-content">
                            <div id="general" class="content" role="tabpanel" aria-labelledby="general-trigger">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="firstname" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="firstname" class="form-control" type="text" name="firstname" :value="old('firstname', $distributor->firstname ?? null)" />
                                            <x-input-error :messages="$errors->first('firstname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="lastname" class="form-label" :value="__('Contact Person')" /><span class="error">*</span>
                                            <x-text-input id="lastname" class="form-control" type="text" name="lastname" :value="old('lastname', $distributor->lastname ?? null)" />
                                            <x-input-error :messages="$errors->first('lastname')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="email" class="form-label" :value="__('Email')" />
                                            <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email ', $distributor->email ?? null)" />
                                            <x-input-error :messages="$errors->first('email')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="mobile" class="form-label" :value="__('Mobile')" /><span class="error">*</span>
                                            <x-text-input id="mobile" class="form-control only_numbers" type="text" name="mobile" :value="old('mobile', $distributor->mobile ?? null)" />
                                            <x-input-error :messages="$errors->first('mobile')" class="mt-2" />
                                        </div>
                                    </div>
                                    <?php /*<div class="col-xl-4 col-md-6 col-12">
                                           <div class="mb-1">
                                               <x-input-label for="shop_name" class="form-label" :value="__('Retailer Name')" /><span class="error">*</span>
                                               <x-text-input id="shop_name" class="form-control" type="text" name="shop_name" :value="old('shop_name', $distributor->shop_name??null)"/>
                                               <x-input-error :messages="$errors->first('shop_name')" class="mt-2" />
                                           </div>
                                       </div>*/ ?>
                                    <div class="col-xl-4 col-md-6 col-12 d-none">
                                        <div class="mb-1">
                                            <x-input-label for="customer_code" class="form-label" :value="__('Customer Code')" />
                                            <x-text-input id="customer_code" class="form-control" type="text" name="customer_code" :value="old('customer_code', $distributor->customer_code ?? null)" />
                                            <x-input-error :messages="$errors->first('customer_code')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 d-none">
                                        <div class="mb-1">
                                            <x-input-label for="country_id" class="form-label" :value="__('Country')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="country_id" id="country_id">
                                                <option value="">Please Select</option>
                                                @if($countries)
                                                    @foreach($countries as $countryId => $countryName)
                                                        <option value="{{$countryId}}" {{ (int) $countryIdSelect === $countryId ? 'selected' : ''}}>{{$countryName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="state_id" class="form-label" :value="__('State')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="state_id" id="state_id">
                                                <option value="">Please Select</option>
                                            </select>
                                            <x-input-error :messages="$errors->first('state_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="city_id" class="form-label" :value="__('City')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="city_id" id="city_id">
                                                <option value="">Please Select</option>
                                            </select>
                                            <x-input-error :messages="$errors->first('city_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <!-- <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="area_id" class="form-label" :value="__('Area')" /><span
                                                class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="area_id" id="area_id">
                                                <option value="">Please Select</option>
                                            </select>
                                            <x-input-error :messages="$errors->first('area_id')" class="mt-2" />
                                        </div>
                                    </div> -->
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="area_of_operation" class="form-label" :value="__('Area Of Operation')" /><span class="error">*</span>
                                            <x-text-input id="area_of_operation" class="form-control" type="text" name="area_of_operation" :value="old('area_of_operation', $distributor->area_of_operation ?? null)" />
                                            <x-input-error :messages="$errors->first('area_of_operation')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="current_dealership" class="form-label" :value="__('Current Dealership')" /><span
                                                class="error">*</span>
                                            <x-text-input id="current_dealership" class="form-control" type="text" name="current_dealership"
                                                :value="old('current_dealership', $distributor->current_dealership ?? null)" />
                                            <x-input-error :messages="$errors->first('current_dealership')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 d-none">
                                        <div class="mb-1">
                                            <x-input-label for="address" class="form-label" :value="__('Address')" /><span
                                                class="error">*</span>
                                            <x-textarea id="address" class="form-control"
                                                name="address">{{old('address', $distributor->address ?? null)}}</x-textarea>
                                            <x-input-error :messages="$errors->first('address')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 d-none">
                                        <div class="mb-1">
                                            <x-input-label for="pincode" class="form-label" :value="__('Pincode')" />
                                            <x-text-input id="pincode" class="form-control only_numbers" type="text"
                                                name="pincode" :value="old('pincode', $distributor->pincode ?? null)" />
                                            <x-input-error :messages="$errors->first('pincode')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="zone_id" class="form-label" :value="__('Zone')" /><span
                                                class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="zone_id" id="zone_id">
                                                <option value="">Please Select</option>
                                                @if($zones)
                                                    @foreach($zones as $zoneId => $zoneName)
                                                        <option value="{{$zoneId}}" {{((int) $zoneSelectId === $zoneId) ? 'selected' : ''}}>{{$zoneName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('zone_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="cst_gst_no" class="form-label" :value="__('GSTIN')" />
                                            <x-text-input id="cst_gst_no" class="form-control" type="text" name="cst_gst_no"
                                                :value="old('cst_gst_no', $distributor->cst_gst_no ?? null)" />
                                            <x-input-error :messages="$errors->first('cst_gst_no')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 @if(!isset($distributor)) d-none @endif">
                                        <div class="mb-1">
                                            <x-input-label for="is_interested" class="form-label" :value="__('Is Interested')" />
                                            <?php /*<div class="form-check form-switch">
                                                   <input id="is_interested" class="form-check-input" type="checkbox" name="is_interested" value="{{$isInterested}}" {{((old('is_interested', $distributor->is_interested??0) === $isInterested) ? 'checked' : '')}}/>
                                               </div>*/ ?>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="is_interested" id="is_interested">
                                                <option value="">Please Select</option>
                                                @if($meetingTypes)
                                                    @foreach($meetingTypes as $meetingTypeId => $meetingTypeName)
                                                        <option value="{{$meetingTypeId}}" {{ (int) $isInterestedSelect === $meetingTypeId ? 'selected' : ''}}>
                                                            {{$meetingTypeName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('is_interested')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status"
                                                    value="{{$activeStatus}}" {{((old('status', $distributor->status ?? $activeStatus) === $activeStatus) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row d-none">
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="pan_no" class="form-label" :value="__('Pan No. of Company')" /><span class="error">*</span>
                                            <x-text-input id="pan_no" class="form-control" type="text" name="pan_no"
                                                :value="old('pan_no', $distributor->pan_no ?? null)" />
                                            <x-input-error :messages="$errors->first('pan_no')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-8 col-md-6 col-12">
                                        <div class="row" id="distributor_image">
                                            <div class="col-12">
                                                <div class="row input_image">
                                                    <div class="col-md-6">
                                                        <x-input-label for="pan_doc" class="form-label" :value="__('PAN Card Document')" /><span class="error">*</span>
                                                        <div class="input-group">
                                                            <input type="hidden" name="edit_pan_doc" class="edit_image"
                                                                value="{{ $distributor->pan_doc ?? '' }}" id="edit_pan_doc">
                                                            <x-text-input id="pan_doc" class="form-control selected_image"
                                                                type="text" name="pan_doc" readonly aria-label="Image"
                                                                aria-describedby="button-image" />
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary choose-file-button"
                                                                    type="button" data-id="pan_doc">Choose File</button>
                                                            </div>
                                                        </div>
                                                        <x-input-error :messages="$errors->first('pan_doc')" class="mt-2" />
                                                    </div>
                                                    @if(isset($distributor) && $distributor->pan_doc !== null)
                                                        <div class="col-md-2 my-1" id="image-tag">
                                                            <img src="{{ asset('storage/' . $distributor->pan_doc) }}" alt=""
                                                                style="height:100px; width:100px">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="image_label"></label>
                                                            <div class="input-group-append text-xl-end text-md-end">
                                                                <button class="btn btn-danger remove_image"
                                                                    type="button">Delete</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php /*<div class="col-xl-4 col-md-6 col-12">
                                           <div class="mb-1">
                                               <x-input-label for="vat_tin_no" class="form-label" :value="__('VAT/TIN No.')" />
                                               <x-text-input id="vat_tin_no" class="form-control" type="text" name="vat_tin_no" :value="old('vat_tin_no', $distributor->vat_tin_no??null)"/>
                                               <x-input-error :messages="$errors->first('vat_tin_no')" class="mt-2" />
                                           </div>
                                       </div>
                                       <div class="col-xl-4 col-md-6 col-12">
                                           <div class="mb-1">
                                               <x-input-label for="cst_gst_no" class="form-label" :value="__('GSTIN')" /><span class="error">*</span>
                                               <x-text-input id="cst_gst_no" class="form-control" type="text" name="cst_gst_no" :value="old('cst_gst_no', $distributor->cst_gst_no??null)"/>
                                               <x-input-error :messages="$errors->first('cst_gst_no')" class="mt-2" />
                                           </div>
                                       </div>*/?>
                                    <div class="col-xl-8 col-md-6 col-12 d-none">
                                        <div class="row" id="distributor_image">
                                            <div class="col-12">
                                                <div class="row input_image">
                                                    <div class="col-md-6">
                                                        <x-input-label for="gst_doc" class="form-label" :value="__('GST Card Document')" /><span class="error">*</span>
                                                        <div class="input-group">
                                                            <input type="hidden" name="edit_gst_doc" class="edit_image"
                                                                value="{{ $distributor->gst_doc ?? '' }}" id="edit_gst_doc">
                                                            <x-text-input id="gst_doc" class="form-control selected_image"
                                                                type="text" name="gst_doc" readonly aria-label="Image"
                                                                aria-describedby="button-image" />
                                                            <div class="input-group-append">
                                                                <button class="btn btn-outline-secondary choose-file-button"
                                                                    type="button" data-id="gst_doc">Choose File</button>
                                                            </div>
                                                        </div>
                                                        <x-input-error :messages="$errors->first('gst_doc')" class="mt-2" />
                                                    </div>
                                                    @if(isset($distributor) && $distributor->gst_doc !== null)
                                                        <div class="col-md-2 my-1" id="image-tag">
                                                            <img src="{{ asset('storage/' . $distributor->gst_doc) }}" alt=""
                                                                style="height:100px; width:100px">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label for="image_label"></label>
                                                            <div class="input-group-append text-xl-end text-md-end">
                                                                <button class="btn btn-danger remove_image"
                                                                    type="button">Delete</button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div id="documents" class="content" role="tabpanel" aria-labelledby="documents-trigger"></div> -->
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('distributor.index') }}" class="btn btn-outline-secondary">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">

        $(document).on('change', '#country_id', function () {
            getState($(this).val(), "{{$stateId}}", "{{$cityId}}", "{{$areaId}}");
        });
        $(document).on('change', '#state_id', function () {
            getCity($(this).val(), "{{$cityId}}");
        });
        $(document).on('change', '#city_id', function () {
            getArea($(this).val(), "{{$areaId}}");
        });

        $(document).ready(function () {
            //if("{{$type}}" === 'Edit'){
            getState("{{$countryIdSelect}}", "{{$stateId}}", "{{$cityId}}", "{{$areaId}}");
            getCity("{{ $stateId }}", "{{ $cityId }}");
            //}
        });

        $('.FormValidate').validate({
            rules: {
                firstname: {
                    required: true,
                },
                lastname: {
                    required: true,
                },
                /*email : {
                    required: true,
                },*/
                mobile: {
                    required: true,
                },
                shop_name: {
                    required: true,
                },
                country_id: {
                    required: true,
                },
                state_id: {
                    required: true,
                },
                city_id: {
                    required: true,
                },
                current_dealership: {
                    required: true,
                },
                area_of_operation: {
                    required: true,
                },
                //area_id: {
                //    required: true,
               // },
                address: {
                    //required: true,
                },
                zone_id: {
                    required: true,
                },
                cst_gst_no: {
                    //required: true,
                },
                /*pan_no: {
                    required: true,
                },
                pan_doc: {
                    required: function () {
                        if ($("#edit_pan_doc").val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                gst_doc: {
                    required: function () {
                        if ($("#edit_gst_doc").val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },*/
            },
            messages: {
                firstname: {
                    required: "Please enter Name."
                },
                lastname: {
                    required: "Please enter Contact Person."
                },
                /* email: {
                    required: "Please enter your Email."
                }, */
                mobile: {
                    required: "Please enter Mobile."
                },
                shop_name: {
                    required: "Please enter Retailer Name."
                },
                country_id: {
                    required: "Please select Country."
                },
                state_id: {
                    required: "Please select State."
                },
                city_id: {
                    required: "Please select City."
                },
                area_of_operation: {
                    required: "Please Enter Area Of Operation."
                },
                current_dealership: {
                    required: "Please Enter Current Dealership."
                },
                //area_id: {
                //    required: "Please select Area."
               // },
                address: {
                    required: "Please enter Retailer Address."
                },
                zone_id: {
                    required: "Please select Zone."
                },
                pan_no: {
                    required: "Please enter Pan No. of Company."
                },
                cst_gst_no: {
                    required: "Please enter GSTIN"
                }
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            $('body').on('click', '.choose-file-button', function (event) {
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

        @if(isset($distributor))
            $(".remove_image").click(function () {
                var imgTable = $(this).closest('.input_image');
                imgTable.find('.selected_image').val('');
                column_name = imgTable.find('.selected_image').attr('name');

                if (imgTable.find('.selected_image').val() == '') {
                    $.ajax({
                        url: "{{ route('distributor.delete.image') }}",
                        type: "POST",
                        data: {
                            "id": "{{ $distributor->id }}",
                            'column_name': column_name,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            if (response.status == true) {
                                imgTable.find('#image-tag').remove();
                                imgTable.find('.edit_image').val('');
                                imgTable.find('.remove_image').addClass('d-none');
                            }
                        }
                    });
                } else {
                    imgTable.find('.selected_image').val('');
                }
            });
        @endif
    </script>
@endpush