@php
    $type = isset($tadatype) ? __('Edit') : __('Add New');
    $activeStatus = config('constants.ACTIVE');
    $isBooleanActive = config('constants.BOOLEANACTIVE');
@endphp

@extends('layouts.admin')
@section('content')

    <div class="content-wrapper p-0">
        @include('elements.breadcrumb', ['route' => route('tadatype.index'), 'parentName' => __('TA/DA Type'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST"
                                    action="{{ isset($tadatype) ? route('tadatype.update', $tadatype->id) : route('tadatype.store')}}"
                                    class="form FormValidate" accept-charset="UTF-8" autocomplete="off">
                                    @csrf
                                    @if(isset($tadatype))
                                        @method('PUT')
                                    @endif
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Name')" /><span
                                                class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name"
                                                :value="old('name', $tadatype->name ?? null)" />
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        {{-- <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="date" class="form-label" :value="__('Date')" />
                                            <div class="form-check form-switch">
                                                <input id="is_date" class="form-check-input" type="checkbox" name="is_date"
                                                    value="{{ $isBooleanActive }}" {{((old('is_date',
                                                    $tadatype->is_date??!$isBooleanActive) === $isBooleanActive) ? 'checked'
                                                : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('is_date')" class="mt-2" />
                                        </div> --}}
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="expense_name" class="form-label" :value="__('Expense Name')" />
                                            <div class="form-check form-switch">
                                                <input id="is_expense_name" class="form-check-input" type="checkbox"
                                                    name="is_expense_name" value="{{ $isBooleanActive }}"
                                                    {{((old('is_expense_name', $tadatype->is_expense_name ?? !$isBooleanActive) === $isBooleanActive) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('is_expense_name')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="amount" class="form-label" :value="__('Expense Amount')" />
                                            <div class="form-check form-switch">
                                                <input id="is_amount" class="form-check-input" type="checkbox"
                                                    name="is_amount" value="{{ $isBooleanActive }}" {{((old('is_amount', $tadatype->is_amount ?? !$isBooleanActive) === $isBooleanActive) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('is_amount')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="photo" class="form-label" :value="__('Photo')" />
                                            <div class="form-check form-switch">
                                                <input id="is_photo" class="form-check-input" type="checkbox"
                                                    name="is_photo" value="{{ $isBooleanActive }}" {{((old('is_photo', $tadatype->is_photo ?? !$isBooleanActive) === $isBooleanActive) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('is_photo')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="location" class="form-label" :value="__('Location')" />
                                            <div class="form-check form-switch">
                                                <input id="is_location" class="form-check-input" type="checkbox"
                                                    name="is_location" value="{{ $isBooleanActive }}" {{((old('is_location', $tadatype->is_location ?? !$isBooleanActive) === $isBooleanActive) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('is_location')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="location" class="form-label" :value="__('From To Location')" />
                                            <div class="form-check form-switch">
                                                <input id="is_from_to_location" class="form-check-input" type="checkbox"
                                                    name="is_from_to_location" value="{{ $isBooleanActive }}"
                                                    {{((old('is_from_to_location', $tadatype->is_from_to_location ?? !$isBooleanActive) === $isBooleanActive) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('is_from_to_location')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="location" class="form-label" :value="__('KM')" />
                                            <div class="form-check form-switch">
                                                <input id="is_km" class="form-check-input" type="checkbox" name="is_km"
                                                    value="{{ $isBooleanActive }}" {{((old('is_km', $tadatype->is_km ?? !$isBooleanActive) === $isBooleanActive) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('is_km')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-2 col-md-6 col-12 mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status"
                                                    value="{{$activeStatus}}" {{((old('status', $tadatype->status ?? $activeStatus) === $activeStatus) ? 'checked' : '')}} />
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if ($type == 'Add New' || ($type == 'Edit' && $tadatype->name !== "Daily Allowance"))
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{ route('tadatype.index') }}"
                                                    class="btn btn-outline-secondary">Back</a>
                                            </div>
                                        @endif

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
                    required: true,
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