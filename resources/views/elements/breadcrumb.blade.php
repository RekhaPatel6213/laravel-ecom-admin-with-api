<div class="content-header row">
    <div class="col-12">
        <div class="breadcrumb-wrapper-box without-btn-breadcrumb mt-2 mb-2">
            <div class="row align-items-center">
                <div class="content-header-left col-xl-9 col-md-12 col-12">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">{{$parentName??$name}}</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                    @if($parentName !== null && $route !== null)
                                        <li class="breadcrumb-item"><a href="{{ $route }}">{{$parentName}}</a></li>
                                    @endif
                                    <li class="breadcrumb-item active"><a href="javascript:void(0);">{{$name}}</a></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                @if($isAction === true)
                    <div class="content-header-right text-md-end text-end col-xl-3 col-md-12 col-sm-12 col-12 d-md-block ">
                        <div class="breadcrumb-right">
                            <div class="dropdown">
                                @if($isDelete === true)
                                    <a href="javascript:void(0);" class="btn btn-outline-danger delete_records"><i class="fa fa-trash-o"></i>&nbsp;Delete</a>
                                @endif

                                @if(isset($isImport) && $isImport === true)
                                    <a href="javascript:void(0);" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-default"><i class="fa fa-upload"></i>&nbsp;Import </a>
                                @endif

                                <a href="{{ $newRoute }}" class="btn btn-outline-primary"><i class="fa fa-plus"></i>&nbsp;</i>{{ $newName }}</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>