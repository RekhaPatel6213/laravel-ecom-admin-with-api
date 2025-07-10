@extends('layouts.admin')
    @section('content')
        <div class="content-wrapper p-0">
            <div class="flash_messages">
                @include('elements.flash_messages')
            </div>
 
            @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('Coupon History'), 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => false])

            <div class="content-body">
                <!-- Column Search -->
                <section id="column-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-datatable">
                                    <table class="dt-column-search table table-responsive table-bordered" id="CounponHistory">
                                        <thead>
                                            <tr>
                                                <th style="width:80px; text-align:center">Sr. No</th>
                                                <th>Order No</th>
                                                <th>Distributor Name</th>
                                                <th>Retailer Name</th>
                                                <th>Counpon Name</th>
                                                <th>Coupon Code</th>
                                                <th>Discount Amount</th>
                                                <th>Order Amount</th>
                                                <th>Date</th>
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
            var table_id = $("#CounponHistory");

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
                        if($.inArray(title, ['Action', '','Status']) === -1){
                            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Search ' + title + '" />');
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
                        ajax: "{{ route('coupon.history') }}",
                        columns: [
                            { data: 'srno',orderable:true },
                            { data: 'order_no',orderable:true },
                            { data: 'distributor',orderable:true },
                            { data: 'shop',orderable:true },
                            { data: 'counpon_name',orderable:true },
                            { data: 'counpon_code',orderable:true },
                            { data: 'discount_amount',orderable:true },
                            { data: 'order_amount',orderable:true },
                            { data: 'created_at',orderable:true },
                            { data: 'action',orderable:false },
                        ],
                        columnDefs: [
                            {"targets": [0,1,6,7], "className": "text-center"},
                            {"targets": [2,3,4,5], "className": "text-left"}
                        ],
                        "order": [[1, 'desc']],

                        dom: '<"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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