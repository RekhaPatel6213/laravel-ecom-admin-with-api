@extends('layouts.admin')
@section('content')
    @php
        $type = isset($user) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
        $distributorIds = old('distributor_id', $user->distributor_id ?? []);

        $designationSelectId = old('designation_id', $user->designation_id ?? null);
        $zoneSelectId = old('zone_id', $user->zone_id ?? null);
    @endphp
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => route('user.index'), 'parentName' => __('Company Sales Person'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST"
                                    action="{{ isset($user) ? route('user.update', $user->id) : route('user.store')}}"
                                    class="form FormValidate" accept-charset="UTF-8" autocomplete="off">
                                    @csrf
                                    @if(isset($user))
                                        @method('PUT')
                                        <input type="hidden" name="user_id" value="{{ $user->id ?? '' }}">
                                    @endif

                                    <div class="row">
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="firstname" class="form-label"
                                                    :value="__('Firstname')" /><span class="error">*</span>
                                                <x-text-input id="firstname" class="form-control" type="text"
                                                    name="firstname" :value="old('firstname', $user->firstname ?? null)" />
                                                <x-input-error :messages="$errors->first('firstname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="lastname" class="form-label"
                                                    :value="__('Lastname')" /><span class="error">*</span>
                                                <x-text-input id="lastname" class="form-control" type="text" name="lastname"
                                                    :value="old('lastname', $user->lastname ?? null)" />
                                                <x-input-error :messages="$errors->first('lastname')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="email" class="form-label" :value="__('Email')" /><span
                                                    class="error">*</span>
                                                <x-text-input id="email" class="form-control" type="email" name="email"
                                                    :value="old('email', $user->email ?? null)" />
                                                <x-input-error :messages="$errors->first('email')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="password" class="form-label"
                                                    :value="__('Password')" />@if(!isset($user)) <span
                                                    class="error">*</span> @endif
                                                <x-text-input id="password" class="form-control" type="password"
                                                    name="password" autocomplete="new-password" />
                                                <x-input-error :messages="$errors->first('password')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="confirm_password" class="form-label" :value="__('Confirm Password ')" />@if(!isset($user)) <span class="error">*</span> @endif
                                                <x-text-input id="confirm_password" class="form-control" type="password"
                                                    name="confirm_password" />
                                                <x-input-error :messages="$errors->first('confirm_password')"
                                                    class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="mobile" class="form-label" :value="__('Mobile')" /><span
                                                    class="error">*</span>
                                                <x-text-input id="mobile" class="form-control only_numbers" type="text"
                                                    name="mobile" :value="old('mobile', $user->mobile ?? null)" />
                                                <x-input-error :messages="$errors->first('mobile')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="designation_id" class="form-label"
                                                    :value="__('Designation')" /><span class="error">*</span>
                                                <select class="form-select form-control" aria-label="Default select example"
                                                    name="designation_id" id="designation_id">
                                                    <option value="">Please Select</option>
                                                    @if($designations)
                                                        @foreach($designations as $designationId => $designationName)
                                                            <option value="{{$designationId}}"
                                                                {{((int) $designationSelectId === $designationId) ? 'selected' : ''}}>
                                                                {{$designationName}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <x-input-error :messages="$errors->first('designation_id')" class="mt-2" />
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
                                                <x-input-label for="distributor_id" class="form-label"
                                                    :value="__('Distributors')" />
                                                <select class="form-select form-control select2_multiple1"
                                                    aria-label="Default select example" name="distributor_id[]"
                                                    id="distributor_id" multiple>
                                                </select>
                                                <x-input-error :messages="$errors->first('distributor_id')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="daily-allowance" class="form-label" :value="__('Daily Allowance')" /><span class="error">*</span>
                                                <x-text-input id="daily_allowance" class="form-control float_numbers"
                                                    type="text" name="daily_allowance" :value="old('daily_allowance', $user->daily_allowance ?? null)" />
                                                <x-input-error :messages="$errors->first('daily_allowance')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="status" class="form-label" :value="__('Status')" />
                                                <div class="form-check form-switch">
                                                    <input id="status" class="form-check-input" type="checkbox"
                                                        name="status" value="{{$activeStatus}}" {{((old('status', $user->status ?? $activeStatus) === $activeStatus) ? 'checked' : '')}} />
                                                </div>
                                                <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                            <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Back</a>
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

        $(document).on('change', '#zone_id', function () {
            getDistributor($(this).val(), "{{implode(',', $distributorIds)}}", "multiple");
        });

        $(document).ready(function () {
            if ("{{$type}}" === 'Edit') {
                getDistributor("{{$zoneSelectId}}", "{{implode(',', $distributorIds)}}", "multiple");
            }
        });
        $.validator.addMethod("validEmail", function(value, element) {
    if (value === '') return true; 
    var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/;
    if (/^[-_.]|[-_.]$/.test(value)) return false;
    return emailRegex.test(value);
}, "Please enter a valid email address.");



        $('.FormValidate').validate({
            rules: {
                firstname: {
                    required: true,
                },
                lastname: {
                    required: true,
                },
                email: {
                    required: true,
                    validEmail: true
                },
                    /*password: {
                        required: {{!isset($user) ? 1 : 0}},
                passwordRegex: true,
                minlength: 6,
            },
            confirm_password: {
                required: {{!isset($user) ? 1 : 0}},
                minlength: 6,
                equalTo: "#password"
            }, */
            mobile: {
            required: true,
        },
            designation_id: {
            //required: true,
        },
            zone_id: {
            required: true,
        },
            distributor_id: {
            required: true,
        },
            daily_allowance: {
            required: true
        }
                },
            messages: {
            firstname: {
                required: "Please enter Firstame."
            },
            lastname: {
                required: "Please enter Lastname."
            },
            email: {
                required: "Please enter your Email."

            },
            password: {
                required: "Please enter Password."
            },
            confirm_password: {
                required: "Please enter Confirm Password."
            },
            mobile: {
                required: "Please enter Mobile."
            },
            designation_id: {
                required: "Please select Designation"
            },
            zone_id: {
                required: "Please select Zone."
            },
            distributor_id: {
                required: "Please select Distributor"
            },
            daily_allowance: {
                required: "Please Enter Daily Allowance"
            }
        }
            });
    </script>
@endpush