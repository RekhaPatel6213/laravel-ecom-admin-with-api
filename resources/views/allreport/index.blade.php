@extends('layouts.admin')
@section('content')
<div class="content-wrapper p-0">
    <div class="flash_messages">
        @include('elements.flash_messages')
    </div>
    <div class="content-header row">
        <div class="col-12">
            <div class="breadcrumb-wrapper-box without-btn-breadcrumb mt-2 mb-2">
                <div class="row align-items-center">
                    <div class="content-header-left col-xl-9 col-md-12 col-12">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">All Report</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                        <li class="breadcrumb-item active"><a href="javascript:void(0);">All Report</a></li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form id="reportForm" method="POST" action="{{ route('generate.report') }}">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 col-12 mb-1">
                                        <label for="report_type" class="form-label">Select Report Type</label><span class="error">*</span>
                                        <select class="form-select form-control" name="report_type" id="report_type">
                                            <option value="">Please Select</option>
                                            <option value="R">Retailing Report</option>
                                            <option value="P">Primary Order Report</option>
                                            <option value="O">Daily Sales Report</option>
                                        </select>
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 mb-1">
                                        <label for="sales_person" class="form-label">Sales Person (Distributor)</label><span class="error">*</span>
                                        <select class="form-select form-control" name="sales_person" id="sales_person" disabled>
                                            <option value="">Select Report Type First</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-4 col-md-6 col-12 mb-1">
                                        <label for="start_date" class="form-label">Start Date</label><span class="error">*</span>
                                        <input type="text" id="start_date" name="start_date" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
                                    </div>
                                    <div class="col-xl-4 col-md-6 col-12 mb-1">
                                        <label for="end_date" class="form-label">End Date</label><span class="error">*</span>
                                        <input type="text" id="end_date" name="end_date" class="form-control" placeholder="YYYY-MM-DD" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Generate Report</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

@push('script')
<script>
    $(document).ready(function() {
        // Date picker initialization
        $('#start_date, #end_date').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
        });

        // When report type changes
        $('#report_type').change(function() {
            const reportType = $(this).val();
            const salesPersonDropdown = $('#sales_person');

            if (!reportType) {
                salesPersonDropdown.html('<option value="">Select Report Type First</option>').prop('disabled', true);
                return;
            }

            salesPersonDropdown.html('<option value="">Loading...</option>').prop('disabled', false);

            $.ajax({
                url: '{{ route("get.distributors.for.report") }}',
                type: 'GET',
                data: {
                    report_type: reportType
                },
                success: function(response) {
                    if (response && response.length > 0) {
                        let options = '<option value="">Select Sales Person</option>';
                        $.each(response, function(index, distributor) {
                            options += `<option value="${distributor.id}">${distributor.firstname} ${distributor.lastname}</option>`;
                        });
                        salesPersonDropdown.html(options);
                    } else {
                        salesPersonDropdown.html('<option value="">No distributors found</option>');
                    }
                },
                error: function(xhr) {
                    console.error('Error loading distributors:', xhr.responseText);
                    salesPersonDropdown.html('<option value="">Error loading data</option>');
                }
            });
        });
    });
</script>

<script>
    // Initialize flatpickr datepickers
    const startPicker = flatpickr("#start_date", {
        dateFormat: "d-m-Y", // Match your backend expectation
        defaultDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            endPicker.set('minDate', dateStr);
            // Also update the end date if it's now invalid
            if (endPicker.selectedDates[0] && endPicker.selectedDates[0] < selectedDates[0]) {
                endPicker.setDate(selectedDates[0]);
            }
        }
    });

    const endPicker = flatpickr("#end_date", {
        dateFormat: "d-m-Y", // Match your backend expectation
        defaultDate: "today"
    });

    // Form submission handler
    $(document).ready(function() {
        $('#reportForm').on('submit', function(e) {
            e.preventDefault();
            console.log("Form submitted"); // Debug

            const btn = $('#generateReportBtn');
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Generating...');

            // Format dates properly for backend
            const formData = {
                report_type: $('#report_type').val(),
                sales_person: $('#sales_person').val(),
                start_date: startPicker.selectedDates[0] ? formatDate(startPicker.selectedDates[0]) : '',
                end_date: endPicker.selectedDates[0] ? formatDate(endPicker.selectedDates[0]) : '',
                _token: $('input[name="_token"]').val()
            };

            console.log("Submitting:", formData); // Debug

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    console.log("Response:", response);
                    if (response && response[0] && response[0].pdf_path) {
                        window.open(response[0].pdf_path, '_blank');
                    } else {
                        alert('Error: Could not generate report');
                    }
                },
                error: function(xhr) {
                    console.error("Error:", xhr.responseText);
                    alert('Error: ' + (xhr.responseJSON?.message || 'Failed to generate report'));
                },
                complete: function() {
                    btn.prop('disabled', false).html('Generate Report');
                }
            });
        });

        function formatDate(date) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${day}-${month}-${year}`;
        }
    });
</script>

<script>
    // Add this to your blade file's script section
    $('form').on('submit', function(e) {
        e.preventDefault();

        const formData = $(this).serialize();

        // Show loading indicator
        $('.btn-primary').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');

        $.ajax({
            url: '{{ route("generate.report") }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response && response[0] && response[0].pdf_path) {
                    // Open the PDF in a new tab
                    window.open(response[0].pdf_path, '_blank');
                } else {
                    alert('Error generating report');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to generate report'));
            },
            complete: function() {
                $('.btn-primary').prop('disabled', false).html('Generate Report');
            }
        });
    });
</script>
@endpush
@endsection