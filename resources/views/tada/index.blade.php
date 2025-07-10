@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>
        @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('TA/DA'), 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

        <div class="content-body">
            <!-- Column Search -->
            <section id="column-search-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable">
                                <table class="dt-column-search table table-responsive table-bordered" id="TadaList">
                                    <thead>
                                        <tr>
                                            <th style="width:60px; text-align:center"><input type='checkbox'
                                                    class='form-check-input check_all'></th>
                                            <th style="width:60px; text-align:center">Sr. No</th>
                                            <th style="width:200px">Date</th>
                                            <th>Name</th>
                                            <th>Location</th>
                                            <th>Type</th>
                                            <th>Expance Name</th>
                                            <th>Document</th>
                                            <th>Rate/Km</th>
                                            <th>Amount</th>
                                            <th>Daily Allowance</th>
                                            <!-- <th>Comment</th> -->
                                            <th>Is Confirm?</th>
                                            {{-- <th>Created Date</th> --}}
                                            <!-- <th>Action</th> -->
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
        var table_id = $("#TadaList");
        //var delete_url="{{ route('tadatype.multiple_delete') }}";
        var status_url = "{{ route('tada.change_status') }}";
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
                    if ($.inArray(title, ['Action', '']) === -1) {
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
                    ajax: "{{ route('tada.index') }}",
                    columns: [
                        { data: 'checkbox', orderable: false },
                        { data: 'srno', orderable: true },
                        { data: 'date', orderable: true },
                        { data: 'name', orderable: true },
                        { data: 'location', orderable: true },
                        { data: 'type', orderable: true },
                        { data: 'expance', orderable: true },
                        { data: 'value', orderable: true },
                        { data: 'per_km_price', orderable: true },
                        { data: 'amount', orderable: true },
                        { data: 'da', orderable: true },
                        //{ data: 'comment',orderable:false },
                        { data: 'is_confirm', orderable: false },
                        //{ data: 'created_at',orderable:true },
                        //{ data: 'action',orderable:false },
                    ],
                    columnDefs: [
                        { "targets": [0], "className": "text-center" },
                        { "targets": [1], "className": "text-center" }
                    ],
                    "order": [[2, 'desc']],

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
                        // {
                        //     extend: 'excelHtml5',
                        //     title: '',
                        //     text: 'Export',
                        //     className: 'btn btn-success rounded mt-1',
                        //     filename: 'TA_DA_List_' + new Date().toISOString().slice(0, 10),
                        //     exportOptions: {
                        //         columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11],
                        //         format: {
                        //             body: function (data, row, column, node) {
                        //                 // Adjust column index if needed
                        //                 if ($(node).find('input[type="checkbox"]').length) {
                        //                     return $(node).find('input[type="checkbox"]').prop('checked') ? 'Yes' : 'No';
                        //                 }
                        //                 return data;
                        //             }
                        //         }
                        //     }
                        // },
                        {
                            text: 'Export',
                            className: 'btn btn-success rounded mt-1',
                            action: function (e, dt, node, config) {
                                var reportType = $('#report_type').val();
                                
                                if (reportType === 'E') {
                                } else {
                                    var today = new Date();
                                    var dateString = today.getFullYear() + '-' + 
                                                    String(today.getMonth() + 1).padStart(2, '0') + '-' + 
                                                    String(today.getDate()).padStart(2, '0');
                                    
                                    config.title = "Expense Report";
                                    config.filename = "expense report " + dateString;
                                    
                                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, node, config);
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