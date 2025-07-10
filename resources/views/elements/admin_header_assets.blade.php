<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

<!-- BEGIN: Vendor CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/vendors.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/forms/wizard/bs-stepper.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/forms/select/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/animate/animate.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/sweetalert2.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/charts/apexcharts.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/vendors/css/extensions/toastr.min.css') }}">
<!-- END: Vendor CSS-->

<!-- BEGIN: Theme CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/bootstrap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/bootstrap-extended.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/colors.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/components.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/themes/dark-layout.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/themes/bordered-layout.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/themes/semi-dark-layout.css') }}">

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/core/menu/menu-types/vertical-menu.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/plugin/font-awesome/css/font-awesome.min.css') }}">
<!-- END: Page CSS-->

<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/fancybox.min.css') }}">

<!-- BEGIN: Page CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/plugins/forms/pickers/form-flat-pickr.css') }}">
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
<link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/style.css') }}">
<!-- END: Custom CSS-->

<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/plugins/forms/form-wizard.css') }}">

<script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
<!-- BEGIN: Vendor JS-->
<script src="{{ asset('admin/app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->
<link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">
<link rel="stylesheet" href="{{ asset('admin/app-assets/css/jquery-ui.css') }}" />
<script src="{{ asset('admin/app-assets/js/jquery-ui.js') }}"></script>

<script type="text/javascript">
    var token = "{{ csrf_token() }}";
    var STATEURL = "{{ route('state.state_list') }}";
    var CITYURL = "{{ route('city.city_list') }}";
    var AREAURL = "{{ route('area.area_list') }}";
    var CATEGORYURL = "{{ route('category.category_list') }}";
    var DISRIBUTORURL = "{{ route('distributor.distributor_list') }}";
    var ACTIVE = "{{ config('constants.ACTIVE') }}";
    var INACTIVE = "{{ config('constants.INACTIVE') }}";
</script>