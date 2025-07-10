@extends('layouts.admin')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/pages/dashboard-ecommerce.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/plugins/charts/chart-apex.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('admin/app-assets/css/plugins/extensions/ext-component-toastr.css') }}">

<div class="content-wrapper p-0">
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- Dashboard Ecommerce Starts -->
        <section id="dashboard-ecommerce">
            <div class="row match-height">
                <!-- Statistics Card -->
                <div class="col-xl-12 col-md-12 col-12">
                    <div class="card card-statistics">
                        <div class="card-header">
                            <h4 class="card-title">Dashboard</h4>
                            <div class="d-flex align-items-center">
                                <p class="card-text font-small-2 me-25 mb-0"></p>
                            </div>
                        </div>
                        <div class="card-body statistics-body">
                            <div class="row">

                                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                                    <div class="d-flex flex-row dashboard-wrap-box">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <a href="{{ route('user.index') }}"><i class="fa fa-user" style="font-size:22px"></i></a>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0"><a href="{{ route('user.index') }}">{{ $total_user }}</a></h4>
                                            <p class="card-text font-small-3 mb-0"><a href="{{ route('user.index') }}">Total Sales Person</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                                    <div class="d-flex flex-row dashboard-wrap-box">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <a href="{{ route('distributor.index') }}"><i class="fa fa-user" style="font-size:22px"></i></a>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0"><a href="{{ route('distributor.index') }}">{{ $total_distributor }}</a></h4>
                                            <p class="card-text font-small-3 mb-0"><a href="{{ route('distributor.index') }}">Total Distributor</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                                    <div class="d-flex flex-row dashboard-wrap-box">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <a href="{{ route('category.index') }}"><i class="fa fa-tag" style="font-size:22px"></i></a>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0"><a href="{{ route('category.index') }}">{{ $category_count }}</a></h4>
                                            <p class="card-text font-small-3 mb-0"><a href="{{ route('category.index') }}">Total Categories</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                                    <div class="d-flex flex-row dashboard-wrap-box">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <a href="{{ route('product.index') }}"><i class="fa fa-product-hunt" style="font-size:22px"></i></a>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0"><a href="{{ route('product.index') }}">{{ $product_count }}</a></h4>
                                            <p class="card-text font-small-3 mb-0"><a href="{{ route('product.index') }}">Total Products</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row dashboard-wrap-box">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <a href="{{route('order.index') }}"><i class="fa fa-cart-arrow-down" style="font-size:22px"></i></a>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0"><a href="{{ route('order.index') }}">{{ $order_count }}</a></h4>
                                            <p class="card-text font-small-3 mb-0"><a href="{{ route('order.index') }}">Comp. Sales Orders</a></p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                        <div class="d-flex flex-row dashboard-wrap-box">
                                            <div class="avatar bg-light-info me-2">
                                                <div class="avatar-content">
                                                    <a href="{{ route('order.index') }}"><i class="fa fa-cart-arrow-down" style="font-size:22px"></i></a>
                            </div>
                        </div>
                        <div class="my-auto">
                            <h4 class="fw-bolder mb-0"><a href="{{ route('order.index') }}">{{ $direct_order_count }}</a></h4>
                            <p class="card-text font-small-3 mb-0"><a href="{{ route('order.index') }}">Direct Dealer Orders</a></p>
                        </div>
                    </div>
                </div> --}}
            </div>
    </div>
</div>
</div>

