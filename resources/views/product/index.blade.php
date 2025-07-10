@extends('layouts.admin')
@section('content')
    <div class="content-wrapper p-0">
        <div class="flash_messages">
            @include('elements.flash_messages')
        </div>

        @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('Product'), 'isAction' => true, 'newRoute' => route('product.create'), 'newName' => __('Add'), 'isDelete' => true, 'isImport' => true])

        <div class="content-body">
            <!-- Column Search -->
            <section id="column-search-datatable">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-datatable">
                                <table class="dt-column-search table table-responsive table-bordered" id="Product">
                                    <thead>
                                        <tr>
                                            <th style="width:60px; text-align:center"><input type='checkbox'
                                                    class='form-check-input check_all'></th>
                                            <th style="width:80px; text-align:center">Sr. No</th>
                                            <th>Category Name</th>
                                            <th>Category Type</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Document</th>
                                            <th>East MRP</th>
                                            <th>North MRP</th>
                                            <th>South MRP</th>
                                            <th>West MRP</th>
                                            <th>Is Parent ?</th>
                                            <th>Sort Order</th>
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

    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Import Product</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{route('product.import')}}" class="import_form" accept-charset="UTF-8"
                    role="form" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Choose File <span class="error">*</span></label>
                                    <input type="file" name="import" class="form-control import_input">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a style="width: 100%;height: 100%;"
                                    href="{{ asset('samplefile/product_import_sample.xlsx') }}"
                                    class="btn btn-primary pull-left" download>Download Sample</a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('elements.datatable')

    <script type="text/javascript">
        $(".import_input").change(function () {
            $(".import_form").submit();
        });
    </script>

    <script>
        var table_id = $("#Product");
        var delete_url = "{{ route('product.multiple_delete') }}";
        var status_url = "{{ route('product.change_status') }}";

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
                    ajax: "{{ route('product.index') }}",
                    columns: [{ data: 'checkbox', orderable: false },
                    { data: 'srno', orderable: true },
                    { data: 'category_name', orderable: true },
                    { data: 'category_type', orderable: true },
                    { data: 'product_name', orderable: true },
                    { data: 'product_code', orderable: true },
                    { data: 'document', orderable: true },
                    { data: 'east_mrp', orderable: true },
                    { data: 'north_mrp', orderable: true },
                    { data: 'south_mrp', orderable: true },
                    { data: 'west_mrp', orderable: true },
                    { data: 'is_parent', orderable: true },
                    { data: 'sort_order', orderable: true },
                    { data: 'status', orderable: true },
                    { data: 'action', orderable: false },
                    ],
                    columnDefs: [
                        { targets: [7, 8, 9, 10, 11, 12,13], visible: false },

                        {

                            "targets": [0],
                            "className": "text-center"
                        },
                        {
                            "targets": [1],
                            "className": "text-center"
                        }
                    ],
                    "order": [
                        [1, 'desc']
                    ],

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
                            filename: 'Products_List_' + new Date().toISOString().slice(0, 10),
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