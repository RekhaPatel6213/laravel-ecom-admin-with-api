    @extends('layouts.admin')
    @section('content')
        @php
            $type = isset($categorytype) ? __('Edit') : __('Add New');
            $activeStatus = config('constants.ACTIVE');
        @endphp

        <div class="content-wrapper p-0">
            <div class="flash_messages">
                @include('elements.flash_messages')
            </div>

            @include('elements.breadcrumb',  ['route' => route('categorytype.index'), 'parentName' => __('Category Type'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

            <div class="content-body">
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <form method="POST" action="{{ isset($categorytype) ? route('categorytype.update', $categorytype->id) : route('categorytype.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                                    @csrf
                                    @if(isset($categorytype))
                                        @method('PUT')
                                        <input type="hidden" name="categorytype_id" value="{{ $categorytype->id ?? '' }}">
                                    @endif
                                        <div class="row">
                                  <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <x-input-label for="name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                                <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $categorytype->name??null)" required autofocus autocomplete="name" />
                                                <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                            </div>
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <x-input-label for="status" class="form-label" :value="__('Status')" />
                                                <div class="form-check form-switch">
                                                    <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $categorytype->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                                </div>
                                                <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                <a href="{{ route('categorytype.index') }}" class="btn btn-outline-secondary">Back</a>
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

        <script src="{{ asset('admin/app-assets/vendors/js/forms/validation/jquery.validate.js') }}"></script>

        <style type="text/css">
            .error{
                color: red;
            }
        </style>

        <script type="text/javascript">
            $('.FormValidate').validate({
                rules: {
                    "name": {
                        required : true,
                    },
                    "status": {
                        //required : true,
                    },
                },
                messages: {
                    "name": {
                        required: "Please Enter Category Type Name",
                    },
                    "status": {
                        required: "Please Select Status",
                    },
                }
            });
        </script>
    @endsection