<div class="col-xl-12 col-md-12 col-12">
    <div class="card card-statistics">
        <div class="card-header">
            <h4 class="card-title">Today's Summary</h4>
            <div class="d-flex align-items-center">
                <p class="card-text font-small-2 me-25 mb-0"></p>
            </div>
        </div>
        <div class="card-body statistics-body">
            <div class="row">

                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                    <div class="d-flex flex-row dashboard-wrap-box">
                        <div class="avatar bg-light-info me-2">
                            <div class="avatar-content">
                                <a href="{{ route('user.index') }}"><i class="fa fa-users" style="font-size:22px"></i></a>
                            </div>
                        </div>
                        <div class="my-auto">
                            <h4 class="fw-bolder mb-0"><a href="{{ route('meeting.index') }}">{{ $todayCount }}</a></h4>
                            <p class="card-text font-small-3 mb-0"><a href="{{ route('meeting.index') }}">Total Meetings</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                    <div class="d-flex flex-row dashboard-wrap-box">
                        <div class="avatar bg-light-info me-2">
                            <div class="avatar-content">
                                <a href="{{ route('order.index') }}"><i class="fa fa-first-order" style="font-size:22px"></i></a>
                            </div>
                        </div>
                        <div class="my-auto">
                            <h4 class="fw-bolder mb-0"><a href="{{ route('order.index') }}">{{ $productive }}</a></h4>
                            <p class="card-text font-small-3 mb-0"><a href="{{ route('order.index') }}">Productive Orders</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2">
                    <div class="d-flex flex-row dashboard-wrap-box">
                        <div class="avatar bg-light-info me-2">
                            <div class="avatar-content">
                                <a href="{{ route('order.no_order') }}"><i class="fa fa-solid fa-clipboard" style="font-size:22px"></i></a>
                            </div>
                        </div>
                        <div class="my-auto">
                            <h4 class="fw-bolder mb-0"><a href="{{ route('order.no_order') }}">{{ $noproductive }}</a></h4>
                            <p class="card-text font-small-3 mb-0"><a href="{{ route('category.index') }}">Unproductive Orders</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                    <div class="d-flex flex-row dashboard-wrap-box">
                        <div class="avatar bg-light-info me-2">
                            <div class="avatar-content">
                                <a href="{{route('order.index') }}"><i class="fa fa-cart-arrow-down" style="font-size:22px"></i></a>
                            </div>
                        </div>
                        <div class="my-auto">
                            <h4 class="fw-bolder mb-0"><a href="{{ route('order.index') }}">{{ $primaryorder }}</a></h4>
                            <p class="card-text font-small-3 mb-0"><a href="{{ route('order.index') }}">Primary Orders</a></p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                    <div class="d-flex flex-row dashboard-wrap-box">
                        <div class="avatar bg-light-info me-2">
                            <div class="avatar-content">
                                <a href="{{route('tada.index') }}"><i class="fa fa-usd" style="font-size:22px"></i></a>
                            </div>
                        </div>
                        <div class="my-auto">
                            <h4 class="fw-bolder mb-0"><a href="{{ route('tada.index') }}">{{ $todayAmountSum }}</a></h4>
                            <p class="card-text font-small-3 mb-0"><a href="{{ route('tada.index') }}">Total Expense</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-6 col-md-6 col-6">
    <div class="card card-statistics">
        <div class="card-header d-block">
            <h4 class="card-title">Meetings</h4>
            <div class="d-flex align-items-center mt-2">
                <input type="text" id="dateRange" class="form-control" placeholder="Select Date Range" style="max-width: 300px;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="meetingChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-6 col-md-6 col-6">
    <div class="card card-statistics">
        <div class="card-header d-block">
            <h4 class="card-title">Daily Sales</h4>
            <div class="d-flex align-items-center mt-2">
                <input type="text" id="mrpDateRange" class="form-control" placeholder="Select Date Range" style="max-width: 300px;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="productMrpChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-6 col-md-6 col-6">
    <div class="card card-statistics">
        <div class="card-header d-block">
            <h4 class="card-title">Daily Orders</h4>
            <div class="d-flex align-items-center mt-2">
                <input type="text" id="ordersDateRange" class="form-control" placeholder="Select Date Range" style="max-width: 300px;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="ordersChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-6 col-md-6 col-6">
    <div class="card card-statistics">
        <div class="card-header d-block">
            <h4 class="card-title">Daily Expense</h4>
            <div class="d-flex align-items-center mt-2">
                <input type="text" id="tadaDateRange" class="form-control" placeholder="Select Date Range" style="max-width: 300px;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="tadaChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12 col-md-12 col-12">
    <div class="card card-statistics">
        <div class="card-header d-block">
            <h4 class="card-title">Primary Orders</h4>
            <div class="d-flex align-items-center mt-2">
                <input type="text" id="nullShopOrdersDateRange" class="form-control" placeholder="Select Date Range" style="max-width: 300px;">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="nullShopOrdersChart"></div>
            </div>
        </div>
    </div>
</div>

