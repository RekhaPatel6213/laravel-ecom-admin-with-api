@php 
            $startMapLink = ($route->start_latitude !== null && $route->start_longitude !== null) ? 'https://www.google.com/maps?q=loc:' . $route->start_latitude . ',' . $route->start_longitude : null;
    $endMapLink = ($route->end_latitude !== null && $route->end_longitude !== null) ? 'https://www.google.com/maps?q=loc:' . $route->end_latitude . ',' . $route->end_longitude : null;
@endphp
@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>
        @include('elements.breadcrumb', ['route' => route('route.index'), 'parentName' => __('Route'), 'name' => 'View', 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])
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
                    </div>
                    <div class="bs-stepper-content">
                        <div id="details-vertical" class="content" role="tabpanel"
                            aria-labelledby="details-vertical-trigger">
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
                                        <th> <label class="form-label"
                                                for="vertical-email">{{ ($route->user->firstname ?? '') . ' ' . ($route->user->lastname ?? '') }}</label>
                                        </th>
                                        <th> <label class="form-label"
                                                for="vertical-email">{{ $route->user->email ?? ''}}</label></th>
                                        <th> <label class="form-label"
                                                for="vertical-email">{{ $route->user->mobile ?? ''}}</label></th>
                                    </tr>
                                </tbody>
                            </table>
                            <hr />

                            <hr />
                            <h2>Route Details</h2>
                            <table class="table table-bordered table-hover">
                                <tbody>
                                    <tr>
                                        <th width="25%"></th>
                                        <th width="25%">Date</th>
                                        <th width="25%">Time</th>
                                        <th width="25%">Route</th>
                                    </tr>
                                    <tr>
                                        <th>Route Strat</th>
                                        <th> <label class="form-label"
                                                for="vertical-email">{{ $route->start_time ? \Carbon\Carbon::parse($route->start_time)->format(config('constants.DATE_FORMATE')) : "" }}

                                            </label></th>
                                        <th> <label class="form-label"
                                                for="vertical-email">{{ \Carbon\Carbon::parse($route->start_time)->format(config('constants.TIME_FORMATE'))}}</label>
                                        </th>
                                        <th> <label class="form-label" for="vertical-email">@if($startMapLink !== null) <a
                                        href="{{$startMapLink}}" target="_blank">Map Link</a>@endif</label></th>
                                    </tr>
                                    <tr>
                                        <th>Route End</th>
                                        <th> <label class="form-label"
                                                for="vertical-email">{{$route->end_time !== null ? $route->start_time->format(config('constants.DATE_FORMATE')) : '-'}}</label>
                                        </th>
                                        <th> <label class="form-label"
                                                for="vertical-email">{{ $route->end_time !== null ? \Carbon\Carbon::parse($route->end_time)->format(config('constants.TIME_FORMATE')) : '-'}}</label>
                                        </th>
                                        <th> <label class="form-label" for="vertical-email">@if($endMapLink !== null) <a
                                        href="{{$endMapLink}}" target="_blank">Map Link</a>@endif</label></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="{{ route('route.index') }}" class="btn btn-outline-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /Vertical Wizard -->
        </div>
    </div>
@endsection