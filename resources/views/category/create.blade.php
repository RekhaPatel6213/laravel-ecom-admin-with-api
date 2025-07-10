@extends('layouts.admin')
@section('content')
    @php
        $type = isset($category) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
        $isParent = isset($category) ? $category->is_parent  : 1;
        $isParent = old('_token') === null ? $isParent : (old('is_parent') != null ? old('is_parent', $isParent) : 0);

        $categoryTypes = getCategoryTypes();
    @endphp
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb',  ['route' => route('category.index'), 'parentName' => __('Category'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <form method="POST" action="{{ isset($category) ? route('category.update', $category->id) : route('category.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                        @csrf
                        @if(isset($category))
                            @method('PUT')
                            <input type="hidden" name="category_id" value="{{ $category->id ?? '' }}">
                            <input type="hidden" name="edit_image" class="edit_image" value="{{ $category->image ?? '' }}">
                            <input type="hidden" name="edit_app_image" class="edit_image" value="{{ $category->app_image ?? '' }}">
                        @endif

                        <div class="bs-stepper-header">
                            <div class="step" data-target="#general" role="tab" id="general-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">General</span>
                                    </span>
                                </button>
                            </div>

                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <div class="step" data-target="#seo" role="tab" id="seo-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">SEO</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="general" class="content" role="tabpanel" aria-labelledby="general-trigger">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Name')" /><span class="error">*</span>
                                            <x-text-input id="name" class="form-control" type="text" name="name" :value="old('name', $category->name??null)"/>
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="parent_category_id" class="form-label" :value="__('Category Type')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="category_type_id" id="category_type_id">
                                                <option value="">Please Select</option>    
                                                @if($categoryTypes)
                                                    @foreach($categoryTypes as $categoryId => $categoryName)
                                                        <option value="{{$categoryId}}" {{ old('category_type_id', $category->category_type_id??'') == $categoryId ? 'selected' : ''}} >{{$categoryName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('category_type_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12" id="parentCategory">
                                        <div class="mb-1">
                                            <x-input-label for="parent_category_id" class="form-label" :value="__('Parent Category')" />
                                            <select class="form-select form-control" aria-label="Default select example" name="parent_category_id" id="parent_category_id">
                                                <option value="">Please Select</option>    
                                                @if($categories)
                                                    @foreach($categories as $categoryId => $categoryName)
                                                        <option value="{{$categoryId}}" {{ old('parent_category_id', $category->parent_category_id??'') == $categoryId ? 'selected' : ''}} >{{$categoryName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('parent_category_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="sort_order" class="form-label" :value="__('Sort Order')" />
                                            <x-text-input id="sort_order" class="form-control only_numbers" type="text" name="sort_order" :value="old('sort_order', $category->sort_order??$sortId)"/>
                                            <x-input-error :messages="$errors->first('sort_order')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="is_parent" class="form-label" :value="__('Is Parent')" />
                                            <div class="form-check form-switch">
                                                <input id="is_parent" class="form-check-input" type="checkbox" name="is_parent" value="1" {{$isParent ? 'checked' : ''}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('is_parent')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $category->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="description" class="form-label" :value="__('Description')" />
                                            <x-textarea id="description" class="form-control ckeditor" name="description">{{old('description', $category->description??null)}}</x-textarea>
                                            <x-input-error :messages="$errors->first('description')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1" id="category_image">
                                    <div class="col-xl-8 col-md-6 col-12">
                                        <div class="row input_image">
                                            <div class="col-md-8">
                                                <x-input-label for="image" class="form-label" :value="__('Image')" /><span class="error textchange"></span>
                                                <div class="input-group">
                                                    <x-text-input id="image" class="form-control selected_image" type="text" name="image"  readonly aria-label="Image" aria-describedby="button-image"/>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="image">Choose File</button>
                                                    </div>
                                                </div>
                                                <x-input-error :messages="$errors->first('image')" class="mt-2" />
                                            </div>
                                            @if(isset($category) && $category->image !== null)
                                                <div class="col-md-2 my-1" id="image-tag">
                                                    <img src="{{ asset('storage/'.$category->image) }}" alt="" style="height:100px; width:100px">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="image_label"></label>
                                                    <div class="input-group-append text-xl-end text-md-end">
                                                        <button class="btn btn-danger remove_image" type="button">Delete</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1" id="icon_image">
                                    <div class="col-xl-8 col-md-6 col-12">
                                        <div class="row input_image">
                                            <div class="col-md-8">
                                                <x-input-label for="app_image" class="form-label" :value="__('App Image')" /><span class="error textchange"></span>
                                                <div class="input-group">
                                                    <x-text-input id="app_image" class="form-control selected_image" type="text" name="app_image"  readonly aria-label="Image" aria-describedby="button-image"/>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="app_image">Choose File</button>
                                                    </div>
                                                </div>
                                                <x-input-error :messages="$errors->first('app_image')" class="mt-2" />
                                            </div>
                                            @if(isset($category) && $category->app_image !== null)
                                                <div class="col-md-2 my-1" id="image-tag">
                                                    <img src="{{ asset('storage/'.$category->app_image) }}" alt="" style="height:100px; width:100px">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="image_label"></label>
                                                    <div class="input-group-append text-xl-end text-md-end">
                                                        <button class="btn btn-danger remove_image" type="button">Delete</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="seo" class="content" role="tabpanel" aria-labelledby="seo-trigger">
                                @include('elements.seo_form')
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">Back</a>
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
        $('.FormValidate').validate({
            rules: {
                "name": {
                    required: true,
                },
                "category_type_id": {
                    required: true,
                },
                "image": {
                    imageExtension: "jpg|jpeg|png|webp"
                },
                "app_image": {
                    imageExtension: "jpg|jpeg|png|webp"
                }
            },
            messages: {
                "name": {
                    required: "Please Enter Name",
                },
                "category_type_id": {
                    required: "Please Select Category Type",
                }
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('body').on('click', '.choose-file-button', function(event) {
                event.preventDefault();
                inputId = $(this).data('id');
                window.open('/file-manager/fm-button', 'fm', 'width=1400,height=800');
            });
        });

        // input
        let inputId = '';

        // set file link
        function fmSetLink($url) {
            document.getElementById(inputId).value = $url;
        }
        
        @if(isset($category))
            $(".remove_image").click(function() {
                var imgTable = $(this).closest('.input_image');
                imgTable.find('.selected_image').val('');
                column_name = imgTable.find('.selected_image').attr('name');

                if (imgTable.find('.selected_image').val() == '') {
                    $.ajax({
                        url: "{{ route('category.delete.image') }}",
                        type: "POST",
                        data: {
                            "id": "{{ $category->id }}",
                            'column_name': column_name,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status == true) {
                                imgTable.find('#image-tag').remove();
                                imgTable.find('.edit_image').val('');
                            }
                        }
                    });
                } else {
                    imgTable.find('.selected_image').val('');
                }
            });
        @endif
    </script>
@endpush