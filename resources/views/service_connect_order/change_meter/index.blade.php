@extends('layouts.app')

@section('content')
<div class="container">
  <!-- Dashboard for available and reserved meters -->
  <div class="row cmd-stats">
      <!-- Unacted Requests Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--unacted">
              <div class="cmd-stat-icon"><i class="fas fa-clipboard-list"></i></div>
              <div class="flex-grow-1">
                  <div class="cmd-stat-label">Unacted Requests</div>
                  <div class="cmd-stat-value">{{ $change_meter_status_count['total']['unacted'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown">
                      <span>Today<strong>{{ $change_meter_status_count['today']['unacted'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $change_meter_status_count['yesterday']['unacted'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $change_meter_status_count['old_transactions']['unacted'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>

      <!-- Dispatched Requests Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--dispatched">
              <div class="cmd-stat-icon"><i class="fas fa-truck"></i></div>
              <div class="flex-grow-1">
                  <div class="cmd-stat-label">Dispatched Requests</div>
                  <div class="cmd-stat-value">{{ $change_meter_status_count['total']['dispatched'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown">
                      <span>Today<strong>{{ $change_meter_status_count['today']['dispatched'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $change_meter_status_count['yesterday']['dispatched'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $change_meter_status_count['old_transactions']['dispatched'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>

      <!-- Acted - Not Completed Requests Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--progress">
              <div class="cmd-stat-icon"><i class="fas fa-exclamation-circle"></i></div>
              <div class="flex-grow-1">
                  <div class="cmd-stat-label">Acted &ndash; Not Completed</div>
                  <div class="cmd-stat-value">{{ $change_meter_status_count['total']['acted_not_completed'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown">
                      <span>Today<strong>{{ $change_meter_status_count['today']['acted_not_completed'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $change_meter_status_count['yesterday']['acted_not_completed'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $change_meter_status_count['old_transactions']['acted_not_completed'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>

      <!-- Acted - Completed Requests Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--completed">
              <div class="cmd-stat-icon"><i class="fas fa-check-circle"></i></div>
              <div class="flex-grow-1">
                  <div class="cmd-stat-label">Acted &ndash; Completed</div>
                  <div class="cmd-stat-value">{{ $change_meter_status_count['total']['acted_completed'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown">
                      <span>Today<strong>{{ $change_meter_status_count['today']['acted_completed'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $change_meter_status_count['yesterday']['acted_completed'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $change_meter_status_count['old_transactions']['acted_completed'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Toolbar: title, actions, search + filter -->
  <div class="cmd-toolbar">
    <div class="cmd-toolbar-top">
        <h1 class="cmd-toolbar-title">Change Meter Request</h1>
        <div class="cmd-toolbar-actions">
          <a class="btn btn-sm btn-success" href="{{ route('viewReport') }}" target="_blank"><i class="fa fa-download"></i> Generate Report</a>
          @can('change-meter-request-create')
            <a class="btn btn-sm ui-btn-cta" href="{{ route('createCM') }}"> Create New Request </a>
          @endcan
        </div>
    </div>
    <form action="{{ route('cm.search') }}" method="GET">
      <div class="cmd-toolbar-filters">
        <div class="cmd-search">
            <input type="text" placeholder="Search by Control No. / Account No. / Name / New or Old Meter No" id="search" name="search" class="form-control" value="{{ request('search') }}">
        </div>
        <div class="cmd-status-select">
          <select class="form-select" name="status" onchange="this.form.submit()">
              <option value="ALL" {{ request('status') == 'ALL' ? 'selected' : '' }}>All</option>
              <option value="unacted" {{ request('status') == 'unacted' ? 'selected' : '' }}>Unacted</option>
              <option value="dispatched" {{ request('status') == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
              <option value="acted_not_completed" {{ request('status') == 'acted_not_completed' ? 'selected' : '' }}>Acted - Not Completed</option>
              <option value="acted_completed" {{ request('status') == 'acted_completed' ? 'selected' : '' }}>Acted - Completed</option>
            </select>
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
          @if(request('search') || request('status'))
            <a href="{{ route('indexCM') }}" class="btn btn-outline-secondary" title="Clear All Filters">
                <i class="fas fa-times"></i> Clear All
            </a>
          @endif
        </div>
      </div>
    </form>
  </div>

  <!-- Results -->
  <div class="row" id="show_data">
  @foreach ($cm_requests as $key => $cm_request)
    @php
      // Same mapping as the original top-strip color: 3 = dispatched (warning),
      // 1 or 2 = acted (success), otherwise unacted (danger).
      $cmAccentClass = $cm_request->status == 3 ? 'warning' : (($cm_request->status == 2) ? 'success' : ($cm_request->status == 1 ? 'danger' : 'primary'));
    @endphp
    <div class="col-xl-4 col-md-6 mb-4">
      <div class="cmd-request-card cmd-request-card--{{ $cmAccentClass }}">
        <div class="cmd-request-head">
          <div>
            @if($cm_request->status == null || $cm_request->status == 3)
              <div class="dropdown">
                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                  Action
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                  <li><a class="dropdown-item" href="{{ route('viewCM', $cm_request->id) }}"><i class="fa fa-eye"></i> View</a></li>
                  @can('change-meter-request-edit')
                    @if($cm_request->status == null && $cm_request->new_meter_no == null)
                      <li><a class="dropdown-item" href="{{ route('editCM',$cm_request->id) }}"><i class="fa fa-pencil"></i> Update</a></li>
                    @endif
                  @endcan

                  <li><a class="dropdown-item" href="{{route('printChangeMeterRequest',$cm_request->id)}}" target="_blank"><i class="fa fa-print"></i> Print</a></li>

                    @if($cm_request->status == 3)
                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#transferRequestModal" data-sco="{{$cm_request->control_no}}" data-id="{{$cm_request->id}}" data-crew-id="{{$cm_request->crew}}"><i class="fa fa-shuffle"></i>&nbsp; Transfer Request</a></li>
                      <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#meterPostingModal" data-assign-meter="{{ $cm_request->assignedMeter }}" data-name="{{$cm_request->last_name.', '.$cm_request->first_name}}" data-sco="{{$cm_request->control_no}}" data-id="{{$cm_request->id}}" data-process-date="{{ date('F d, Y', strtotime($cm_request->created_at)) }}"><i class="fa fa-clipboard-check"></i>&nbsp; Meter Posting</a></li>
                    @endif

                    @can('change-meter-request-dispatch')
                      @if($cm_request->status == null && $cm_request->kwh_meter_request_id != null && $cm_request->new_meter_no != null)
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#dispatchingModal"
                          data-dispatch-sco="{{$cm_request->control_no}}"
                          data-dispatch-id="{{$cm_request->id}}"
                          data-dispatch-name="{{$cm_request->last_name.', '.$cm_request->first_name}}"
                          data-dispatch-address="{{$cm_request->address}}"
                          data-dispatch-kwhMeterControlNo="{{$cm_request->kwhMeterRequest->control_no}}"
                          data-dispatch-kwhMeterType="{{$cm_request->kwhMeterRequest->meterType->meter_code ?? ''}}"
                          data-dispatch-serial="{{$cm_request->new_meter_no}}"><i class="fa fa-truck"></i>&nbsp; Dispatch</a></li>
                      @endif
                    @endcan
                    @can('change-meter-request-assign')
                      @if($cm_request->status == null && $cm_request->kwh_meter_request_id != null && $cm_request->new_meter_no == null)
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#assigningModal"
                          data-sco="{{$cm_request->control_no}}"
                          data-id="{{$cm_request->id}}"
                          data-name="{{$cm_request->last_name.', '.$cm_request->first_name}}"
                          data-address="{{$cm_request->address}}"
                          data-kwhMeterControlNo="{{$cm_request->kwhMeterRequest->control_no}}"
                          data-kwhMeterType="{{$cm_request->kwhMeterRequest->meterType->meter_code ?? ''}}"
                          data-kwhMeterId="{{$cm_request->kwh_meter_request_id}}" ><i class="fa fa-tasks"></i>&nbsp; Assign Meter</a></li>
                      @endif
                    @endcan

                  @can('change-meter-request-delete')
                    @if($cm_request->status == null)
                      <li><a class="dropdown-item delete-cm" href="{{route('deleteCM',$cm_request->id)}}"><i class="fa fa-trash"></i> Delete</a></li>
                    @endif
                  @endcan
                </ul>
              </div>
            @else
              <a href="{{ route('viewCM', $cm_request->id) }}" type="submit" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
            @endif
          </div>
          <span class="badge cmd-badge rounded-pill cmd-badge--{{$cm_request->status == null ? 'primary' : ($cm_request->status == 2 ? 'success' : ($cm_request->status == 1 ? 'danger' : 'warning')) }} p-2">{{$cm_request->status == 1 ? 'ACTED - NOT COMPLETED' : ($cm_request->status == 2 ? 'ACTED - COMPLETED' : ($cm_request->status == 3 ? 'DISPATCHED' : 'UNACTED')) }}</span>
          {{-- <p class="badge rounded-pill bg-{{ $cmAccentClass }}{{ $cmAccentClass === 'warning' ? ' text-dark' : '' }} p-2 mb-0">{{ $cm_request->status == 1 || $cm_request->status == 2 ? 'Acted' : ($cm_request->status == 3 ? 'Dispatched' : 'Unacted')}}</p> --}}
        </div>
        <div class="cmd-request-body">
          <div class="cmd-request-grid">
            <div class="cmd-request-row"><span class="cmd-label">Control No.</span><span class="cmd-value fw-bold">{{$cm_request->control_no}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Name</span><span class="cmd-value">{{$cm_request->last_name.', '.$cm_request->first_name}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Account</span><span class="cmd-value"><a style="text-decoration: none;" target="_blank" href="{{ route('ledger.search', ['account_no' => $cm_request->account_number]) }}">{{ substr($cm_request->account_number, 0, 2) }}-{{ substr($cm_request->account_number, 2, 4) }}-{{ substr($cm_request->account_number, 6, 4) }}</a></span></div>
            <div class="cmd-request-row"><span class="cmd-label">Process Date</span><span class="cmd-value">{{ date('F d, Y', strtotime($cm_request->created_at)) }}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Area</span><span class="cmd-value">A{{$cm_request->area}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Address</span><span class="cmd-value">{{$cm_request->address}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Consumer Type</span><span class="cmd-value">{{ $cm_request->consumer_type ?? 'Unknown Type'}}</span></div>
            <div class="cmd-request-row {{ $cm_request->status == null ? 'd-none' : '' }}">
              <span class="cmd-label">Application Status</span>
              <!-- 1 = installed, 2 = rejected -->
              <span class="cmd-value"></span>
            </div>
            <div class="cmd-request-row"><span class="cmd-label">Crew</span><span class="cmd-value">{{$cm_request->crew_full_name}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Old Meter No.</span><span class="cmd-value">{{$cm_request->old_meter_no}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">New Meter No.</span><span class="cmd-value text-{{$cm_request->new_meter_no ? '' : 'danger'}}">{{$cm_request->new_meter_no ? $cm_request->new_meter_no : "N/A"}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">{{ $cm_request->status == 1 ? 'Date Acted' : 'Date Installed'}}</span><span class="cmd-value text-{{$cm_request->date_time_acted ? '' : 'danger'}}">{{ $cm_request->date_time_acted ? date('F d, Y h:i A', strtotime($cm_request->date_time_acted)) : 'N/A' }}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Landmark</span><span class="cmd-value">{{$cm_request->location}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">Remarks</span><span class="cmd-value">{{$cm_request->remarks}}</span></div>
            <div class="cmd-request-row"><span class="cmd-label">OR No.</span><span class="cmd-value {{$cm_request->changeMeterRequestTransaction ? 'fw-bold text-success' : ''}}">{{$cm_request->changeMeterRequestTransaction ? $cm_request->changeMeterRequestTransaction->or_no : "None"}}</span></div>
          </div>
        </div>
        <div class="cmd-request-footer">created by: {{$cm_request->created_name}}</div>
      </div>
    </div>
  @endforeach
  </div>
  <div id="pagination">{{ $cm_requests->links() }}</div>

  @include('service_connect_order.change_meter.meter_posting')
  @include('service_connect_order.change_meter.dispatch')
  @include('service_connect_order.change_meter.meter_assign')
  @include('service_connect_order.change_meter.transfer_request')
</div>
@endsection
@section('script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-cm').forEach(function(el) {
      el.addEventListener('click', function(e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        Swal.fire({
          title: 'Are you sure?',
          text: "This action cannot be undone.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = href;
          }
        });
      });
    });
  });
  
  var meterPostingModal = document.getElementById('meterPostingModal');
  var dispatchingModal = document.getElementById('dispatchingModal');
  var transferRequestModal = document.getElementById('transferRequestModal');

  meterPostingModal.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget;

      var sco = button.getAttribute('data-sco');
      var full_name = button.getAttribute('data-name');
      var process_date = button.getAttribute('data-process-date');
      var cm_id = button.getAttribute('data-id');
      var assigned_meter = button.getAttribute('data-assign-meter');

      // Parse the JSON data from assigned_meter
      var meterDetails = null;
      try {
          if (assigned_meter && assigned_meter !== 'null' && assigned_meter !== '') {
              meterDetails = JSON.parse(assigned_meter);
          }
      } catch (e) {
          console.error('Error parsing meter details:', e);
          meterDetails = null;
      }

      // Get modal elements
      var modal_sco = meterPostingModal.querySelector('#sco');
      var modal_name = meterPostingModal.querySelector('#full_name');
      var modal_process_date = meterPostingModal.querySelector('#process_date');
      var modal_cm_id = meterPostingModal.querySelector('#cm_id');
      var modal_meter_no = meterPostingModal.querySelector('#meter_no');
      var modal_seal_no = meterPostingModal.querySelector('#seal_no');
      var modal_erc_seal = meterPostingModal.querySelector('#erc_seal');

      // Set basic fields
      modal_sco.value = sco;
      modal_name.value = full_name;
      modal_process_date.value = process_date;
      modal_cm_id.value = cm_id;

      // Set meter details from parsed JSON and handle readonly attribute
      if (meterDetails) {
          // Populate fields with meter details
          modal_meter_no.value = meterDetails.serial_number || '';
          modal_seal_no.value = meterDetails.leyeco_seal_number || '';
          modal_erc_seal.value = meterDetails.erc_seal_number || '';
          
          // Add readonly attribute to prevent editing
          modal_meter_no.setAttribute('readonly', true);
          modal_seal_no.setAttribute('readonly', true);
          modal_erc_seal.setAttribute('readonly', true);
          
          // Optional: Add visual styling to indicate readonly state
          modal_meter_no.classList.add('bg-light');
          modal_seal_no.classList.add('bg-light');
          modal_erc_seal.classList.add('bg-light');
          
      } else {
          // Clear fields if no meter details available
          modal_meter_no.value = '';
          modal_seal_no.value = '';
          modal_erc_seal.value = '';
          
          // Remove readonly attribute to allow editing
          modal_meter_no.removeAttribute('readonly');
          modal_seal_no.removeAttribute('readonly');
          modal_erc_seal.removeAttribute('readonly');
          
          // Remove visual styling
          modal_meter_no.classList.remove('bg-light');
          modal_seal_no.classList.remove('bg-light');
          modal_erc_seal.classList.remove('bg-light');
      }
  });

  transferRequestModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    console.log('Button that triggered the modal:');

    var sco = button.getAttribute('data-sco');
    var cm_id = button.getAttribute('data-id');
    var crew_id = button.getAttribute('data-crew-id');

    // Get today's date
    const today = new Date();
    
    // Format it as YYYY-MM-DD
    const formattedDate = today.toISOString().split('T')[0];

    // Format the time as HH:mm
    const formattedTime = today.toTimeString().slice(0, 5);

    var modal_sco = transferRequestModal.querySelector('#sco_dispatched');
    var modal_cm_id = transferRequestModal.querySelector('#cm_id');
    var modal_dispatched_date = transferRequestModal.querySelector('#date_dispatched');

    modal_sco.value = sco;
    modal_cm_id.value = cm_id;
    modal_dispatched_date.value = formattedDate;
    $('#crew_dispatched_from').val(crew_id).trigger('change');
  });

  dispatchingModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    var sco = button.getAttribute('data-dispatch-sco');
    var cm_id = button.getAttribute('data-dispatch-id');
    var name = button.getAttribute('data-dispatch-name');
    var address = button.getAttribute('data-dispatch-address');
    var kwhMeterControlNo = button.getAttribute('data-dispatch-kwhMeterControlNo');
    var kwhMeterType = button.getAttribute('data-dispatch-kwhMeterType');
    var kwhMeterSerial = button.getAttribute('data-dispatch-serial');

    // Get today's date
    const today = new Date();
    
    // Format it as YYYY-MM-DD
    const formattedDate = today.toISOString().split('T')[0];


    var modal_sco = dispatchingModal.querySelector('#dispatching_sco');
    var modal_name = dispatchingModal.querySelector('#dispatching_name');
    var modal_address = dispatchingModal.querySelector('#dispatching_address');
    var modal_cm_id = dispatchingModal.querySelector('#cm_id');
    var modal_kwh_meter_request_id = dispatchingModal.querySelector('#dispatching_kwh_meter_request');
    var modal_kwhMeterType = dispatchingModal.querySelector('#dispatching_type_of_meter');
    var modal_kwhMeterSerial = dispatchingModal.querySelector('#dispatching_serial_number');
    var modal_dispatched_date = dispatchingModal.querySelector('#dispatching_date');

    modal_sco.value = sco;
    modal_cm_id.value = cm_id;
    modal_name.value = name;
    modal_address.value = address;
    modal_kwh_meter_request_id.value = kwhMeterControlNo;
    modal_kwhMeterType.value = kwhMeterType;
    modal_kwhMeterSerial.value = kwhMeterSerial;
    modal_dispatched_date.value = formattedDate;
  });

  assigningModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    var sco = button.getAttribute('data-sco');
    var cm_id = button.getAttribute('data-id');
    var name = button.getAttribute('data-name');
    var address = button.getAttribute('data-address');
    var kwhMeterControlNo = button.getAttribute('data-kwhMeterControlNo');
    var kwhMeterType = button.getAttribute('data-kwhMeterType');
    var kwh_meter_request_id = button.getAttribute('data-kwhMeterId');

    // Get today's date
    const today = new Date();
    
    // Format it as YYYY-MM-DD
    const formattedDate = today.toISOString().split('T')[0];

    var modal_sco = assigningModal.querySelector('#assigning_sco');
    var modal_name = assigningModal.querySelector('#assigning_name');
    var modal_address = assigningModal.querySelector('#assigning_address');
    var modal_cm_id = assigningModal.querySelector('#cm_id');
    var modal_kwhMeterControlNo = assigningModal.querySelector('#assigning_kwh_meter_request');
    var modal_kwhMeterType = assigningModal.querySelector('#assigning_type_of_meter');
    var modal_kwh_meter_request_id = assigningModal.querySelector('#kwh_meter_request_id');

    modal_sco.value = sco;
    modal_cm_id.value = cm_id;
    modal_name.value = name;
    modal_address.value = address;
    modal_kwhMeterControlNo.value = kwhMeterControlNo;
    modal_kwhMeterType.value = kwhMeterType;
    loadSerialNumbers(kwh_meter_request_id);
  });

  // Function to load serial numbers for selected kWh meter request
  function loadSerialNumbers(controlNo) {
      $('#meter_serial_number').html('<option value="">Loading serial numbers...</option>');
      
      $.ajax({
          url: '{{ route("kwhMeterSerialNumbers") }}',
          type: 'GET',
          data: { 
              // change_meter_request_id: controlNo
              control_no: controlNo
          },
          success: function(response) {
              let options = '<option value="">Select Serial Number</option>';
              // console.log('Response from server:', response);
              if (response.success && response.data.length > 0) {
                  response.data.forEach(function(meter) {
                      options += `<option value="${meter.id}">${meter.serial_number}</option>`;
                  });
              } else {
                  options = '<option value="">No available serial numbers</option>';
              }
              
              $('#assigning_serial_number').html(options);
          },
          error: function(xhr, status, error) {
              console.error('Error fetching serial numbers:', error);
              $('#assigning_serial_number').html('<option value="">Error loading serial numbers</option>');
              alert('Error loading serial numbers. Please try again.');
          }
      });
  }

  const application_status = document.getElementById('status');
  const time = document.getElementById('time');

  // Get the row element
  var meter_details_row = document.getElementById('meter_details');
  var address_details_row = document.getElementById('address_details');

  // Get all input elements inside the row
  var meter_details = meter_details_row.querySelectorAll('input, select');
  var address_details = address_details_row.querySelectorAll('input, select');

  // Add event listener to dropdown
  application_status.addEventListener('change', function() {
      // Toggle visibility of text field based on selected option
      if (application_status.value == 2) {
        date_acted.setAttribute('required', 'required');
        time.setAttribute('required', 'required');

        meter_details.forEach(function(input) {
            input.disabled = false;
            input.setAttribute('required', 'required');
        });
        address_details.forEach(function(input) {
            input.disabled = false;
            if(input.id !== 'care_of' && input.id !== 'last_reading' && input.id !== 'reading_initial'){
              input.setAttribute('required', 'required');
            }
        });

      } else {
        // Iterate through each input element and disable it
        meter_details.forEach(function(input) {
            input.disabled = true;
            input.value = '';
        });
        address_details.forEach(function(input) {
            input.disabled = true;
            input.value = '';
        });
        date_acted.removeAttribute('required');
        time.removeAttribute('required');

        $('#meter_no').removeClass('is-valid');
        $('#meter_no').removeClass('is-invalid');
        $('#error_meter').html('');

        $('#seal_no').removeClass('is-valid');
        $('#seal_no').removeClass('is-invalid');
        $('#error_seal').html('');

        $('#erc_seal').removeClass('is-valid');
        $('#erc_seal').removeClass('is-invalid');
        $('#error_erc_seal').html('');
        $('#submit_meter_posting').attr('disabled', false);
      }
  });

  $('#meter_no').blur(function(){
    var error_meter = '';
    var meter_no = $(this).val();
    if (meter_no) {
      $.ajax({
        url:"{{ route('validateMeterPosting') }}",
        method:"POST",
        data:{
          meter_no: meter_no, 
          _token: '{{csrf_token()}}'
        },
        success:function(result)
        {
          if(result[0] == 'unique') {
            $('#error_meter').html('<label class="text-success">Meter No. Available</label>');
            $('#meter_no').removeClass('is-invalid');
            $('#meter_no').addClass('is-valid');

            var hasInvalidField = false;
            meter_details.forEach(function(input) {
                if (input.classList.contains('is-invalid')) {
                    hasInvalidField = true;
                    return; // Exit the loop early if an invalid field is found
                }
            });

            if (hasInvalidField) {
                $('#submit_meter_posting').attr('disabled', 'disabled');
            } else {
                $('#submit_meter_posting').attr('disabled', false);
            }
          }
          else {
            $('#error_meter').html('<label class="text-danger">Meter No. not Available! Pls refer to '+ result[1] +'</label>');
            $('#meter_no').removeClass('is-valid');
            $('#meter_no').addClass('is-invalid');
            $('#submit_meter_posting').attr('disabled', 'disabled');
          }
        }
      })
    }
    else{
      $('#meter_no').removeClass('is-invalid');
      $('#meter_no').removeClass('is-valid');
      $('#error_meter').html('');
      $('#submit_meter_posting').attr('disabled', false);
    }
  });

  $('#seal_no').blur(function(){
    var error_seal = '';
    var seal_no = $(this).val();
    if (seal_no) {
      $.ajax({
        url:"{{ route('validateMeterPosting') }}",
        method:"POST",
        data:{
          seal_no: seal_no, 
          _token: '{{csrf_token()}}'
        },
        success:function(result)
        {
          if(result[0] == 'unique') {
            $('#error_seal').html('<label class="text-success">Seal No. Available</label>');
            $('#seal_no').removeClass('is-invalid');
            $('#seal_no').addClass('is-valid');

            var hasInvalidField = false;
            meter_details.forEach(function(input) {
                if (input.classList.contains('is-invalid')) {
                    hasInvalidField = true;
                    return; // Exit the loop early if an invalid field is found
                }
            });

            if (hasInvalidField) {
                $('#submit_meter_posting').attr('disabled', 'disabled');
            } else {
                $('#submit_meter_posting').attr('disabled', false);
            }
          }
          else {
            $('#error_seal').html('<label class="text-danger">Seal No. not Available! Pls refer to SCO NO '+ result[1] +'</label>');
            $('#seal_no').removeClass('is-valid');
            $('#seal_no').addClass('is-invalid');
            $('#submit_meter_posting').attr('disabled', 'disabled');
          }
        }
      })
    }
    else{
      $('#seal_no').removeClass('is-invalid');
      $('#seal_no').removeClass('is-valid');
      $('#error_seal').html('');
      $('#submit_meter_posting').attr('disabled', false);
    }
  });

  $('#erc_seal').blur(function(){
    var error_erc_seal = '';
    var erc_seal_no = $(this).val();
    if (erc_seal_no) {
      $.ajax({
        url:"{{ route('validateMeterPosting') }}",
        method:"POST",
        data:{
          erc_seal: erc_seal_no, 
          _token: '{{csrf_token()}}'
        },
        success:function(result)
        {
          if(result[0] == 'unique') {
            $('#error_erc_seal').html('<label class="text-success">ERC Seal No. Available</label>');
            $('#erc_seal').removeClass('is-invalid');
            $('#erc_seal').addClass('is-valid');
            // $('#submit_meter_posting').attr('disabled', false);
            var hasInvalidField = false;
            meter_details.forEach(function(input) {
                if (input.classList.contains('is-invalid')) {
                    hasInvalidField = true;
                    return; // Exit the loop early if an invalid field is found
                }
            });

            if (hasInvalidField) {
                $('#submit_meter_posting').attr('disabled', 'disabled');
            } else {
                $('#submit_meter_posting').attr('disabled', false);
            }
          }
          else {
            $('#error_erc_seal').html('<label class="text-danger">ERC Seal No. not Available! Pls refer to SCO NO '+ result[1] +'</label>');
            $('#erc_seal').removeClass('is-valid');
            $('#erc_seal').addClass('is-invalid');
            $('#submit_meter_posting').attr('disabled', 'disabled');
          }
        }
      })
    }
    else{
      $('#erc_seal').removeClass('is-invalid');
      $('#erc_seal').removeClass('is-valid');
      $('#error_seal').html('');
      
      
    }
  });

  function clearSearch() {
    $('#search').val('');
  }

  document.getElementById('myForm').addEventListener('submit', function(event) {
      // Check if any form field is invalid
      if (!this.checkValidity()) {
          // If any field is invalid, prevent form submission
          event.preventDefault();
          // Optionally, you can display an error message or perform other actions
          alert('Please fill in all required fields correctly.');
      }
  });
</script>
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('css/ui-form-design.css') }}">
<link rel="stylesheet" href="{{ asset('css/change-meter-dashboard.css') }}">
@endsection