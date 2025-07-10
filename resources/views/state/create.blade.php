@extends('layouts.admin')
@section('content')
    @php
        $type = isset($state) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
    @endphp
    <div class="content-wrapper p-0">
        @include('elements.breadcrumb',  ['route' => route('state.index'), 'parentName' => __('State'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($state) ? route('state.update', $state->id) : route('state.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($state))
                                        @method('PUT')
                                    @endif

                                    <input type="hidden" name="state_id" value="{{ $state->id ?? '' }}">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $state->name??null)" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="country_id" class="form-label" :value="__('Country')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="country_id" id="country_id">
                                                <option value="">Please Select</option>    
                                                @if($countryList)
                                                    @foreach($countryList as $countryId => $countryName)
                                                        <option value="{{$countryId}}" {{ old('country_id', $state->country_id??'') == (int)$countryId ? 'selected' : ''}} >{{$countryName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('country_id')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="zone_id" class="form-label" :value="__('Zone')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="zone_id" id="zone_id">
                                                <option value="">Please Select</option>    
                                                @if($zoneList)
                                                    @foreach($zoneList as $zoneId => $zoneName)
                                                        <option value="{{$zoneId}}" {{ old('zone_id', $state->zone_id??'') == $zoneId ? 'selected' : ''}} >{{$zoneName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('zone_id')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="code" class="form-label" :value="__('State Code')" />
                                            <x-text-input id="code" class="form-control" type="text" name="code" :value="old('code', $state->code??null)" autofocus autocomplete="code" />
                                            <x-input-error :messages="$errors->first('code')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="sort_order" class="form-label" :value="__('Sort Order')" />
                                            <x-text-input id="sort_order" class="form-control only_numbers" type="text" name="sort_order" :value="old('sort_order', $state->sort_order??($sortId??''))"/>
                                            <x-input-error :messages="$errors->first('sort_order')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $state->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('state.index') }}" class="btn btn-outline-secondary">Back</a>
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

    @push('script')
        <script type="text/javascript">
            $('.FormValidate').validate({
                rules: {
                    "name": {
                        required : true,
                    },
                    "country_id": {
                        required : true,
                    },
                    "zone_id": {
                        required : true,
                    }
                },
                messages: {
                    "name": {
                        required: "Please Enter Name",
                    },
                    "country_id": {
                        required: "Please Select Country",
                    },
                    "zone_id": {
                        required: "Please Select Zone",
                    }
                }
            });
        </script>
    @endpush
@endsection