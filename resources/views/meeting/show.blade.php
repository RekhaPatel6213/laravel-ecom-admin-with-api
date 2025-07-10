@php 
    $startMapLink = ($meeting->start_latitude !== null && $meeting->start_longitude !== null) ? 'https://www.google.com/maps?q=loc:'.$meeting->start_latitude.','.$meeting->start_longitude : null;
    $endMapLink = ($meeting->end_latitude !== null && $meeting->end_longitude !== null) ? 'https://www.google.com/maps?q=loc:'.$meeting->end_latitude.','.$meeting->end_longitude : null;
@endphp
@extends('layouts.admin')
    @section('content')
        <div class="content-wrapper p-0">
            <div class="flash_messages">
                @include('elements.flash_messages')
            </div>
            @include('elements.breadcrumb',  ['route' => route('meeting.index'), 'parentName' => __('Meeting'), 'name' => 'View', 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])
            <div class="content-body">
                <!-- Vertical Wizard -->
                <section class="vertical-wizard order_ver">
                    <div class="bs-stepper vertical vertical-wizard-example">
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#details-vertical" role="tab" id="details-vertical-trigger">
                                <button type="button" class="step-trigger p-0 w-100">
                                    <span class="bs-stepper-label w-100">
                                        <span class="bs-stepper-title cb">Details</span>
                                    </span>
                                </button>
                            </div>
                            <div class="step" data-target="#comment-vertical" role="tab" id="comment-vertical-trigger">
                                <button type="button" class="step-trigger p-0 w-100">
                                    <span class="bs-stepper-label w-100">
                                        <span class="bs-stepper-title cb">Comments</span>
                                    </span>
                                </button>
                            </div>
                            <div class="step" data-target="#attachments-vertical" role="tab" id="attachments-vertical-trigger">
                                <button type="button" class="step-trigger p-0 w-100">
                                    <span class="bs-stepper-label w-100">
                                        <span class="bs-stepper-title cb">Attachments</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <div id="details-vertical" class="content" role="tabpanel" aria-labelledby="details-vertical-trigger">
                                <h2>Person Details</h2>
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th width="25%"></th>
                                            <th width="25%">Name</th>
                                            <th width="25%">Email</th>
                                            <th width="25%">mobile</th>
                                        </tr>
                                        <tr>
                                            <th>Sales Person</th>
                                            <th> <label class="form-label" for="vertical-email">{{ ($meeting->user->firstname??'').' '.($meeting->user->lastname??'') }}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Sales Person Email</th> -->
                                            <th> <label class="form-label" for="vertical-email">{{ $meeting->user->email??''}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Sales Person Contact</th> -->
                                            <th>  <label class="form-label" for="vertical-email">{{ $meeting->user->mobile??''}}</label></th>
                                        </tr>
                                        <tr>
                                            <th>Distributor</th>
                                            <th> <label class="form-label" for="vertical-email">{{ ($meeting->distributor->firstname??'').' '.($meeting->distributor->lastname??'') }}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Distributor Email</th> -->
                                            <th> <label class="form-label" for="vertical-email">{{ $meeting->distributor->email??''}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th> Distributor Contact</label> </th> -->
                                            <th>  <label class="form-label" for="vertical-email">{{ $meeting->distributor->mobile??''}}</label></th>
                                        </tr>

                                        <tr>
                                            <th>Retailer</th>
                                            <th> <label class="form-label" for="vertical-email">{{$meeting->shop->name??''}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Retailer Email</th> -->
                                            <th> <label class="form-label" for="vertical-email">{{ $meeting->shop->email??''}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th> Retailer Contact</th> -->
                                            <th>  <label class="form-label" for="vertical-email">{{ $meeting->shop->mobile??''}}</label></th>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                                <hr/>
                                <h2>Meeting Type</h2>
                                <table class="table table-bordered table-hover">
                                    <tr>
                                            <th width="25%">Meeting Type</th>
                                            <th><label class="form-label" for="vertical-email">{{$meeting->type->name??"-"}}</label></th>
                                    </tr>
                                </table>
                                <hr/>
                                <h2>Meeting Details</h2>
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                        <tr>
                                            <th width="25%"></th>
                                            <th width="25%">Date</th>
                                            <th width="25%">Time</th>
                                            <th width="25%">Route</th>
                                        </tr>
                                        <tr>
                                            <th>Meeting Strat</th>
                                            <th> <label class="form-label" for="vertical-email">{{$meeting->meeting_date->format(config('constants.DATE_FORMATE'))}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Meeting End</th> -->
                                            <th> <label class="form-label" for="vertical-email">{{ \Carbon\Carbon::parse($meeting->start_time)->format(config('constants.TIME_FORMATE'))}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Sales Person Contact</th> -->
                                            <th>  <label class="form-label" for="vertical-email">@if($startMapLink !== null) <a href="{{$startMapLink}}" target="_blank">Map Link</a>@endif</label></th>
                                        </tr>
                                        <tr>
                                            <th>Meeting End</th>
                                            <th> <label class="form-label" for="vertical-email">{{$meeting->end_time !== null ? $meeting->meeting_date->format(config('constants.DATE_FORMATE')): '-'}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th>Distributor Email</th> -->
                                            <th> <label class="form-label" for="vertical-email">{{ $meeting->end_time !== null ? \Carbon\Carbon::parse($meeting->end_time)->format(config('constants.TIME_FORMATE')): '-'}}</label></th>
                                        <!-- </tr>
                                        <tr>
                                            <th> Distributor Contact</label> </th> -->
                                            <th>  <label class="form-label" for="vertical-email">@if($endMapLink !== null) <a href="{{$endMapLink}}" target="_blank">Map Link</a>@endif</label></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div id="comment-vertical" class="content" role="tabpanel" aria-labelledby="comment-vertical-trigger">
                               {!!$meeting->comments!!}
                               <hr/>
                            </div>

                            <div id="attachments-vertical" class="content" role="tabpanel" aria-labelledby="attachments-vertical-trigger">
                                <img src="{{ asset('storage/'.$meeting->attachment1) }}" alt="" style="height:200px; width:200px">
                                <img src="{{ asset('storage/'.$meeting->attachment2) }}" alt="" style="height:200px; width:200px">
                                <img src="{{ asset('storage/'.$meeting->attachment3) }}" alt="" style="height:200px; width:200px">
                                <hr/>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <a href="{{ route('meeting.index') }}" class="btn btn-outline-secondary">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- /Vertical Wizard -->
            </div>
        </div>
    @endsection