@extends('layouts.admin')
@section('content')
    @php
        $type = isset($area) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
    @endphp
    <div class="content-wrapper p-0">
        @include('elements.breadcrumb',  ['route' => route('area.index'), 'parentName' => __('Route'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($area) ? route('area.update', $area->id) : route('area.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($area))
                                        @method('PUT')
                                    @endif

                                    <input type="hidden" name="area_id" value="{{ $area->id ?? '' }}">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $area->name??null)" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="distributor_id" class="form-label" :value="__('Distributor')" /><span
                                                    class="error">*</span>
                                                <select class="form-select form-control" aria-label="Default select example" name="distributor_id" id="distributor_id">
                                                    <option value="">Please Select</option>
                                                    @if($DistributorList)
                                                        @foreach($DistributorList as $id => $fullname)
                                                            <option value="{{ $id }}" {{ old('distributor_id', $area->distributor_id ?? '') == $id ? 'selected' : '' }}>
                                                                {{ $fullname }}
                                                            </option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                                <x-input-error :messages="$errors->first('distributor_id')" class="mt-2" />
                                            </div>
                                        </div>
                                        <!-- <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="pincode" class="form-label" :value="__('Pincode')" />
                                            <x-text-input id="pincode" class="form-control" type="text" name="pincode" :value="old('pincode', $area->pincode??null)" autofocus autocomplete="pincode" />
                                            <x-input-error :messages="$errors->first('pincode')" class="mt-2" />
                                        </div> -->
                                        <div class="col-xl-4 col-md-6 col-12">
                                            <div class="mb-1">
                                                <x-input-label for="sort_order" class="form-label" :value="__('Sort Order')" />
                                                <x-text-input id="sort_order" class="form-control only_numbers" type="text" name="sort_order" :value="old('sort_order', $area->sort_order??($sortId??''))"/>
                                                <x-input-error :messages="$errors->first('sort_order')" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $area->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('area.index') }}" class="btn btn-outline-secondary">Back</a>
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
                    "city_id": {
                        required : true,
                    },
                },
                messages: {
                    "name": {
                        required: "Please Enter Name",
                    },
                    "city_id": {
                        required: "Please Select City",
                    }
                }
            });
        </script>
    @endpush
@endsection
