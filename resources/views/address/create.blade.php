@extends('layouts.admin')
@section('content')
    @php
        $type = isset($address) ? __('Edit') : __('Add New');
        $defaultAddress = 1;

        $countryIdSelect = old('country_id', $address->country_id??null);
        $stateId = old('state_id', $address->state_id??null);
        $cityId = old('city_id', $address->city_id??null);
        $areaId = old('area_id', $address->area_id??null);
    @endphp
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb',  ['route' => route('address.index', [$userType, $userId]), 'parentName' => __('Address'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($address) ? route('address.update', [$userType, $userId, $address->id]) : route('address.store',[$userType, $userId])}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($address))
                                        @method('PUT')
                                    @endif

                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="first_name" class="form-label" :value="__('Firstname')" /><span class="error">*</span>
                                                <x-text-input id="first_name" class="form-control" type="text" name="first_name" :value="old('first_name', $address->first_name??null)"/>
                                                <x-input-error :messages="$errors->first('first_name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="last_name" class="form-label" :value="__('Lastname')" /><span class="error">*</span>
                                                <x-text-input id="last_name" class="form-control" type="text" name="last_name" :value="old('last_name', $address->last_name??null)"/>
                                                <x-input-error :messages="$errors->first('last_name')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="email" class="form-label" :value="__('Email')" /><span class="error">*</span>
                                                <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $address->email??null)"/>
                                                <x-input-error :messages="$errors->first('email')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="mobile_no" class="form-label" :value="__('Mobile')" />
                                                <x-text-input id="mobile_no" class="form-control only_numbers" type="text" name="mobile_no" :value="old('mobile_no', $address->mobile_no??null)" />
                                                <x-input-error :messages="$errors->first('mobile_no')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="flat" class="form-label" :value="__('Address Line1')" /><span class="error">*</span>
                                                <x-text-input id="flat" class="form-control" type="text" name="flat" :value="old('flat', $address->flat??null)"/>
                                                <x-input-error :messages="$errors->first('flat')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="area" class="form-label" :value="__('Address Line2')" />
                                                <x-text-input id="area" class="form-control" type="text" name="area" :value="old('area', $address->area??null)"/>
                                                <x-input-error :messages="$errors->first('area')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="landmark" class="form-label" :value="__('Landmark')" />
                                                <x-text-input id="landmark" class="form-control" type="text" name="landmark" :value="old('landmark', $address->landmark??null)"/>
                                                <x-input-error :messages="$errors->first('landmark')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="pincode" class="form-label" :value="__('Pincode')" /><span class="error">*</span>
                                                <x-text-input id="pincode" class="form-control only_numbers" type="text" name="pincode" :value="old('pincode', $address->pincode??null)" />
                                                <x-input-error :messages="$errors->first('pincode')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="country_id" class="form-label" :value="__('Country')" /><span class="error">*</span>
                                                <select class="form-select form-control" aria-label="Default select example" name="country_id" id="country_id">
                                                    <option value="">Please Select</option>    
                                                    @if($countries)
                                                        @foreach($countries as $countryId => $countryName)
                                                            <option value="{{$countryId}}" {{ (int)$countryIdSelect === $countryId ? 'selected' : ''}} >{{$countryName}}</option>
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
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="area_id" class="form-label" :value="__('Area')" />
                                                <select class="form-select form-control" aria-label="Default select example" name="area_id" id="area_id">
                                                    <option value="">Please Select</option>    
                                                </select>
                                                <x-input-error :messages="$errors->first('area_id')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="default_address" class="form-label" :value="__('Default Address')" />
                                                <div class="form-check form-switch">
                                                    <input id="default_address" class="form-check-input" type="checkbox" name="default_address" value="{{$defaultAddress}}" {{((old('default_address', $address->default_address??$defaultAddress) === $defaultAddress) ? 'checked' : '')}}/>
                                                </div>
                                                <x-input-error :messages="$errors->first('default_address')" class="mt-2" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{route('address.index', [$userType, $userId])}}" class="btn btn-outline-secondary">Back</a>
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

        $(document).on('change', '#country_id', function(){
            getState($(this).val(), "{{$stateId}}", "{{$cityId}}", "{{$areaId}}");
        });
        $(document).on('change', '#state_id', function(){
            getCity($(this).val(), "{{$cityId}}");
        });
        $(document).on('change', '#city_id', function(){
            getArea($(this).val(), "{{$areaId}}");
        });

        $(document).ready(function() {
            if("{{$type}}" === 'Edit'){
                getState("{{$countryIdSelect}}", "{{$stateId}}", "{{$cityId}}", "{{$areaId}}");
            }
        });

        $('.FormValidate').validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                email : {
                    required: true,
                },
                /*mobile_no: {
                    required: true,
                },*/
                flat: {
                    required: true,
                },
                pincode: {
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
                }
            },
            messages: {
                first_name: {
                    required: "Please enter Firstame."
                },
                last_name: {
                    required: "Please enter Lastname."
                },
                email: {
                    required: "Please enter your Email."
                },
                mobile_no: {
                    required: "Please enter Mobile."
                },
                flat: {
                    required: "Please enter Address."
                },
                pincode: {
                    required: "Please enter Pincode."
                },
                country_id: {
                    required: "Please select Country."
                },
                state_id: {
                    required: "Please select State."
                },
                city_id: {
                    required: "Please select City."
                }
            }
        });
    </script>
@endpush