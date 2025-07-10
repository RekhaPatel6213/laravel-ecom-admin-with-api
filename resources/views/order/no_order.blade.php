@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => null, 'parentName' => 'Order', 'name' => __('No Order'), 'isAction' => false, 'newRoute' => route('order.index'), 'newName' => __('Add'), 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <!-- Column Search -->
            <section id="column-search-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable">
                                <table class="dt-column-search table table-responsive table-bordered" id="no_order">
                                    <thead>
                                        <tr>
                                            <th style="width:80px; text-align:center">Sr. No</th>
                                            <th>User</th>
                                            <th>Distributor</th>
                                            <th>Shop</th>
                                            <th>Comment</th>
                                            <th>Date</th>
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
        var table_id = $("#no_order");
        var today = {{request()->today ? 1 : 0 }};
        var todayDate = "{{date('d-m-Y')}}";
        /**
         * DataTables Advanced
         */

        $(function () {
            // var isRtl = $('html').attr('data-textdirection') === 'rtl';

            var dt_ajax_table = $('.datatables-ajax'),
                dt_filter_table = $('.dt-column-search'),
                dt_adv_filter_table = $('.dt-advanced-search'),
                dt_responsive_table = $('.dt-responsive');

            if (dt_filter_table.length) {
                // Setup - add a text input to each footer cell
                $('.dt-column-search thead tr').clone(true).appendTo('.dt-column-search thead');

                $('.dt-column-search thead tr:eq(1) th').each(function (i) {
                    var title = $(this).text();
                    if ($.inArray(title, ['Action', '', 'Status']) === -1) {
                        $(this).html('<input type="search" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                        if (title == "Date") {
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
                        var dateFormate = 'd/m/Y'; //

                        if (columnTitle == "Date") {
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

                var dt_filter = dt_filter_table.DataTable({
                    ajax: "{{ route('order.no_order') }}",
                    columns: [
                        { data: 'srno', orderable: true },
                        { data: 'user', orderable: true },
                        { data: 'distributor_name', orderable: true },
                        { data: 'shop_name', orderable: true },
                        { data: 'comment', orderable: true },
                        { data: 'order_date', orderable: true },
                    ],
                    columnDefs: [
                        { "targets": [0], "className": "text-center" },
                        { "targets": [1], "className": "text-center" }
                    ],
                    "order": [[0, 'asc']],
                    dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"fB>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    buttons: [
                        {
                            text: 'Reset',
                            className: 'btn btn-danger rounded ms-1 mt-1',
                            action: function () {
                                dt_filter.search('').columns().search('').draw(); // Reset DataTable filters
                                $('input[type="search"]').val(''); // Clear global search input
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: '',
                            text: 'Export',
                            className: 'btn btn-success rounded mt-1',
                            filename: 'No_Order_List_' + new Date().toISOString().slice(0, 10),
                            exportOptions: {
                                columns: [0,1, 2, 3, 4, 5],
                                format: {
                                    body: function (data, row, column, node) {
                                        // Adjust column index if needed
                                        if ($(node).find('input[type="checkbox"]').length) {
                                            return $(node).find('input[type="checkbox"]').prop('checked') ? 'Active' : 'In-Active';
                                        }
                                        return data;
                                    }
                                }
                            }
                        }
                    ],
                    orderCellsTop: true,
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    }
                });
            }

            // on key up from input field
            $('input.dt-input').on('keyup', function () {
                filterColumn($(this).attr('data-column'), $(this).val());
            });

            // Filter form control to default size for all tables
            $('.dataTables_filter .form-control').removeClass('form-control-sm');
            $('.dataTables_length .form-select').removeClass('form-select-sm').removeClass('form-control-sm');
        });
        function convertDateFormat(dateStr) {
            let parts = dateStr.split("/"); // Split date by "-"
            return parts[2] + "-" + parts[1] + "-" + parts[0]; // Rearrange to yyyy-mm-dd
        }
    </script>

@endsection