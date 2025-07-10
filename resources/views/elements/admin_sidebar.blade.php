    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item m-auto">
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <span class="brand-logo">
                        <img src="{{ asset(getImage(getSettingData('company_logo'))) }}" alt="">
                    </span>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <!-- <i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i> -->
                </a>
            </li>
        </ul>
    </div>

    <div class="shadow-bottom"></div>
    @php
        $action = request()->route()->getAction();

        //echo '<pre>'; print_r($action);
        $controller_action = explode('@', class_basename($action['controller']));
        //dd($controller_action);
        $current_controller = $controller_action[0];
        $current_action = $controller_action[1]??null;
    @endphp

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @php
                $controller = array('UserController');
                $action = array('dashboard', 'index');
            @endphp
            <li class="nav-item @if($current_controller == 'DashboardController') active @endif">
                <a class="d-flex align-items-center" href="{{ route('dashboard') }}"><i data-feather="grid"></i>
                    <span class="menu-title text-truncate" data-i18n="Dashboards">Dashboards</span>
                </a>
            </li>

            @php
                $controller = array('CategoryTypeController','VariantTypeController','VariantValueController','CountryController', 'StateController', 'CityController', 'AreaController', 'MeetingTypeController', 'DesignationController','ZoneController');
                $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller,$controller)?'has-sub menu-collapsed-open':'' }}">
                <a class="d-flex align-items-center" href="#"><i data-feather="box"></i>
                    <span class="menu-title text-truncate" data-i18n="Master">Master</span>
                </a>
                <ul class="menu-content">
                    <li class="@if($current_controller == 'CategoryTypeController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('categorytype.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Category Type</span>
                        </a>
                    </li>
                    <?php /*<li class="@if($current_controller == 'VariantTypeController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('varianttype.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Variant type</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'VariantValueController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('variantvalue.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Variant Value</span>
                        </a>
                    </li>*/ ?>
                    <li class="@if($current_controller == 'CountryController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('country.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Country</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'StateController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('state.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">State</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'CityController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('city.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">City</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'AreaController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('area.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Route</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'MeetingTypeController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('meetingtype.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Meeting Type</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'DesignationController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('designation.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Designation</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'ZoneController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('zone.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Zone</span>
                        </a>
                    </li>
                </ul>
            </li>

            @php
                $controller = array('UserController', 'DistributorController','ShopController');
                $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller, $controller)?'has-sub menu-collapsed-open':'' }}">
                <a class="d-flex align-items-center" href="#"><i data-feather="bar-chart"></i>
                    <span class="menu-title text-truncate" data-i18n="Reports">User Section</span>
                </a>
                <ul class="menu-content">
                    <li class="@if($current_controller == 'UserController') active @endif">
                        <a class="d-flex align-items-center" href="{{route('user.index') }}">
                            <i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="List">Company Sales Person</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'DistributorController') active @endif">
                        <a class="d-flex align-items-center" href="{{route('distributor.index') }}"><i data-feather="users"></i>
                            <span class="menu-title text-truncate" data-i18n="List">Distributor</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'ShopController') active @endif">
                        <a class="d-flex align-items-center" href="{{route('shop.index') }}"><i data-feather="shopping-bag"></i>
                            <span class="menu-title text-truncate" data-i18n="List">Retailer</span>
                        </a>
                    </li>
                </ul>
            </li>

            @php
                $controller = array('MeetingController', 'RouteController');
                $action = array('index', 'route.attendance_report');
            @endphp
            <li class="nav-item {{ in_array($current_controller,$controller)?'has-sub menu-collapsed-open':'' }}">
                <a class="d-flex align-items-center" href="#"><i data-feather="bar-chart"></i>
                    <span class="menu-title text-truncate" data-i18n="Reports">Reports</span>
                </a>
                <ul class="menu-content">
                    <li class="@if($current_controller == 'RouteController' && $current_action == 'index') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('route.index') }}">
                            <i data-feather="user-check"></i><span class="menu-item text-truncate" data-i18n="List">Route</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'MeetingController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('meeting.index') }}">
                            <i data-feather="user-check"></i><span class="menu-item text-truncate" data-i18n="List">Meeting</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'RouteController' && $current_action == 'attendance_report') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('route.attendance_report') }}">
                            <i data-feather="user-check"></i><span class="menu-item text-truncate" data-i18n="List">Attendance Report</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'RouteController' && $current_action == 'employee_tracking') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('route.employee_tracking') }}">
                            <i data-feather="user-check"></i><span class="menu-item text-truncate" data-i18n="List">Employee Tracking</span>
                        </a>
                    </li>
                        
                    <li class="@if($current_controller == 'AllReportController' && $current_action == 'employee_tracking') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('all-report.index') }}">
                            <i data-feather="user-check"></i><span class="menu-item text-truncate" data-i18n="List">All Report</span>
                        </a>
                    </li>
                </ul>
            </li>

            @php
            $controller = array('CategoryController');
            $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller,$controller)?'active':'' }}">
                <a class="d-flex align-items-center" href="{{ route('category.index') }}"><i data-feather="terminal"></i>
                    <span class="menu-title text-truncate" data-i18n="Todo">Category</span>
                </a>
            </li>
            @php
            $controller = array('ProductController');
            $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller,$controller)?'active':'' }}">
                <a class="d-flex align-items-center" href="{{ route('product.index') }}"><i data-feather="pie-chart"></i>
                    <span class="menu-title text-truncate" data-i18n="Todo">Product</span>
                </a>
            </li>

            @php
            $controller = array('OrderController', 'OrderStatusController');
            $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller, $controller)?'has-sub menu-collapsed-open':'' }}">
                <a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="inbox"></i>
                    <span class="menu-title text-truncate" data-i18n="Invoice">Order</span>
                </a>
                <ul class="menu-content">
                    <li class="@if($current_controller == 'OrderStatusController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('orderstatus.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Order Status</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'OrderController' && $current_action == 'index') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('order.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Order Details</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'OrderController' && $current_action == 'no_order_list') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('order.no_order') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">No Order</span>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- @php
                $controller = array('CouponController', 'CouponHistoryController');
                $action = array('index');
            @endphp
            <!-- <li class="nav-item {{ in_array($current_controller, $controller)?'has-sub menu-collapsed-open':'' }}">
                <a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="tag"></i>
                    <span class="menu-title text-truncate" data-i18n="Invoice">Coupon</span>
                </a>
                <ul class="menu-content">
                    <li class="@if($current_controller == 'CouponController' && $current_action != 'history') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('coupon.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Coupon</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'CouponController' && $current_action == 'history') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('coupon.history') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">Coupon History</span>
                        </a>
                    </li>
                </ul>
            </li> --}} 

            @php
                $controller = array('TadaController', 'TadaTypeController');
                $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller, $controller)?'has-sub menu-collapsed-open':'' }}">
                <a class="d-flex align-items-center" href="javascript:void(0)"><i data-feather="tag"></i>
                    <span class="menu-title text-truncate" data-i18n="Invoice">TA/DA</span>
                </a>
                <ul class="menu-content">
                    <li class="@if($current_controller == 'TadaController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('tada.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">TA/DA</span>
                        </a>
                    </li>
                    <li class="@if($current_controller == 'TadaTypeController') active @endif">
                        <a class="d-flex align-items-center" href="{{ route('tadatype.index') }}">
                            <i data-feather="circle"></i><span class="menu-item text-truncate" data-i18n="List">TA/DA Type</span>
                        </a>
                    </li>
                </ul>
            </li>

            @php
                $controller = array('SettingController');
                $action = array('index');
            @endphp
            <li class="nav-item {{ in_array($current_controller,$controller)?'active':'' }}">
                <a class="d-flex align-items-center" href="{{ route('setting.index') }}"><i data-feather='settings'></i>
                    <span class="menu-title text-truncate" data-i18n="Todo">Setting</span>
                </a>
            </li>
        </ul>
    </div>