@extends('layouts.admin')
@section('content')
    @php
        $type = isset($shop) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
        $distributorIdSelect = old('distributor_id', $shop->distributor_id ?? '');
        $countryIdSelect = old('country_id', $shop->country_id ?? 1);
        $stateId = old('state_id', $shop->state_id ?? '');
        $cityId = old('city_id', $shop->city_id ?? '');
        $areaId = old('area_id', $shop->area_id ?? '');
        $zoneSelectId = old('zone_id', $shop->zone_id ?? null);

        $zones = getZones();   
    @endphp
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => route('shop.index'), 'parentName' => __('Retailer'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <form method="POST" action="{{ isset($shop) ? route('shop.update', $shop->id) : route('shop.store')}}"
                        class="form FormValidate" accept-charset="UTF-8" autocomplete="off">
                        @csrf
                        @if(isset($shop))
                            @method('PUT')
                            <input type="hidden" name="shop_id" value="{{ $shop->id ?? '' }}">
                        @endif

                        <div class="bs-stepper-header">
                            <div class="step" data-target="#general" role="tab" id="general-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Profile</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="general" class="content" role="tabpanel" aria-labelledby="general-trigger">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Retailer Name')" /><span class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name"
                                                :value="old('name', $shop->name ?? null)" />
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="contact_person_name" class="form-label" :value="__('Contact Person Name')" /><span class="error">*</span>
                                            <x-text-input id="contact_person_name" class="form-control" type="text"
                                                name="contact_person_name" :value="old('contact_person_name', $shop->contact_person_name ?? null)" />
                                            <x-input-error :messages="$errors->first('contact_person_name')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="email" class="form-label" :value="__('Email')" />
                                            <x-text-input id="email" class="form-control" type="email" name="email"
                                                :value="old('email ', $shop->email ?? null)" />
                                            <x-input-error :messages="$errors->first('email')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="mobile" class="form-label" :value="__('Mobile')" /><span
                                                class="error">*</span>
                                            <x-text-input id="mobile" class="form-control only_numbers" type="text"
                                                name="mobile" :value="old('mobile', $shop->mobile ?? null)" />
                                            <x-input-error :messages="$errors->first('mobile')" class="mt-2" />
                                        </div>
                                    </div>

                                    <!-- <div class="col-xl-4 col-md-6 col-12 d-none">
                                            <div class="mb-1">
                                                <x-input-label for="country_id" class="form-label"
                                                    :value="__('Country')" /><span class="error">*</span>
                                                <select class="form-select form-control" aria-label="Default select example"
                                                    name="country_id" id="country_id">
                                                    <option value="">Please Select</option>
                                                    @if($countries)
                                                        @foreach($countries as $countryId => $countryName)
                                                            <option value="{{$countryId}}" {{ $countryIdSelect === $countryId ? 'selected' : ''}}>{{$countryName}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                            </div>
                                        </div> -->
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

                                    <div class="col-xl-4 col-md-6 col-12 d-none">
                                        <div class="mb-1">
                                            <x-input-label for="address" class="form-label" :value="__('Retailer Address')" /><span class="error">*</span>
                                            <x-textarea id="address" class="form-control" name="address">{{old('address', $shop->address ?? null)}}</x-textarea>
                                            <x-input-error :messages="$errors->first('address')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 d-none">
                                        <div class="mb-1">
                                            <x-input-label for="pincode" class="form-label" :value="__('Pincode')" />
                                            <x-text-input id="pincode" class="form-control only_numbers" type="text"
                                                name="pincode" :value="old('pincode', $shop->pincode ?? null)" />
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
                                            <x-input-label for="gstin_no" class="form-label" :value="__('Gstin No.')" />
                                            <x-text-input id="gstin_no" class="form-control" type="text" name="gstin_no"
                                                :value="old('gstin_no', $shop->gstin_no ?? null)" />
                                            <x-input-error :messages="$errors->first('gstin_no')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="distributor_id" class="form-label"
                                                :value="__('Distributor')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="distributor_id" id="distributor_id">
                                                <option value="">Please Select</option>
                                                <?php /*@if($distributors)
                                                          @foreach($distributors as $distributorId => $distributorName)
                                                              <option value="{{$distributorId}}" {{ $distributorIdSelect === $distributorId ? 'selected' : ''}} >{{$distributorName}}</option>
                                                          @endforeach
                                                      @endif*/ ?>
                                            </select>
                                            <x-input-error :messages="$errors->first('distributor_id')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="area_id" class="form-label" :value="__('Route')" /><span
                                                class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example"
                                                name="area_id" id="area_id">
                                                <option value="">Please Select</option>
                                            </select>
                                            <x-input-error :messages="$errors->first('area_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="shop_area" class="form-label" :value="__('Area')" /><span
                                            class="error">*</span>
                                            <x-text-input id="shop_area" class="form-control" type="text" name="shop_area"
                                                :value="old('shop_area', $shop->shop_area ?? null)" />
                                            <x-input-error :messages="$errors->first('shop_area')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status"
                                                    value="{{$activeStatus}}" {{((old('status', $shop->status ?? $activeStatus) === $activeStatus) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">Back</a>
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
        $(document).on('change', '#zone_id', function () {
            getDistributor($(this).val(), "{{$distributorIdSelect}}", "single");
        });

        $(document).on('change', '#country_id', function () {
            getState($(this).val(), "{{$stateId}}", "{{$cityId}}", "{{$areaId}}");
        });
        $(document).on('change', '#state_id', function () {
            getCity($(this).val(), "{{$cityId}}");
        });
        $(document).on('change', '#distributor_id', function () {
            getArea($(this).val(), "{{$areaId}}");
        });

        $(document).ready(function () {
            getState("{{$countryIdSelect}}", "{{$stateId}}", "{{$cityId}}", "{{$areaId}}");
            if ("{{$type}}" === 'Edit') {
                getDistributor("{{$zoneSelectId}}", "{{$distributorIdSelect}}", "single");

                getArea("{{$distributorIdSelect}}", "{{$areaId}}");
            }
        });

        $('.FormValidate').validate({
            rules: {
                name: {
                    required: true,
                },
                contact_person_name: {
                    required: true,
                },
                email: {
                    //required: true,
                },
                mobile: {
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
                area_id: {
                    required: true,
                },
                shop_area: {
                    required: true,
                },
                address: {
                    //required: true,
                },
                zone_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter Retailer Name."
                },
                contact_person_name: {
                    required: "Please enter Contact Person Name."
                },
                email: {
                    required: "Please enter your Email."
                },
                mobile: {
                    required: "Please enter Mobile."
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
                area_id: {
                    required: "Please select Area."
                },
                address: {
                    required: "Please enter Retailer Address."
                },
                zone_id: {
                    required: "Please select Zone."
                }
            }
        });
    </script>
@endpush