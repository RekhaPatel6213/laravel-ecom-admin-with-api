@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>
        <div class="content-header row">
            <div class="col-12">
                <div class="breadcrumb-wrapper-box mt-2 mb-2">
                    <div class="row align-items-center">
                        <div class="content-header-left col-xl-9 col-md-12 col-12">
                            <div class="row breadcrumbs-top">
                                <div class="col-12">
                                    <h2 class="content-header-title float-start mb-0">Order</h2>
                                    <div class="breadcrumb-wrapper">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                            <li class="breadcrumb-item active"><a href="javascript:void(0);">Order</a></li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="content-header-right text-md-end text-end col-xl-3 col-md-12 col-sm-12 col-12 d-md-block">
                            <div class="breadcrumb-right">
                                <div class="dropdown">
                                    <a href="javascript:void(0);" class="btn btn-outline-danger delete_records"><i
                                            class="fa fa-trash-o"></i>&nbsp;Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Column Search -->
            <section id="column-search-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable">
                                <table class="dt-column-search table table-responsive table-bordered" id="Order">
                                    <thead>
                                        <tr>
                                            <th style="width:60px; text-align:center"><input type='checkbox'
                                                    class='form-check-input check_all'></th>
                                            <th style="width:80px; text-align:center">Sr. No</th>
                                            <th>Order Number</th>
                                            <th>Invoice Number</th>
                                            <th>Order Type</th>
                                            <th>Distributor Name</th>
                                            <th>Retailer Name</th>
                                            {{-- <th>Meeting</th> --}}
                                            <th>Sales Person Name</th>
                                            <!-- <th>Payment Type</th> -->
                                            <th>Order Status</th>
                                            <th>Total Amount</th>
                                            <th>Total Quantity</th>
                                            <th>Order Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Column Search -->
        </div>
    </div>

    @include('elements.datatable')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        var table_id = $("#Order");
        var delete_url = "{{ route('order.multiple_delete') }}";
        var today = {{request()->today ? 1 : 0 }};
        var todayDate = "{{date('d-m-Y')}}";

        $(function () {
            var dt_filter_table = $('.dt-column-search');

            if (dt_filter_table.length) {
                $('.dt-column-search thead tr').clone(true).appendTo('.dt-column-search thead');

                $('.dt-column-search thead tr:eq(1) th').each(function (i) {
                    var title = $(this).text();
                    if ($.inArray(title, ['Action', '']) === -1) {
                        var id = title.replace(/\s/g, '').toLowerCase();
                        var value = (today === 1 && id === 'orderdate') ? todayDate : '';
                        $(this).html('<input type="search" class="form-control form-control-sm" placeholder="Search ' + title + '" id="' + id + '" value="' + value + '"/>');

                        if (title == "Order Date") {
                            $(this).html('<input type="text" class="form-control form-control-sm dateRangePickr filter-date" placeholder="Search ' + title + '" id="' + id + '" value=""/>');
                            $('.dateRangePickr').flatpickr({
                                mode: "range",
                                dateFormat: 'd/m/Y',
                            });
                        } else {
                            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                        }
                    } else {
                        $(this).html('');
                    }

                    $('input', this).on('keyup change', function () {

                        var columnIndex = i; // Index of the Order Date column
                        var columnTitle = title; // Column Title
                        var dateFormate = 'YYYY-MM-DD'; //

                        if (columnTitle == "Order Date") {
                            var startDate = null;
                            var endDate = null;
                            var inputValue = this.value; //.trim();
                            console.log(inputValue);

                            if (inputValue.search(' to ') > 0) { // If user enters a range like "2024-02-01 to 2024-02-10"
                                var dates = inputValue.split(' to ');
                                startDate = new Date(convertDateFormat(dates[0]));
                                endDate = new Date(convertDateFormat(dates[1]));
                            } else { // If user enters a single date
                                startDate = new Date(convertDateFormat(inputValue));
                                endDate = new Date(convertDateFormat(inputValue));
                            }
                            console.log("Filtering between:", startDate, "and", endDate);

                            // Remove any previous search filters
                            $.fn.dataTable.ext.search.pop();

                            if (!inputValue) {
                                dt_filter.draw();
                                return;
                            }

                            // Custom search function for date range filtering
                            $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                                var orderDate = new Date(convertDateFormat(data[columnIndex])); // Get the date from the specified column

                                // Check if the orderDate is within the selected range
                                if (!isNaN(orderDate.getTime())) { // Ensure it's a valid date
                                    //console.log('orderDate', orderDate,  'startDate', startDate, 'endDate', endDate, (!startDate || orderDate >= startDate) && (!endDate || orderDate <= endDate));
                                    return (!startDate || orderDate >= startDate) && (!endDate || orderDate <= endDate);
                                }
                                return false;
                            });


                            dt_filter.draw();

                        } else {
                            if (dt_filter.column(i).search() !== this.value) {
                                dt_filter.column(i).search(this.value).draw();
                            }
                        }

                    });
                });

                // Initialize DataTable
                dt_filter = dt_filter_table.DataTable({
                    ajax: "{{ route('order.index') }}",
                    columns: [
                        { data: 'checkbox', orderable: false },
                        { data: 'srno', orderable: true },
                        { data: 'order_no', orderable: true },
                        { data: 'invoice_no', orderable: true },
                        { data: 'order_type', orderable: true },
                        { data: 'distributor_name', orderable: false },
                        { data: 'shop_name', orderable: false },
                        { data: 'customer_name', orderable: false },
                        { data: 'order_status', orderable: false },
                        { data: 'total_amount', orderable: false },
                        { data: 'total_quantity', orderable: false },
                        { data: 'order_date', orderable: false },
                        { data: 'action', orderable: false },
                    ],
                    columnDefs: [
                        { "targets": [0], "className": "text-center" },
                        { "targets": [1], "className": "text-center" }
                    ],
                    order: [[1, 'desc']],
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"fB>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    buttons: [
                        {
                            text: 'Reset',
                            className: 'btn btn-danger rounded ms-1 mt-1',
                            action: function () {
                                dt_filter.search('').columns().search('').draw();
                                $('input[type="search"]').val('');
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: '',
                            text: 'Export',
                            className: 'btn btn-success rounded mt-1',
                            filename: 'Order_List_' + new Date().toISOString().slice(0, 10),
                            exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11] }
                        }
                    ],
                    orderCellsTop: true,
                    language: { paginate: { previous: '&nbsp;', next: '&nbsp;' } }
                });
            }

            $('input.dt-input').on('keyup', function () {
                filterColumn($(this).attr('data-column'), $(this).val());
            });

            $('.dataTables_filter .form-control').removeClass('form-control-sm');
            $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');
        });
        function convertDateFormat(dateStr) {
            let parts = dateStr.split("/"); // Split date by "-"
            return parts[2] + "-" + parts[1] + "-" + parts[0]; // Rearrange to yyyy-mm-dd
        }

    </script>
@endsection