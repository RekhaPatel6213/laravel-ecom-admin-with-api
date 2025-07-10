    @if ($success_message = Session::get('success_message'))
        <!-- <div class="content-header">
            <div class="alert alert-success" role="alert">
                <div class="alert-body">{{ $success_message }}</div>
            </div>
        </div> -->
        <div class="alert alert-success alert-dismissible auto_hide fade show" role="alert">
            <div class="alert-body">{{ $success_message }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($error_message = Session::get('error_message'))
        <!-- <div class="content-header">
            <div class="alert alert-danger" role="alert">
                <div class="alert-body">{{ $error_message }}</div>
            </div>
        </div> -->
        <div class="alert alert-danger alert-dismissible auto_hide fade show" role="alert">
            <div class="alert-body">{{ $error_message }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <script type="text/javascript">
        /* var SUCCESS_MESSAGE = `<div class="content-header">
                                    <div class="alert alert-success" role="alert">
                                        <div class="alert-body">FLASH_MESSAGE</div>
                                    </div>
                                </div>`;

        var ERROR_MESSAGE = `<div class="content-header">
                                    <div class="alert alert-danger" role="alert">
                                        <div class="alert-body">FLASH_MESSAGE</div>
                                    </div>
                                </div>`; */

        var SUCCESS_MESSAGE = `<div class="alert alert-success alert-dismissible auto_hide fade show" role="alert">
                                    <div class="alert-body">FLASH_MESSAGE</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`;

        var ERROR_MESSAGE = `<div class="alert alert-danger alert-dismissible auto_hide fade show" role="alert">
                                <div class="alert-body">FLASH_MESSAGE</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
    </script>