@extends('layouts.admin')
@section('content')
    @php
        $type = isset($meetingtype) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
    @endphp
    <div class="content-wrapper p-0">
        @include('elements.breadcrumb',  ['route' => route('meetingtype.index'), 'parentName' => __('Meeting Type'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])
        <div class="content-body">
            <section id="multiple-column-form">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="POST" action="{{ isset($meetingtype) ? route('meetingtype.update', $meetingtype->id) : route('meetingtype.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($meetingtype))
                                        @method('PUT')
                                    @endif

                                    <input type="hidden" name="meetingtype_id" value="{{ $meetingtype->id ?? '' }}">
                                    <div class="row">
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $meetingtype->name??null)" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                        <div class="col-xl-3 col-md-6 col-12 mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $meetingtype->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <a href="{{ route('meetingtype.index') }}" class="btn btn-outline-secondary">Back</a>
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
                    }
                },
                messages: {
                    "name": {
                        required: "Please Enter Name",
                    }
                }
            });
        </script>
    @endpush
@endsection