<div class="col-xl-12 col-md-12 col-12">
    <div class="card card-statistics">
        <div class="card-header d-block">
            <h4 class="card-title">Daily Productive vs Unproductive Orders</h4>
            <div class="d-flex align-items-center mt-2">
                <input type="text" id="productiveUnproductiveDateRange" class="form-control" placeholder="Select Date Range" style="max-width: 300px;">
            </div>
        </div>
        <div id="productiveUnproductiveChart"></div>
    </div>
</div>
</section>
<!-- Dashboard Ecommerce ends -->
</div>
</div>

<!-- BEGIN: Page JS-->
<script src="{{ asset('admin/app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>

<!-- END: Page JS-->
@push('script')
<script src="{{ asset('admin/app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/app-assets/vendors/js/extensions/toastr.min.js') }}"></script>
<script>
    // 1. Initialize chart with empty state
    let chart = new ApexCharts(document.querySelector("#meetingChart"), {
        chart: {
            type: 'bar',
            height: 350,
            animations: { enabled: true }
        },
        series: [{
            name: 'Meetings',
            data: []
        }],
        xaxis: {
            categories: [],
            labels: { rotate: -45 }
        },
        colors: ['#aa0023'],
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif',
                fontWeight: 'bold'
            }
        }
    });
    chart.render();

    // 2. Function to process and update chart data
    function updateChart(data) {
        // Transform data - expects array of {month, count} objects
        const categories = data.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month-1).toLocaleString('default', {
                month: 'short', 
                year: 'numeric'
            });
        });
        
        const seriesData = data.map(item => item.count);

        // Update chart
        chart.updateOptions({
            xaxis: { categories: categories }
        }, false, true);

        chart.updateSeries([{
            name: 'Meetings',
            data: seriesData
        }], true);
    }

    // 3. Load initial data
    @if(isset($GraphMeetingCount))
    updateChart({!! json_encode($GraphMeetingCount) !!});
    @endif

    // 4. Date range filter
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0].toISOString().split('T')[0];
                const end = selectedDates[1].toISOString().split('T')[0];

                fetch(`/meeting-count?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(data => {
                        // Data should now be in correct format
                        updateChart(data);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Failed to load data");
                    });
            } else {
                // Reset to all data
                @if(isset($GraphMeetingCount))
                updateChart({!! json_encode($GraphMeetingCount) !!});
                @endif
            }
        }
    });

    // 5. CSS fallback for labels
    const style = document.createElement('style');
    style.innerHTML = `
        .apexcharts-datalabels text {
            font-family: Verdana, sans-serif !important;
            font-size: 13px !important;
            font-weight: bold !important;
            fill: #ffffff !important;
        }
    `;
    document.head.appendChild(style);
</script>

<script>
    // 1. Initialize Product MRP chart
    let mrpChart = new ApexCharts(document.querySelector("#productMrpChart"), {
        chart: {
            type: 'bar',
            height: 350,
            animations: { enabled: true }
        },
        series: [{
            name: 'Total MRP',
            data: []
        }],
        xaxis: {
            categories: [],
            labels: { rotate: -45 }
        },
        colors: ['#aa0023'], // Different color for MRP chart
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif',
                fontWeight: 'bold'
            },
            formatter: function(val) {
                return '₹' + val.toLocaleString(); // Format as currency
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        }
    });
    mrpChart.render();

    function updateMrpChart(data) {
        const categories = data.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month-1).toLocaleString('default', {
                month: 'short', 
                year: 'numeric'
            });
        });
        
        const seriesData = data.map(item => Number(item.total_mrp));

        mrpChart.updateOptions({
            xaxis: { categories: categories }
        }, false, true);

        mrpChart.updateSeries([{
            name: 'Total MRP',
            data: seriesData
        }], true);
    }

    // 3. Load initial MRP data
    @if(isset($GraphProductMrp))
    updateMrpChart({!! json_encode($GraphProductMrp) !!});
    @endif

    // 4. MRP date range filter
    flatpickr("#mrpDateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0].toISOString().split('T')[0];
                const end = selectedDates[1].toISOString().split('T')[0];

                fetch(`/product-mrp-summary?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(data => {
                        updateMrpChart(data);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Failed to load MRP data");
                    });
            } else {
                // Reset to all data
                @if(isset($GraphProductMrp))
                updateMrpChart({!! json_encode($GraphProductMrp) !!});
                @endif
            }
        }
    });
    // Rest of your existing meeting chart code...
