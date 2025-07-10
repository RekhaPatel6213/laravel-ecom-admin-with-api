@extends('layouts.admin')
@section('content')
<div class="content-wrapper p-0">
    <div class="flash_messages">
        @include('elements.flash_messages')
    </div>

    @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('Meeting'), 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false, 'isImport' => false])

    <div class="content-body">
        <!-- Column Search -->
        <section id="column-search-datatable">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-datatable">
                            <table class="dt-column-search table table-responsive table-bordered" id="Meeting">
                                <thead>
                                    <tr>
                                        <th style="width:80px; text-align:center">Sr. No</th>
                                        <th>Sale Person</th>
                                        <th>Distributor</th>
                                        <!-- <th>Retailer</th> -->
                                        <th>City</th>
                                        <th>Mobile No.</th>
                                        <th>Meeting Type</th>
                                        <th>Start Route</th>
                                        <th>Start DateTime</th>
                                        <th>End Route</th>
                                        <th>End DateTime</th>
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
    var table_id = $("#Meeting");

    /**
     * DataTables Advanced
     */

    $(function() {
        // var isRtl = $('html').attr('data-textdirection') === 'rtl';

        var dt_ajax_table = $('.datatables-ajax'),
            dt_filter_table = $('.dt-column-search'),
            dt_adv_filter_table = $('.dt-advanced-search'),
            dt_responsive_table = $('.dt-responsive');

        if (dt_filter_table.length) {
            // Setup - add a text input to each footer cell
            $('.dt-column-search thead tr').clone(true).appendTo('.dt-column-search thead');

            $('.dt-column-search thead tr:eq(1) th').each(function(i) {
                var title = $(this).text();
                if ($.inArray(title, ['Start Route', 'End Route', 'Action']) === -1) {
                    $(this).html('<input type="search" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                    if (title == "Start DateTime") {
                        $(this).html('<input type="text" class="form-control form-control-sm dateRangePickr filter-date" placeholder="Search ' + title + '" id="' + id + '" value=""/>');
                        $('.dateRangePickr').flatpickr({
                            dateFormat: 'd/m/Y',
                        });
                    } else {
                        $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
                    }
                } else {
                    $(this).html('');
                }

                $('input', this).on('keyup change', function() {

                    var columnIndex = i; // Index of the Start DateTime column
                    var columnTitle = title; // Column Title
                    var dateFormate = 'YYYY-MM-DD'; //

                    if (columnTitle == "Start DateTime") {
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
                        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
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
                ajax: "{{ route('meeting.index') }}",
                columns: [
                    //{ data: 'checkbox', orderable:false },
                    {
                        data: 'srno',
                        orderable: true
                    },
                    {
                        data: 'sale_person',
                        orderable: true
                    },
                    {
                        data: 'distributor',
                        orderable: true
                    },
                    // { data: 'shop', orderable:true },
                    {
                        data: 'city',
                        orderable: true
                    },
                    {
                        data: 'mobile',
                        orderable: true
                    },
                    {
                        data: 'type',
                        orderable: true
                    },
                    {
                        data: 'start_map_link',
                        orderable: false
                    },
                    {
                        data: 'start_time',
                        orderable: true
                    },
                    {
                        data: 'end_map_link',
                        orderable: false
                    },
                    {
                        data: 'end_time',
                        orderable: true
                    },
                    {
                        data: 'action',
                        orderable: false
                    },
                ],
                columnDefs: [{
                        "targets": [0],
                        "className": "text-center"
                    },
                    {
                        "targets": [1],
                        "className": "text-center"
                    },
                    {
                        "targets": [9],
                        "className": "text-center"
                    }
                ],
                "order": [
                    [0, 'asc']
                ],
                dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"fB>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                buttons: [{
                        text: 'Reset',
                        className: 'btn btn-danger rounded ms-1 mt-1',
                        action: function() {
                            dt_filter.search('').columns().search('').draw(); // Reset DataTable filters
                            $('input[type="search"]').val(''); // Clear global search input
                        }
                    },
                    // {
                    //     extend: 'excelHtml5',
                    //     title: '',
                    //     text: 'Export',
                    //     className: 'btn btn-success rounded mt-1',
                    //     filename: 'Meeting_List_' + new Date().toISOString().slice(0, 10),
                    //     exportOptions: {
                    //         columns: ':visible:not(:last-child)',
                    //         format: {
                    //             body: function(data, row, column, node) {
                    //                 if (column === 7 || column === 9) { // Assuming the 'website' column is at index 6
                    //                     var url = $(node).find('a').attr('href'); //data.attr('href');
                    //                     //var text =  $(node).find('a').text(); //data.text();
                    //                     //return data ? `=HYPERLINK("${url}", "${text}")` : '';
                    //                     return url ?? ''
                    //                 }
                    //                 return data;
                    //             }
                    //         }
                    //     },
                    // },
                    {
                        extend: 'pdfHtml5',
                        text: 'Export',
                        className: 'btn btn-success rounded mt-1',
                        filename: 'Meeting_List_' + new Date().toISOString().slice(0, 10),
                        exportOptions: {
                            columns: ':visible:not(:last-child)'
                        },
                        customize: function(doc) {
                            // PDF customization options
                            doc.defaultStyle = {
                                fontSize: 10,
                                alignment: 'center'
                            };
                            doc.styles.tableHeader = {
                                bold: true,
                                fontSize: 11,
                                color: 'black',
                                alignment: 'center'
                            };
                            // Add a title
                            doc.content.splice(0, 0, {
                                text: 'Meeting List Report',
                                style: 'header',
                                alignment: 'center',
                                margin: [0, 0, 0, 20]
                            });
                            // Add a footer
                            doc.footer = function(currentPage, pageCount) {
                                return {
                                    text: 'Page ' + currentPage.toString() + ' of ' + pageCount.toString(),
                                    alignment: 'center',
                                    fontSize: 8,
                                    margin: [0, 10, 0, 0]
                                };
                            };
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
        $('input.dt-input').on('keyup', function() {
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