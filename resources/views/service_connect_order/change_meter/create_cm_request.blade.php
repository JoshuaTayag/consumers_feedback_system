@extends('layouts.app')

@section('content')
<div class="container ui-form-page">
  <!-- Page Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
      <h4 class="ui-form-title mb-0"><i class="fas fa-plug me-2"></i>New Change Meter Request</h4>
      <small class="text-muted">Fill in the account and consumer details to file a change meter request</small>
    </div>
    <a class="btn btn-sm btn-outline-secondary" href="{{ route('indexCM') }}">
      <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
  </div>

  <!-- Stage Breadcrumb / Stepper -->
  <div class="ui-stepper-wrap mb-4">
    <ol class="ui-stepper">
      <li class="ui-step is-active">
        <span class="ui-step-icon"><i class="fas fa-file-signature"></i></span>
        <span class="ui-step-label">Create</span>
      </li>
      <li class="ui-step-connector"></li>
      <li class="ui-step">
        <span class="ui-step-icon"><i class="fas fa-gauge-high"></i></span>
        <span class="ui-step-label">Assign Meter</span>
      </li>
      <li class="ui-step-connector"></li>
      <li class="ui-step">
        <span class="ui-step-icon"><i class="fas fa-truck"></i></span>
        <span class="ui-step-label">Dispatch</span>
      </li>
      <li class="ui-step-connector"></li>
      <li class="ui-step">
        <span class="ui-step-icon"><i class="fas fa-paper-plane"></i></span>
        <span class="ui-step-label">Post</span>
      </li>
      <li class="ui-step-connector"></li>
      <li class="ui-step">
        <span class="ui-step-icon"><i class="fas fa-circle-check"></i></span>
        <span class="ui-step-label">Completed</span>
      </li>
    </ol>
  </div>

  <form action="{{ route('storeCM') }}" method="POST" id="cmrForm">
    @csrf

    <div class="alert ui-alert-soft d-flex align-items-start mb-4">
      <i class="fas fa-circle-info me-2 mt-1"></i>
      <div>If the account number does not appear in the lookup, it already has a pending change meter request.</div>
    </div>

    <div class="row g-3">
      <div class="col-lg-12">

        <!-- Account Lookup -->
        <div class="card ui-card mb-3">
          <div class="card-header ui-card-header">
            <i class="fas fa-magnifying-glass me-2"></i>Account Lookup
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-4">
                <label for="electric_service_detail" class="form-label ui-label">Account Number <span class="text-danger">*</span></label>
                <select class="form-control" id="electric_service_detail" name="electric_service_detail" style="width: 100%" required></select>
              </div>
              <div class="col-lg-2">
                <label for="old_meter" class="form-label ui-label">Old Meter</label>
                <input type="text" id="old_meter" name="old_meter" class="form-control" readonly>
              </div>
              <div class="col-lg-2">
                <label for="consumer_type" class="form-label ui-label">Type <span class="text-danger">*</span></label>
                <input type="text" id="consumer_type" name="consumer_type" class="form-control" readonly>
              </div>
              <div class="col-lg-2">
                <label for="last_reading" class="form-label ui-label">Last Reading</label>
                <input type="number" id="last_reading" name="last_reading" class="form-control" readonly>
              </div>
              <div class="col-lg-2">
                <label for="reading_initial" class="form-label ui-label">Initial Reading</label>
                <input type="number" id="reading_initial" name="reading_initial" class="form-control">
              </div>
            </div>
          </div>
        </div>

        <!-- Consumer Details -->
        <div class="card ui-card mb-3">
          <div class="card-header ui-card-header">
            <i class="fas fa-user me-2"></i>Consumer Details
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-3">
                <label for="last_name" class="form-label ui-label">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required>
              </div>
              <div class="col-lg-3">
                <label for="first_name" class="form-label ui-label">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required>
              </div>
              <div class="col-lg-2">
                <label for="membership_or" class="form-label ui-label">Membership OR <span class="text-danger">*</span></label>
                <input type="text" id="membership_or" name="membership_or" class="form-control" readonly>
              </div>
              <div class="col-lg-2">
                <label for="membership_date" class="form-label ui-label">Membership Date <span class="text-danger">*</span></label>
                <input type="date" id="membership_date" name="membership_date" class="form-control" readonly>
              </div>
              <div class="col-lg-2">
                <label for="meter_or_no" class="form-label ui-label">Meter OR #</label>
                <input type="text" id="meter_or_no" name="meter_or_no" class="form-control">
              </div>
            </div>

            <div class="row g-3">
              <div class="col-lg-3">
                <label for="care_of" class="form-label ui-label">Care of</label>
                <input type="text" id="care_of" name="care_of" class="form-control">
              </div>
              <div class="col-lg-3">
                <label for="contact_no" class="form-label ui-label">Contact No.</label>
                <input type="text" id="contact_no" name="contact_no" class="form-control">
              </div>
              <div class="col-lg-3">
                <label for="email" class="form-label ui-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control">
              </div>
              <div class="col-lg-3">
                <label for="process_date" class="form-label ui-label">Process Date <span class="text-danger">*</span></label>
                <input type="date" id="process_date" name="process_date" class="form-control" required>
              </div>
            </div>
          </div>
        </div>

        <!-- Location Details -->
        <div class="card ui-card mb-3">
          <div class="card-header ui-card-header">
            <i class="fas fa-location-dot me-2"></i>Location Details
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-1">
                <label for="feeder" class="form-label ui-label">Feeder <span class="text-danger">*</span></label>
                <select id="feeder" class="form-control" name="feeder" required>
                  <option value=""></option>
                  @foreach (Config::get('constants.feeders') as $feeder)
                    <option value="{{ $feeder['name'] }}" {{ old('feeder') == $feeder['name'] ? 'selected' : '' }}>{{ $feeder['name'] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-1">
                <label for="area" class="form-label ui-label">Area <span class="text-danger">*</span></label>
                <select id="area" class="form-control" name="area" required>
                  <option value=""></option>
                  @foreach (Config::get('constants.coverage_areas') as $area)
                    <option value="{{ $area['id'] }}" {{ old('area') == $area['id'] ? 'selected' : '' }}>{{ $area['name'] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2">
                <label for="municipality" class="form-label ui-label">Municipality <span class="text-danger">*</span></label>
                <select id="municipality" class="form-control" name="municipality" required>
                  <option value=""></option>
                  @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality->id }}" id="{{ $municipality->id }}">{{ $municipality->municipality_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2">
                <label for="barangay" class="form-label ui-label">Barangay <span class="text-danger">*</span></label>
                <select id="barangay" class="form-control" name="barangay" required></select>
              </div>
              <div class="col-lg-2">
                <label for="sitio" class="form-label ui-label">Sitio</label>
                <input type="text" id="sitio" name="sitio" class="form-control">
              </div>
              <div class="col-lg-4">
                <label for="location" class="form-label ui-label">Landmark <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="location" id="location" required>
              </div>
            </div>
          </div>
        </div>

        <!-- kWh Meter Request / Liquidation -->
        <div class="card ui-card mb-3">
          <div class="card-header ui-card-header">
            <i class="fas fa-bolt me-2"></i>kWh Meter Request
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-4">
                <label for="kwh_meter_request_control_no" class="form-label ui-label">kWh Meter Request <span class="text-danger">*</span></label>
                <select id="kwh_meter_request_control_no" class="form-control" name="kwh_meter_request_control_no" required>
                  <option value="">Select kWh Meter Request</option>
                  @foreach ($kwh_meter_requests as $key => $control_no)
                    <option value="{{ $key }}" {{ old('kwh_meter_request_control_no') == $control_no ? 'selected' : '' }}>
                      {{ $control_no }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-3">
                <label for="liquidation_requested_by" class="form-label ui-label">Requested By</label>
                <input type="text" id="liquidation_requested_by" name="liquidation_requested_by" class="form-control" readonly>
              </div>
              <div class="col-lg-5">
                <label for="liquidation_meter_type" class="form-label ui-label">Meter Type</label>
                <input type="text" id="liquidation_meter_type" name="liquidation_meter_type" class="form-control" readonly>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card ui-card mb-3 h-100">
                    <div class="card-header ui-card-header">
                        <i class="fas fa-note-sticky me-2"></i>Remarks
                    </div>
                    <div class="card-body d-flex">
                      <textarea class="form-control flex-grow-1" name="remarks" id="remarks"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card ui-card mb-3 h-100">
                    <div class="card-header ui-card-header">
                        <i class="fas fa-receipt me-2"></i>Schedule of Fees
                    </div>
                    <div class="card-body">
                        @include('service_connect_order.schedule_of_fees')
                    </div>
                </div>
            </div>
        </div>

      </div>
    </div>
    <!-- Actions -->
    <div class="ui-form-actions text-end mt-3">
      <a class="btn btn-outline-secondary" href="{{ route('indexCM') }}">
        <i class="fas fa-xmark me-1"></i>Cancel
      </a>
      <button type="submit" class="btn ui-btn-cta">
        <i class="fas fa-check me-1"></i>Submit Request
      </button>
    </div>
  </form>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
      // Handle kWh meter request selection change
      $('#kwh_meter_request_control_no').on('change', function() {
          const controlNo = $(this).val();

          // Clear dependent fields
          $('#liquidation_requested_by').val('');
          $('#liquidation_meter_type').val('');

          if (controlNo) {
              // Fetch kWh meter request details
              $.ajax({
                  url: '{{ route("kwhMeterRequestDetails") }}',
                  type: 'GET',
                  data: { control_no: controlNo },
                  success: function(response) {
                      if (response.success) {
                          $('#liquidation_requested_by').val(response.data.requested_by);
                          $('#liquidation_meter_type').val(response.data.meter_type);
                      } else {
                          alert('Error: ' + response.message);
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('Error fetching kWh meter request details:', error);
                      alert('Error loading kWh meter request details. Please try again.');
                  }
              });
          }
      });
  });

  $( "#electric_service_detail" ).select2({
    ajax: {
      url: "{{route('cmFetchAccounts')}}",
      type: "get",
      dataType: 'json',
      data: function (params) {
        return {
            search: params.term, // search term
            page: params.page
        };
      },
      processResults:function (results, params){
        params.page = params.page||1;

        return{
          results:results.data,
          pagination:{
            more:results.last_page!=params.page
          },
        }
      },
      cache: true
    },
    templateResult: templateResult,
    templateSelection: templateSelection,
  });

  function templateResult(data){
  if (data.loading){
    return data.text
  }
  return data.id + " | " +data.Name
  }

  function templateSelection(data) {
      // Handle splitting the name
      var fullName = data.Name || "";
      var parts = fullName.split(',');

      var l_name = "";
      var f_name = "";

      if (parts.length > 1) {
          // With comma → Last, First
          l_name = parts[0].trim();
          f_name = parts[1].trim();
      } else {
          // No comma → put entire text in FIRST NAME
          f_name = fullName.trim();
          l_name = "N/A";
      }

      // Extract date only
      var dateOnlyString = (data.Date || "").split(' ')[0];

      var prevReading = parseFloat(data['Prev Reading']);

      document.getElementById('last_name').value = l_name;
      document.getElementById('first_name').value = f_name;
      document.getElementById('membership_or').value = data['OR No'];
      document.getElementById('membership_date').value = dateOnlyString;
      document.getElementById('consumer_type').value = data['Cons Type'];

      if (!isNaN(prevReading)) {
          document.getElementById('last_reading').value = prevReading.toFixed(0);
      }

      document.getElementById('old_meter').value = (data['Serial No'] || "").trim();

      return data.id + " | " + data.Name;
  }

  $('#municipality').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#barangay").html('');
      $.ajax({
          url: "{{url('api/fetch-barangays')}}",
          type: "POST",
          data: {
              municipality_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (res) {
              $('#barangay').html('<option value="">-- Select Barangay --</option>');
              $.each(res.barangays, function (key, value) {
                    $("#barangay").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
              });
          }
      });
  });
</script>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/ui-form-design.css') }}">
@endsection