</script>

<script>
    // 1. Initialize Orders chart
    let ordersChart = new ApexCharts(document.querySelector("#ordersChart"), {
        chart: {
            type: 'bar',
            height: 350,
            animations: { enabled: true }
        },
        series: [{
            name: 'Orders Count',
            data: []
        }],
        xaxis: {
            categories: [],
            labels: { rotate: -45 }
        },
        colors: ['#aa0023'], // Green color for orders chart
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif',
                fontWeight: 'bold'
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + ' orders';
                }
            }
        }
    });
    ordersChart.render();

    // 2. Function to update Orders chart
    function updateOrdersChart(data) {
        const categories = data.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month-1).toLocaleString('default', {
                month: 'short', 
                year: 'numeric'
            });
        });
        
        const seriesData = data.map(item => Number(item.count));

        ordersChart.updateOptions({
            xaxis: { categories: categories }
        }, false, true);

        ordersChart.updateSeries([{
            name: 'Orders Count',
            data: seriesData
        }], true);
    }

    // 3. Load initial Orders data
    @if(isset($GraphOrdersCount))
    updateOrdersChart({!! json_encode($GraphOrdersCount) !!});
    @endif

    // 4. Orders date range filter
    flatpickr("#ordersDateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0].toISOString().split('T')[0];
                const end = selectedDates[1].toISOString().split('T')[0];

                fetch(`/orders-count?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(data => {
                        updateOrdersChart(data);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Failed to load orders data");
                    });
            } else {
                // Reset to all data
                @if(isset($GraphOrdersCount))
                updateOrdersChart({!! json_encode($GraphOrdersCount) !!});
                @endif
            }
        }
    });
</script>

<script>
    // 1. Initialize TADA chart
    let tadaChart = new ApexCharts(document.querySelector("#tadaChart"), {
        chart: {
            type: 'bar',
            height: 350,
            animations: { enabled: true }
        },
        series: [{
            name: 'Total TADA Amount',
            data: []
        }],
        xaxis: {
            categories: [],
            labels: { rotate: -45 }
        },
        colors: ['#aa0023'],
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif',
                fontWeight: 'bold'
            },
            formatter: function(val) {
                return '₹' + val.toLocaleString();
            }
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        }
    });
    tadaChart.render();

    // 2. Function to update TADA chart
    function updateTadaChart(data) {
        const categories = data.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month-1).toLocaleString('default', {
                month: 'short', 
                year: 'numeric'
            });
        });
        
        const seriesData = data.map(item => Number(item.total_amount));

        tadaChart.updateOptions({
            xaxis: { categories: categories }
        }, false, true);

        tadaChart.updateSeries([{
            name: 'Total TADA Amount',
            data: seriesData
        }], true);
    }

    // 3. Load initial TADA data
    @if(isset($GraphTadaAmount))
    updateTadaChart({!! json_encode($GraphTadaAmount) !!});
    @endif

    // 4. TADA date range filter
    flatpickr("#tadaDateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0].toISOString().split('T')[0];
                const end = selectedDates[1].toISOString().split('T')[0];

                fetch(`/tada-amount-summary?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(data => {
                        updateTadaChart(data);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Failed to load TADA data");
                    });
            } else {
                // Reset to all data
                @if(isset($GraphTadaAmount))
                updateTadaChart({!! json_encode($GraphTadaAmount) !!});
                @endif
            }
        }
    });
</script>

