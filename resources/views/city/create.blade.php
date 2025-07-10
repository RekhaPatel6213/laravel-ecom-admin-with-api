@extends('layouts.admin')
@section('content')
    @php
        $type = isset($city) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
    @endphp
    <div class="content-wrapper p-0">
        @include('elements.breadcrumb',  ['route' => route('city.index'), 'parentName' => __('City'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($city) ? route('city.update', $city->id) : route('city.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($city))
                                        @method('PUT')
                                    @endif

                                    <input type="hidden" name="city_id" value="{{ $city->id ?? '' }}">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $city->name??null)" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <x-input-label for="state_id" class="form-label" :value="__('State')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="state_id" id="state_id">
                                                <option value="">Please Select</option>    
                                                @if($stateList)
                                                    @foreach($stateList as $stateId => $stateName)
                                                        <option value="{{$stateId}}" {{ old('state_id', $city->state_id??'') == (int)$stateId ? 'selected' : ''}} >{{$stateName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('state_id')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="code" class="form-label" :value="__('City Code')" />
                                            <x-text-input id="code" class="form-control" type="text" name="code" :value="old('code', $city->code??null)" autofocus autocomplete="code" />
                                            <x-input-error :messages="$errors->first('code')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12 mb-1">
                                            <x-input-label for="sort_order" class="form-label" :value="__('Sort Order')" />
                                            <x-text-input id="sort_order" class="form-control only_numbers" type="text" name="sort_order" :value="old('sort_order', $city->sort_order??($sortId??''))"/>
                                            <x-input-error :messages="$errors->first('sort_order')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $city->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('city.index') }}" class="btn btn-outline-secondary">Back</a>
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
                    "state_id": {
                        required : true,
                    },
                },
                messages: {
                    "name": {
                        required: "Please Enter Name",
                    },
                    "state_id": {
                        required: "Please Select State",
                    }
                }
            });
        </script>
    @endpush
@endsection