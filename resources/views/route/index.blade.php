@extends('layouts.admin')
    @section('content')
        <div class="content-wrapper p-0">
            <div class="flash_messages">
                @include('elements.flash_messages')
            </div>

            @include('elements.breadcrumb', ['route' => null, 'parentName' => null, 'name' => __('Route'), 'isAction' => false, 'newRoute' => null, 'newName' => null, 'isDelete' => true, 'isImport' => false])

            <div class="content-body">
                <!-- Column Search -->
                <section id="column-search-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-datatable">
                                    <table class="dt-column-search table table-responsive table-bordered" id="Route">
                                        <thead>
                                            <tr>
                                                <th style="width:80px; text-align:center">Sr. No</th>
                                                <th>Sale Person</th>
                                                <th>Mobile No.</th>
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
        <script>
            var table_id = $("#Route");

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
                        if($.inArray(title, ['Start Route', 'End Route','Action']) === -1){
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
                        ajax: "{{ route('route.index') }}",
                        columns: [
                            //{ data: 'checkbox', orderable:false },
                            { data: 'srno', orderable:true,  },
                            { data: 'sale_person', orderable:true ,  },
                            { data: 'mobile', orderable:true,  },
                            { data: 'start_map_link', orderable:false,  },
                            { data: 'start_time', orderable:true,  },
                            { data: 'end_map_link', orderable:false,  },
                            { data: 'end_time', orderable:true,  },
                            { data: 'action', orderable:false },
                        ],
                        columnDefs: [
                            {"targets": [0], "className": "text-center"},
                            {"targets": [1], "className": "text-center"},
                            {"targets": [7], "className": "text-center"}
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
                                title:'',
                                text: 'Export',
                                className: 'btn btn-success rounded mt-1',
                                filename: 'Route_List_' + new Date().toISOString().slice(0, 10),
                                exportOptions: {
                                    columns: ':visible:not(:last-child)',
                                    format: {
                                        body: function (data, row, column, node) {
                                            if (column === 3 || column === 5) { // Assuming the 'website' column is at index 6
                                                var url = $(node).find('a').attr('href');//data.attr('href');
                                                //var text =  $(node).find('a').text(); //data.text();
                                                //return data ? `=HYPERLINK("${url}", "${text}")` : '';
                                                return url ??'' 
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