<script>
    // 1. Initialize Null Shop Orders chart
    let nullShopOrdersChart = new ApexCharts(document.querySelector("#nullShopOrdersChart"), {
        chart: {
            type: 'bar',
            height: 350,
            animations: { enabled: true }
        },
        series: [{
            name: 'Primary Orders',
            data: []
        }],
        xaxis: {
            categories: [],
            labels: { rotate: -45 }
        },
        colors: ['#aa0023'],
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif',
                fontWeight: 'bold'
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + (val === 1 ? ' order' : ' orders');
                }
            }
        }
    });
    nullShopOrdersChart.render();

    // 2. Function to update Null Shop Orders chart
    function updateNullShopOrdersChart(data) {
        const categories = data.map(item => {
            const [year, month] = item.month.split('-');
            return new Date(year, month-1).toLocaleString('default', {
                month: 'short', 
                year: 'numeric'
            });
        });
        
        const seriesData = data.map(item => Number(item.count));

        nullShopOrdersChart.updateOptions({
            xaxis: { categories: categories }
        }, false, true);

        nullShopOrdersChart.updateSeries([{
            name: 'Primary Orders',
            data: seriesData
        }], true);
    }

    // 3. Load initial Null Shop Orders data
    @if(isset($GraphNullShopOrders))
    updateNullShopOrdersChart({!! json_encode($GraphNullShopOrders) !!});
    @endif

    // 4. Null Shop Orders date range filter
    flatpickr("#nullShopOrdersDateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0].toISOString().split('T')[0];
                const end = selectedDates[1].toISOString().split('T')[0];

                fetch(`/null-shop-orders-count?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(data => {
                        updateNullShopOrdersChart(data);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Failed to load Primary Orders data");
                    });
            } else {
                // Reset to all data
                @if(isset($GraphNullShopOrders))
                updateNullShopOrdersChart({!! json_encode($GraphNullShopOrders) !!});
                @endif
            }
        }
    });
</script>


<script>
    // Initialize Productive vs Unproductive chart
    let productiveUnproductiveChart = new ApexCharts(document.querySelector("#productiveUnproductiveChart"), {
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            animations: { enabled: true },
            toolbar: { show: true }
        },
        series: [
            { name: 'Productive Orders', data: [] },
            { name: 'Unproductive Orders', data: [] }
        ],
        xaxis: {
            type: 'category',
            categories: [],
            labels: { 
                rotate: -45,
                formatter: function(val) {
                    return new Date(val).toLocaleDateString('en-US', { 
                        month: 'short', 
                        day: 'numeric' 
                    });
                }
            }
        },
        colors: ['#aa0023', '#000'],
        dataLabels: {
            enabled: true,
            style: {
                colors: ['#ffffff'],
                fontSize: '11px'
            },
            formatter: function(val) {
                return val > 0 ? val : '';
            }
        }
    });
    productiveUnproductiveChart.render();

    // Function to update chart
    function updateProductiveUnproductiveChart(data) {
        const groupedData = {};
        data.forEach(item => {
            if (!groupedData[item.date]) {
                groupedData[item.date] = {
                    productive: 0,
                    unproductive: 0
                };
            }
            groupedData[item.date][item.type] = item.count;
        });

        const dates = Object.keys(groupedData).sort();
        const productiveData = dates.map(date => groupedData[date].productive || 0);
        const unproductiveData = dates.map(date => groupedData[date].unproductive || 0);

        productiveUnproductiveChart.updateOptions({
            xaxis: { categories: dates }
        }, false, true);

        productiveUnproductiveChart.updateSeries([
            { name: 'Productive Orders', data: productiveData },
            { name: 'Unproductive Orders', data: unproductiveData }
        ], true);
    }

    // Load ALL data by default (no date restriction)
    fetch('/productive-vs-unproductive-orders')
        .then(response => response.json())
        .then(data => {
            updateProductiveUnproductiveChart(data);
        })
        .catch(error => {
            console.error("Error loading initial data:", error);
        });

    // Date range filter
    flatpickr("#productiveUnproductiveDateRange", {
        mode: "range",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates) {
            if (selectedDates.length === 2) {
                const start = selectedDates[0].toISOString().split('T')[0];
                const end = selectedDates[1].toISOString().split('T')[0];

                fetch(`/productive-vs-unproductive-orders?start=${start}&end=${end}`)
                    .then(response => response.json())
                    .then(data => {
                        updateProductiveUnproductiveChart(data);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        toastr.error("Failed to load filtered data");
                    });
            } else {
                // Reset to ALL data when selection is cleared
                fetch('/productive-vs-unproductive-orders')
                    .then(response => response.json())
                    .then(data => {
                        updateProductiveUnproductiveChart(data);
                    });
            }
        }
    });
</script>
@endpush
@endsection