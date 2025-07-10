@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('Retailer'), 'isAction' => true, 'newRoute' => route('shop.create'), 'newName' => __('Add'), 'isDelete' => true, 'isImport' => false])

        <div class="content-body">
            <!-- Column Search -->
            <section id="column-search-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable">
                                <table class="dt-column-search table table-responsive table-bordered" id="Shop">
                                    <thead>
                                        <tr>
                                            <th style="width:60px; text-align:center"><input type='checkbox'
                                                    class='form-check-input check_all'></th>
                                            <th style="width:80px; text-align:center">Sr. No</th>
                                            <th>Name</th>
                                            <th>Contact Person</th>
                                            <th>Distributor</th>
                                            <th>GST.No</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Route</th>
                                            <th>Shop Area</th>
                                            <th>Zone</th>
                                            <th>Pincode</th>
                                            <th>Status</th>
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
    <script>
        var table_id = $("#Shop");
        var delete_url = "{{ route('shop.multiple_delete') }}";
        var status_url = "{{ route('shop.change_status') }}";

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
                    } else {
                        $(this).html('');
                    }

                    $('input', this).on('keyup change', function () {
                        if (dt_filter.column(i).search() !== this.value) {
                            dt_filter.column(i).search(this.value).draw();
                        }
                    });
                });

                var dt_filter = dt_filter_table.DataTable({
                    ajax: "{{ route('shop.index') }}",
                    columns: [
                        { data: 'checkbox', orderable: false },
                        { data: 'srno', orderable: true },
                        { data: 'name', orderable: true },
                        { data: 'contact_person_name', orderable: true },
                        { data: 'distributor', orderable: true },
                        { data: 'gstin_no', orderable: true },
                        { data: 'email', orderable: true },
                        { data: 'mobile', orderable: true },
                        { data: 'state', orderable: true },
                        { data: 'city', orderable: true },
                        { data: 'area', orderable: true },
                        { data: 'shop_area', orderable: true },
                        { data: 'zone', orderable: true },
                        { data: 'pincode', orderable: true },
                        { data: 'status', orderable: false },
                        { data: 'action', orderable: false },
                    ],
                    columnDefs: [
                        { targets: [8, 9, 10, 11, 12, 13], visible: false },
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
                                dt_filter.search('').columns().search('').draw(); // Reset DataTable filters
                                $('input[type="search"]').val(''); // Clear global search input
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            title: '',
                            text: 'Export',
                            className: 'btn btn-success rounded mt-1',
                            filename: 'Retailer_List_' + new Date().toISOString().slice(0, 10),
                            exportOptions: {

                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
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
    </script>
@endsection