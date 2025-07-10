@extends('layouts.admin')
@section('content')

    @php
        $type = isset($product) ? __('Edit') : __('Add New');
        $activeStatus = config('constants.ACTIVE');
        $isParent = isset($product) ? $product->is_parent  : 1;
        $isParent = old('_token') === null ? $isParent : (old('is_parent') != null ? old('is_parent', $isParent) : 0);

        $variantTypes = variant_type();
        $variantValues = variant_value();
        $categoryTypes = getCategoryTypes();
        $zones = getZones();
    @endphp

    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb',  ['route' => route('product.index'), 'parentName' => __('Product'), 'name' => $type, 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <section class="modern-horizontal-wizard">
                <div class="bs-stepper wizard-modern modern-wizard-example">
                    <form method="POST" action="{{ isset($product) ? route('product.update', $product->id) : route('product.store')}}" class="form FormValidate" accept-charset="UTF-8"  autocomplete="off">
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                            <input type="hidden" id="productId" name="product_id" value="{{ $product->id ?? '' }}">
                            <input type="hidden" name="edit_image" class="edit_image" value="{{ $product->image ?? '' }}">
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
                            <!-- <div class="step" data-target="#specification" role="tab" id="specification-trigger">
                                    <button type="button" class="step-trigger">
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title">Specification</span>
                                        </span>
                                    </button>
                                </div>
                                <div class="line">
                                    <i data-feather="chevron-right" class="font-medium-2"></i>
                                </div>
                            </div> -->
                            <?php /*<div class="step" data-target="#productvariant" role="tab" id="productvariant-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Variants</span>
                                    </span>
                                </button>
                            </div>
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>*/ ?>
                            <!-- <div class="step" data-target="#productimages" role="tab" id="productimages-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">Product Multiple Images</span>
                                    </span>
                                </button>
                            </div> -->
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                            <!-- <div class="step" data-target="#seo" role="tab" id="seo-trigger">
                                <button type="button" class="step-trigger">
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title">SEO</span>
                                    </span>
                                </button>
                            </div> -->
                            <div class="line">
                                <i data-feather="chevron-right" class="font-medium-2"></i>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="general" class="content" role="tabpanel" aria-labelledby="general-trigger">
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="parent_category_id" class="form-label" :value="__('Category Type')" /><span class="error">*</span>
                                            <select class="form-select form-control" aria-label="Default select example" name="category_type_id" id="category_type_id">
                                                <option value="">Please Select</option>    
                                                @if($categoryTypes)
                                                    @foreach($categoryTypes as $categoryId => $categoryName)
                                                        <option value="{{$categoryId}}" {{ old('category_type_id', $product->category_type_id??'') == $categoryId ? 'selected' : ''}} >{{$categoryName}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <x-input-error :messages="$errors->first('category_type_id')" class="mt-2" />

                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="category_id" class="form-label" :value="__('Category Name')" /><span class="error">*</span></label>
                                            <select class="form-select form-control" aria-label="Default select example" name="category_id" id="category_id">
                                                <option value="">Please Select</option>    
                                                <?php /*@if($categories)
                                                    @foreach($categories as $categoryId => $categoryName)
                                                        <option value="{{$categoryId}}" {{ old('category_id', $product->category_id??'') == $categoryId ? 'selected' : ''}} >{{$categoryName}}</option>
                                                    @endforeach
                                                @endif*/?>
                                            </select>
                                            <x-input-error :messages="$errors->first('category_id')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="name" class="form-label" :value="__('Product Name')" /><span class="error">*</span>
                                            <x-text-input id="product_name" class="form-control" type="text" name="name" :value="old('name', $product->name??null)"/>
                                            <x-input-error :messages="$errors->first('name')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="code" class="form-label" :value="__('Product Code')" /><span class="error">*</span>
                                            <x-text-input id="code" class="form-control" type="text" name="code" :value="old('code', $product->code??null)"/>
                                            <x-input-error :messages="$errors->first('code')" class="mt-2" />
                                        </div>
                                    </div>
                                   
                                    <!-- <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="gst" class="form-label" :value="__('GST')" /><span class="error">*</span>
                                            <x-text-input id="gst" class="form-control float_numbers" type="text" name="gst" :value="old('gst', $product->gst??null)"/>
                                            <x-input-error :messages="$errors->first('gst')" class="mt-2" />
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="cgst" class="form-label" :value="__('CGST')" /><span class="error">*</span>
                                            <x-text-input id="cgst" class="form-control float_numbers" type="text" name="cgst" :value="old('cgst', $product->cgst??null)"/>
                                            <x-input-error :messages="$errors->first('cgst')" class="mt-2" />
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="sgst" class="form-label" :value="__('SGST')" /><span class="error">*</span>
                                            <x-text-input id="sgst" class="form-control float_numbers" type="text" name="sgst" :value="old('sgst', $product->sgst??null)"/>
                                            <x-input-error :messages="$errors->first('sgst')" class="mt-2" />
                                        </div>
                                    </div> -->
                                </div>

                                <div class="row">
                                    @php 
                                        $vkey = 0;
                                        $variantPrices = (isset($variant) && isset($variant->first()->variant_prices)) ? (Arr::keyBy($variant->first()->variant_prices->toArray(), 'zone_id')) : null;
                                    @endphp

                                    @if($zones)
                                        @foreach($zones as $zoneId => $zoneName)
                                            <div class="col-xl-3 col-md-6 col-12 mb-1">
                                                <label class="form-label" for="first-name-column">{{$zoneName}} MRP <span class="error">*</span></label>
                                                <input class="form-control float_numbers required variant_mrp_{{$zoneName}}" id="variant_mrp_{{$zoneName}}_{{$vkey}}" data-msg-required="{{$zoneName}} MRP is required." name="variant[{{$vkey}}][mrp][{{$zoneId}}]" value="{{isset($variantPrices[$zoneId]['price']) ? $variantPrices[$zoneId]['price'] :''}}" type="text">
                                            </div>
                                        @endforeach
                                    @endif
                                    
                                    <input type="hidden" name="variant[{{$vkey}}][id]" value="{{ ((isset($variant) && $variant->count() > 0) ? ($variant->first()->id) : null)}}">
                                </div>
                                <div class="row">  
                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="stock_status" class="form-label" :value="__('Stock Status')" />
                                            <div class="form-check form-switch">
                                                <input id="stock_status" class="form-check-input" type="checkbox" name="stock_status" value="1" {{((old('stock_status', $product->stock_status??1) === 1) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('stock_status')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="status" class="form-label" :value="__('Status')" />
                                            <div class="form-check form-switch">
                                                <input id="status" class="form-check-input" type="checkbox" name="status" value="{{$activeStatus}}" {{((old('status', $product->status??$activeStatus) === $activeStatus) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('status')" class="mt-2" />
                                        </div>
                                    </div>
                                    <!-- <div class="col-xl-3 col-md-6 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="is_fast_selling" class="form-label" :value="__('Is Best Selling ?')" />
                                            <div class="form-check form-switch">
                                                <input id="is_fast_selling" class="form-check-input" type="checkbox" name="is_fast_selling" value="1" {{((old('is_fast_selling', $product->is_fast_selling??1) === 1) ? 'checked' : '')}}/>
                                            </div>
                                            <x-input-error :messages="$errors->first('is_fast_selling')" class="mt-2" />
                                        </div>
                                    </div> -->
                                </div>
                                <div class="bng_img-box row mx-0">
                                    <div class="col-lg-4 col-md-12 mb-2">
                                        <div class="mb-1">
                                            <x-input-label for="sort_order" class="form-label" :value="__('Sort Order')" />
                                            <x-text-input id="sort_order" class="form-control only_numbers" type="text" name="sort_order" :value="old('sort_order', $product->sort_order??$sortId)"/>
                                            <x-input-error :messages="$errors->first('sort_order')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-12 mb-2">
                                        <div class="row input_image">
                                            <div class="col-12">
                                                <x-input-label for="image" class="form-label" :value="__('Image')" /><span class="error">*</span>
                                                <div class="input-group">
                                                    <x-text-input id="image" class="form-control selected_image" type="text" name="image"  readonly aria-label="Image" aria-describedby="button-image"/>
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="image">Choose File</button>
                                                    </div>
                                                </div>
                                                <x-input-error :messages="$errors->first('image')" class="mt-2" />
                                            </div>
                                            @if(isset($product) && $product->image !== null)
                                                <div class="col-md-2 my-1" id="image-tag">
                                                    <img src="{{ asset('storage/'.$product->image) }}" alt="" style="height:100px; width:100px">
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
                                    <div class="col-lg-4 col-md-12">
                                        <div class="mb-1">
                                            <x-input-label for="alt_tag" class="form-label" :value="__('Alt Tag')" />
                                            <x-text-input id="alt_tag" class="form-control" type="text" name="alt_tag" :value="old('alt_tag', $product->alt_tag??null)"/>
                                            <x-input-error :messages="$errors->first('alt_tag')" class="mt-2" />
                                        </div>
                                    </div>    
                                </div>
                                <!-- <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div class="mb-1">
                                            <x-input-label for="description" class="form-label" :value="__('Description')" />
                                            <x-textarea id="description" class="form-control ckeditor" name="description">{{old('description', $product->description??null)}}</x-textarea>
                                            <x-input-error :messages="$errors->first('description')" class="mt-2" />
                                        </div>
                                    </div>
                                </div> -->
                                <hr class="">
                            </div>

                            <!-- <div id="specification" class="content" role="tabpanel" aria-labelledby="specification-trigger">
                                <div class="row">
                                    <div class="col-md-12 col-12">
                                        <div id="specificationDiv" class="col-md-12 col-12">
                                                @if(isset($product) && $product->specification && count($product->specification))
                                                    @foreach($product->specification as $k => $specification)
                                                        <div class="specification-wrapper">
                                                            <div class="row">
                                                                <div class="col-md-4">
                                                                    <div class="mb-1">
                                                                        <label class="form-label" for="title-column{{$k}}">Specification Title </label>
                                                                        <input type="text" class="form-control" name="specification[{{$k}}][title]" value="{{ $specification['title'] }}" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="mb-1">
                                                                        <label class="form-label" for="description{{$k}}">Specification</label>
                                                                        <textarea name="specification[{{$k}}][description]" id="description{{$k}}" class="form-control ckeditor">{{ $specification['description'] }}</textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2">
                                                                    <button type="button" class="btn btn-danger remove_image waves-effect waves-float waves-light delete-specification mt-2">Remove</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="specification-wrapper">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="title-column1">Specification Title </label>
                                                                    <input type="text" class="form-control" name="specification[1][title]" value="" />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-1">
                                                                    <label class="form-label" for="description1">Specification</label>
                                                                    <textarea name="specification[1][description]" id="description1" class="form-control ckeditor"></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-danger remove_image waves-effect waves-float waves-light delete-specification mt-2">Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        <button id="add-textarea" type="button" class="btn btn-primary mt-3">+ Add Another Specification</button>
                                    </div>
                                </div>
                                <hr class="my-3">
                            </div> -->

                            <?php /*<div id="productvariant" class="content" role="tabpanel" aria-labelledby="productvariant-trigger">
                                <div class="form-group add-front-data">
                                    <label class="form-label" for="first-name-column">Add Variant <span class="error">*</span></label>
                                </div>
                                @if(!empty($variant))
                                    @foreach($variant as $vkey => $vvalue)

                                    @php 
                                        $variantPrices = Arr::keyBy($vvalue->variant_prices->toArray(), 'zone_id');
                                        //echo '<pre>'; print_r($variantPrices); echo '</pre>';
                                    @endphp
                                        <pre>{{--$vvalue->variant_prices--}}</pre>
                                        
                                        <div class="row" id="color-row{{ $vkey }}">
                                            <input type="hidden" name="variant[{{ $vkey }}][id]" value="{{$vvalue['id']}}">
                                            <div class="col-xl-4 col-md-6 col-12 mb-1">
                                                <label class="form-label" for="first-name-column">Product Name <span class="error">*</span></label>
                                                <input class="form-control productName required" data-msg-required="Product Name is required." id="product_name_{{ $vkey }}" name="variant[{{ $vkey }}][product_name]" value="{{$vvalue['product_name']}}" type="text">
                                            </div>
                                            <div class="col-xl-1 col-md-6 col-12 mb-1">
                                                <label class="form-label" for="first-name-column">Variant Type <span class="error">*</span></label>
                                                <select class="form-select variantType required" data-rowid="color-row{{ $vkey }}" data-msg-required="Select Variant Type" name="variant[{{ $vkey }}][variant_type]">
                                                    <option value="">Please Select</option>    
                                                    @if($variantTypes)
                                                        @foreach($variantTypes as $typeId => $typeValue)
                                                            <option value="{{$typeId}}" {{ $vvalue['variant_type'] == $typeId ? 'selected' : ''}}>{{$typeValue}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-xl-1 col-md-6 col-12 mb-1">
                                                <label class="form-label" for="first-name-column">Variant Value <span class="error">*</span></label>
                                                <select class="form-select variantValue required" data-msg-required="Select Variant Value." name="variant[{{ $vkey }}][variant_value]">
                                                    <option selected="selected" value="">Please Select</option>
                                                    @if($variantValues)
                                                        @foreach($variantValues as $valueId => $value)
                                                            <option value="{{$valueId}}" {{ $vvalue['variant_value'] == $valueId ? 'selected' : ''}}>{{$value}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="col-xl-1 col-md-6 col-12 mb-1 d-none">
                                                <label class="form-label" for="first-name-column">Qty <span class="error">*</span></label>
                                                <input class="form-control only_numbers required" data-msg-required="Qty is required." name="variant[{{ $vkey }}][qty]" value="{{$vvalue['qty']}}" type="text">
                                            </div>
                                            <div class="col-xl-1 col-md-6 col-12 mb-1 d-none">
                                                <label class="form-label" for="first-name-column">MRP <span class="error">*</span></label>
                                                <input class="form-control float_numbers required variant_mrp" id="variant_mrp_{{ $vkey }}" data-msg-required="MRP is required." name="variant[{{ $vkey }}][mrp]" value="{{$vvalue['mrp']}}" type="text">
                                            </div>
                                            <div class="col-xl-1 col-md-6 col-12 mb-1 d-none">
                                                <label class="form-label" for="first-name-column">Selling Price <span class="error">*</span></label>
                                                <input class="form-control float_numbers required variant_sp" id="variant_sp_{{ $vkey }}" data-msg-required="Selling Price is required." name="variant[{{ $vkey }}][sp]" value="{{$vvalue['sp']}}" type="text">
                                            </div>

                                            <div class="col-xl-1 col-md-6 col-12 mb-1">
                                                <label class="form-label" for="first-name-column">Per Case Qty<span class="error">*</span></label>
                                                <input class="form-control only_numbers required" data-msg-required="Qty is required." name="variant[{{ $vkey }}][case_quantity]" value="{{$vvalue['qty']}}" type="text">
                                            </div>
                                            @if($zones)
                                                @foreach($zones as $zoneId => $zoneName)
                                                    <div class="col-xl-1 col-md-6 col-12 mb-1">
                                                        <label class="form-label" for="first-name-column">{{$zoneName}} MRP <span class="error">*</span></label>
                                                        <input class="form-control float_numbers required variant_mrp_{{$zoneName}}" id="variant_mrp_{{$zoneName}}_{{ $vkey }}" data-msg-required="{{$zoneName}} MRP is required." name="variant[{{ $vkey }}][mrp][{{$zoneId}}]" value="{{$variantPrices[$zoneId]['price']??''}}" type="text">
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="col-xl-1 col-md-6 col-12">
                                                <div class="form-group">
                                                    <a href="javascript:;" class="btn btn-icon-only green" onclick="$('#color-row{{ $vkey }}').remove();">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="form-group add-new-button">
                                    <a href="javascript:void(0);" class="btn btn-main-image-only green" onclick='add_new_variant()'><i class="fa fa-plus"></i></a>
                                </div>
                            </div>*/ ?>

                            <!-- <div id="productimages" class="content" role="tabpanel" aria-labelledby="productimages-trigger">
                                <div class="form-group add-front-data">
                                    <label class="form-label" for="first-name-column">Add Product Images</label>
                                </div>
                                @if(!empty($product_image))
                                        @foreach($product_image as $pikey => $pivalue)
                                            <div class="row imageRow" id="image-row{{ $pikey }}">
                                                <div class="row">
                                                    <div class="mb-1 col-lg-6 col-xl-6 col-12 mb-0">
                                                        <div class="row input_image">
                                                            <div class="col-md-6">
                                                                <label for="image_label">Image <span class="error">* (Image Size must be 700px * 700px)</span></label>
                                                                <div class="input-group">
                                                                    <input type="text" name="multiImage[{{ $pikey }}][image]" id="pro_images_{{ $pikey }}" class="form-control selected_image" readonly>
                                                                    <input type="hidden" name="multiImage[{{ $pikey }}][edit_image]" value="{{ $pivalue['image'] }}">
                                                                    <div class="input-group-append text-xl-end text-md-end">
                                                                        <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="pro_images_{{ $pikey }}">Choose File</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(!empty($pivalue['image']))
                                                                <div class="col-xl-4 col-md-4 col-12 text-center my-2" id="image-tag">
                                                                    <img src="{{ asset('storage/'.$pivalue['image']) }}" alt="" style="height:100px; width:100px">
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="mb-1 col-lg-6 col-xl-3 col-12 mb-0">
                                                        <label class="form-label" for="first-name-column">Sort Order</label>
                                                        <input type="text" name="multiImage[{{ $pikey }}][sort_order]" class="form-control numeral-mask only_numbers" value="{{ $pivalue['sort_order'] }}">
                                                    </div>
                                                    <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                                        <div class="form-group">
                                                            <a href="javascript:;" class="btn btn-icon-only green" onclick="$('#image-row{{ $pikey }}').remove();">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><hr>
                                        @endforeach
                                    @endif
                                <div class="form-group add-new-button">
                                    <a href="javascript:void(0);" class="btn btn-main-image-only green" onclick='add_new_image()'><i class="fa fa-plus"></i></a>
                                </div>
                            </div> -->

                            <!-- <div id="seo" class="content" role="tabpanel" aria-labelledby="seo-trigger">
                                @include('elements.seo_form')
                            </div> -->

                            <div class="row mb-1">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                    <a href="{{ route('product.index') }}" class="btn btn-outline-secondary">Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script type="text/javascript">
        var new_option = $('#productvariant .row').length; //0;
        function add_new_variant()
        {
            var newoptionhtml = `<div class="row" id="color-row` + new_option + `">
                                <div class="col-xl-4 col-md-6 col-12 mb-1">
                                    <label class="form-label" for="first-name-column">Product Name <span class="error">*</span></label>
                                    <input class="form-control productName required" data-msg-required="Product Name is required." id="product_name_`+ new_option +`" name="variant[`+ new_option +`][product_name]" type="text">
                                </div>
                                <div class="col-xl-1 col-md-6 col-12 mb-1">
                                    <label class="form-label" for="first-name-column">Variant Type <span class="error">*</span></label>
                                    <select class="form-select variantType required" data-rowid="color-row` + new_option + `" data-msg-required="Select Variant Type" name="variant[`+ new_option +`][variant_type]">
                                        <option value="">Please Select</option>    
                                        @if($variantTypes)
                                            @foreach($variantTypes as $typeId => $typeValue)
                                                <option value="{{$typeId}}">{{$typeValue}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xl-1 col-md-6 col-12 mb-1">
                                    <label class="form-label" for="first-name-column">Variant Value <span class="error">*</span></label>
                                    <select class="form-select variantValue required" data-msg-required="Select Variant Value." name="variant[`+ new_option +`][variant_value]">
                                        <option selected="selected" value="">Please Select</option>
                                        @if($variantValues)
                                            @foreach($variantValues as $valueId => $value)
                                                <option value="{{$valueId}}">{{$value}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-xl-1 col-md-6 col-12 mb-1 d-none">
                                    <label class="form-label" for="first-name-column">Qty <span class="error">*</span></label>
                                    <input class="form-control only_numbers required" data-msg-required="Qty is required." name="variant[`+ new_option +`][qty]" type="text">
                                </div>
                                <div class="col-xl-1 col-md-6 col-12 mb-1 d-none">
                                    <label class="form-label" for="first-name-column">MRP <span class="error">*</span></label>
                                    <input class="form-control float_numbers required variant_mrp" id="variant_mrp_`+ new_option +`" data-msg-required="MRP is required." name="variant[`+ new_option +`][mrp]" type="text">
                                </div>
                                <div class="col-xl-1 col-md-6 col-12 mb-1 d-none">
                                    <label class="form-label" for="first-name-column">Selling Price <span class="error">*</span></label>
                                    <input class="form-control float_numbers required variant_sp" id="variant_sp_`+ new_option +`" data-msg-required="Selling Price is required." name="variant[`+ new_option +`][sp]" type="text">
                                </div>

                                <div class="col-xl-1 col-md-6 col-12 mb-1">
                                    <label class="form-label" for="first-name-column">Per Case Qty<span class="error">*</span></label>
                                    <input class="form-control only_numbers required variant_case_quantity" id="variant_case_quantity_`+ new_option +`" data-msg-required="Per Case Qty is required." name="variant[`+ new_option +`][case_quantity]" type="text">
                                </div>
                                @if($zones)
                                    @foreach($zones as $zoneId => $zoneName)
                                    <div class="col-xl-1 col-md-6 col-12 mb-1">
                                        <label class="form-label" for="first-name-column">{{$zoneName}} MRP <span class="error">*</span></label>
                                        <input class="form-control float_numbers required variant_mrp_{{$zoneName}}" id="variant_mrp_{{$zoneName}}_`+ new_option +`" data-msg-required="{{$zoneName}} MRP is required." name="variant[`+ new_option +`][mrp][{{$zoneId}}]" type="text">
                                    </div>
                                    @endforeach
                                @endif
                                <div class="col-xl-1 col-md-6 col-12">
                                    <div class="form-group">
                                        <a href="javascript:;" class="btn btn-icon-only green" onclick="$(\'#color-row` + new_option + `\').remove();">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>`;

            $('#productvariant .add-new-button').before(newoptionhtml);

            $("#product_name_"+new_option).val($("#product_name").val());
            new_option++;
        }

        var new_image = 0
        function add_new_image()
        {
            var newimagehtml = `<div class="row" id="image-row` + new_image + `">
                                    <div class="row">
                                        <div class="mb-1 col-lg-6 col-xl-6 col-12 mb-0">
                                            <div class="row input_image">
                                                <div class="col-md-8">
                                                    <label for="image_label">Image <span class="error">* (Image Size must be 700px * 700px)</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="multiImage[` + new_image + `][image]" id="pro_images_` + new_image + `" class="form-control selected_image required" readonly>
                                                        <div class="input-group-append text-xl-end text-md-end">
                                                            <button class="btn btn-outline-secondary choose-file-button" type="button" data-id="pro_images_` + new_image + `">Choose File</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-2">
                                                    <label for="image_label"></label>
                                                    <div class="input-group-append text-xl-end text-md-end ">
                                                        <button class="btn btn-danger remove_image" type="button">Delete</button>
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="mb-1 col-lg-6 col-xl-3 col-12 mb-0">
                                            <label class="form-label" for="first-name-column">Sort Order</label>
                                            <input type="text" name="multiImage[` + new_image + `][sort_order]" class="form-control numeral-mask only_numbers" value="` + (new_image + 1) + `">
                                        </div>
                                        <div class="mb-3 col-lg-12 col-xl-2 col-12 d-flex align-items-center mb-0">
                                            <div class="form-group">
                                                <a href="javascript:;" class="btn btn-icon-only green" onclick="$(\'#image-row` + new_image + `\').remove();">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div><hr>
                                </div>`;
            $('#productimages .add-new-button').before(newimagehtml);

            new_image++;
        }

        var specCounter = parseInt("{{ isset($product) && $product->specification ? count($product->specification) : 1 }}");
        $('#add-textarea').click(function() {
            var textareaId = 'description' + specCounter;
            var newTextarea = `<div class="specification-wrapper">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-1">
                                        <label class="form-label" for="title-column${specCounter}">Specification  Title </label>
                                        <input type="text" class="form-control" name="specification[${specCounter}][title]" value="" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-1">
                                        <label class="form-label" for="${textareaId}">Specification </label>
                                        <textarea name="specification[${specCounter}][description]" id="${textareaId}" class="form-control ckeditor"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove_image waves-effect waves-float waves-light delete-specification mt-2">Remove</button>
                                </div>
                            </div>
                    </div>`;

            $('#specificationDiv').append(newTextarea);
            CKEDITOR.replace(textareaId); // Initialize CKEditor for the new textarea

            specCounter++;
        });

        $('#specificationDiv').on('click', '.delete-specification', function() {
            if (confirm("Are you sure to delete ?")) {
                $(this).closest('.specification-wrapper').remove();
            }
        });
    </script>
@endsection

@push('script')
    <script type="text/javascript">

        $(document).on('change', '#category_type_id', function(){
            getCategory($(this).val(), {{$product->category_id??null}});
        });

        $(document).ready(function() {
            if("{{$type}}" === 'Edit'){
                getCategory("{{$product->category_type_id??null}}", "{{$product->category_id??null}}");
            }
        });

        $('.FormValidate').validate({
            rules: {
                "category_type_id": {
                    required: true,
                },
                "category_id": {
                    required: true,
                },
                "name": {
                    required: true,
                },
                "code": {
                    required: true,
                },
                
                "image": {
                    required: "{{!isset($product) ? true : false}}",
                    imageExtension: "jpg|jpeg|png"
                },
                "status": {
                    required: true,
                },
            },
            messages: {
                "category_type_id": {
                    required: "Please Select Category Type",
                },
                "category_id": {
                    required: "Please Select Category Name",
                },
                "name": {
                    required: "Please Enter Product Name",
                },
                "code": {
                    required: "Please Enter Product Code",
                },
                
                "image": {
                    required: "Please Select Image",
                    imageExtension: "Only .jpg, .jpeg, .png Allowed"
                },
                "status": {
                    required: "Please Select Status",
                },
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

        @if(isset($product))
            $(".remove_image").click(function() {
                var imgTable = $(this).closest('.input_image');
                imgTable.find('.selected_image').val('');
                column_name = imgTable.find('.selected_image').attr('name');

                if (imgTable.find('.selected_image').val() == '') {
                    $.ajax({
                        url: "{{ route('product.delete.image') }}",
                        type: "POST",
                        data: {
                            "id": "{{ $product->id }}",
                            'column_name': column_name,
                            "_token": "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.status == true) {
                                imgTable.find('#image-tag').remove();
                                imgTable.find('.edit_image').val('');
                               $('.edit_image').val('');
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