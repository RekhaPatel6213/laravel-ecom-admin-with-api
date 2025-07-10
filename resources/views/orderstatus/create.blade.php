@php
    $type = isset($orderstatus) ? __('Edit') : __('Add New');
    $activeStatus = config('constants.ACTIVE');
@endphp

@extends('layouts.admin')
@section('content')

    <div class="content-wrapper p-0">
        @include('elements.breadcrumb', ['route' => route('orderstatus.index'), 'parentName' => __('Order Status'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($orderstatus) ? route('orderstatus.update', $orderstatus->id) : route('orderstatus.store')}}" class="form FormValidate" accept-charset="UTF-8" autocomplete="off">
                                    @csrf
                                    @if(isset($orderstatus))
                                        @method('PUT')
                                    @endif
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="order_status_name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="order_status_name" class="form-control" type="text" name="order_status_name" :value="old('order_status_name', $orderstatus->order_status_name??null)" />
                                            <x-input-error :messages="$errors->first('order_status_name')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $orderstatus->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('orderstatus.index') }}" class="btn btn-outline-secondary">Back</a>
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
        $('.FormValidate').validate({
            rules: {
                "order_status_name": {
                    required : true,
                }
            },
            messages: {
                "order_status_name": {
                    required: "Please Enter Order Status Name",
                }
            }
        });
    </script>
@endpush