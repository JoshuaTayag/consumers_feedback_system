@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Change Meter Request</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-sm btn-success" href="{{ route('viewReport') }}" target="_blank"><i class="fa fa-download"></i> Generate Report</a>
                    @can('change-meter-request-create')
                      <a class="btn btn-sm btn-success" href="{{ route('createCM') }}"> Create New Request </a>
                    @endcan
                  </div>
              </div>
            </div>
            <form action="{{ route('cm.search') }}" method="GET">
              <div class="row p-3">
                <div class="col-lg-2">
                    <input type="text" placeholder="Search by Control No." id="search_sco_no" name="control_no" class="form-control" value="{{ request('control_no') }}">
                </div>
                <div class="col-lg-2">
                    <input type="text" placeholder="Search by First Name" id="search_first_name" name="first_name" class="form-control" value="{{ request('first_name') }}">
                </div>
                <div class="col-lg-2">
                    <input type="text" placeholder="Search by Last Name" id="search_last_name" name="last_name" class="form-control" value="{{ request('last_name') }}">
                </div>
                <div class="col-lg-2">
                    <input type="text" placeholder="Search by Meter No" id="search_meter_no" name="meter_no" class="form-control" value="{{ request('meter_no') }}">
                </div>
                <div class="col-lg-2">
                  <button type="submit" class="btn btn-info"><i class="fa fa-search"></i></button>
                  <button type="button" class="btn btn-info" onclick="clearSearch()">Clear</button>
                </div>
              </div>
            </form>
            <div class="card-body">
              <div class="row" id="show_data">
              @foreach ($cm_requests as $key => $cm_request)
                @php
                  $consumerTypes = collect(Config::get('constants.consumer_types'));
                  $consumerType = $consumerTypes->firstWhere('id', $cm_request->consumer_type);
                @endphp
                <div class="col-lg-4 mb-4">
                  <div class="card h-100">
                    <div class="card-header p-1 bg-{{ $cm_request->status == 3 ? 'warning' : ($cm_request->status == 1 || $cm_request->status == 2 ? 'success' : 'danger')}}"></div>
                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col d-flex align-items-center">
                          @if($cm_request->status == null || $cm_request->status == 3)
                            <div class="dropdown">
                              <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                @can('change-meter-request-edit')
                                  @if($cm_request->status == null)
                                    <li><a class="dropdown-item" href="{{ route('editCM',$cm_request->id) }}"><i class="fa fa-pencil"></i> Update</a></li>
                                  @endif
                                @endcan

                                <li><a class="dropdown-item" href="{{route('printChangeMeterRequest',$cm_request->id)}}" target="_blank"><i class="fa fa-print"></i> Print</a></li>

                                  @if($cm_request->status == 3)
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#meterPostingModal" data-name="{{$cm_request->last_name.', '.$cm_request->first_name}}" data-sco="{{$cm_request->control_no}}" data-id="{{$cm_request->id}}" data-area="{{$cm_request->area}}" data-feeder="{{$cm_request->feeder}}" data-process-date="{{ date('F d, Y', strtotime($cm_request->created_at)) }}"><i class="fa fa-clipboard-check"></i>&nbsp; Meter Posting</a></li>
                                  @endif

                                  @if($cm_request->status == null)
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#dispatchingModal" data-sco="{{$cm_request->control_no}}" data-id="{{$cm_request->id}}"><i class="fa fa-truck"></i>&nbsp; Dispatch</a></li>
                                  @endif

                                @can('change-meter-request-delete')
                                  @if($cm_request->status == null)
                                    <li><a class="dropdown-item" href="{{route('deleteCM',$cm_request->id)}}"><i class="fa fa-trash"></i> Delete</a></li>
                                  @endif
                                @endcan 
                              </ul>
                            </div>
                          @else
                            <a href="{{ route('viewCM', $cm_request->id) }}" type="submit" target="_blank" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                          @endif
                        </div>
                        <div class="col-lg-3 d-flex align-items-center">
                            <div class="mx-end ms-auto"> <!-- Add mx-auto to horizontally center the content -->
                                <p class="badge rounded-pill bg-{{ $cm_request->status == 1 || $cm_request->status == 2 ? 'success' : ($cm_request->status == 3 ? 'warning text-dark' : 'danger')}} p-2 mb-0">{{ $cm_request->status == 1 || $cm_request->status == 2 ? 'Acted' : ($cm_request->status == 3 ? 'Dispatched' : 'Not Acted')}}</p>
                            </div>
                        </div>
                      </div>
                      <div class="row border-bottom ">
                        <div class="col-lg-5 border-end">Control No. :</div>
                        <div class="col-lg-7 fw-bold">{{$cm_request->control_no}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Name:</div>
                        <div class="col-lg-7 ">{{$cm_request->last_name.', '.$cm_request->first_name}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Account:</div>
                        <div class="col-lg-7 ">{{ substr($cm_request->account_number, 0, 2) }}-{{ substr($cm_request->account_number, 2, 4) }}-{{ substr($cm_request->account_number, 6, 4) }} </div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Process Date:</div>
                        <div class="col-lg-7 ">{{ date('F d, Y', strtotime($cm_request->created_at)) }}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Area:</div>
                        <div class="col-lg-7 ">A{{$cm_request->area}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Address:</div>
                        <div class="col-lg-7 ">{{$cm_request->sitio.', '.$cm_request->barangay->barangay_name.', '. $cm_request->municipality->municipality_name}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Consumer Type:</div>
                        <div class="col-lg-7 ">{{ $consumerType['name'] ?? 'Unknown Type'}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Application Status:</div>
                        <!-- 1 = installed, 2 = rejected -->
                        <div class="col-lg-7 {{ $cm_request->status == null ? 'd-none' : 'd-block'}}"><span class="badge my-1 rounded-pill bg-{{$cm_request->status == 1 ? 'danger' : ($cm_request->status == 2 ? 'success' : 'warning text-dark') }} p-2 fs-6" >{{$cm_request->status == 1 ? 'ACTED - NOT COMPLETED' : ($cm_request->status == 2 ? 'ACTED - COMPLETED' : ($cm_request->status == 3 ? 'DISPATCHED' : 'UNACTED')) }}</span></div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Old Meter No.:</div>
                        <div class="col-lg-7 ">{{$cm_request->old_meter_no}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">New Meter No.:</div>
                        <div class="col-lg-7 text-{{$cm_request->new_meter_no ? '' : 'danger'}} ">{{$cm_request->new_meter_no ? $cm_request->new_meter_no : "N/A"}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Date Installed:</div>
                        <div class="col-lg-7 text-{{$cm_request->date_time_acted ? '' : 'danger'}}">{{ $cm_request->date_time_acted ? date('F d, Y h:i A', strtotime($cm_request->date_time_acted)) : 'N/A' }}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">Remarks:</div>
                        <div class="col-lg-7">{{$cm_request->remarks}}</div>
                      </div>
                      <div class="row border-bottom">
                        <div class="col-lg-5 border-end">OR No.:</div>
                        <div class="col-lg-7 {{$cm_request->changeMeterRequestTransaction ? 'fw-bold text-success' : ''}} ">{{$cm_request->changeMeterRequestTransaction ? $cm_request->changeMeterRequestTransaction->or_no : "None"}}</div>
                      </div>
                      <div class="row pt-3 text-muted">
                        <div class="col text-end">created by: {{$cm_request->created_name}}</div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
              <div id="pagination">{{ $cm_requests->links() }}</div>
            </div>
              @include('service_connect_order.change_meter.meter_posting')
              @include('service_connect_order.change_meter.dispatching')
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script>
  var meterPostingModal = document.getElementById('meterPostingModal');
  var dispatchingModal = document.getElementById('dispatchingModal');

  meterPostingModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    var sco = button.getAttribute('data-sco');
    var full_name = button.getAttribute('data-name');
    var process_date = button.getAttribute('data-process-date');
    var area = button.getAttribute('data-area');
    var feeder = button.getAttribute('data-feeder');
    var cm_id = button.getAttribute('data-id');

    // console.log(feeder)
    var modal_sco = meterPostingModal.querySelector('#sco');
    var modal_name = meterPostingModal.querySelector('#full_name');
    var modal_process_date = meterPostingModal.querySelector('#process_date');
    var modal_cm_id = meterPostingModal.querySelector('#cm_id');

    modal_sco.value = sco;
    modal_name.value = full_name;
    modal_process_date.value = process_date;
    modal_cm_id.value = cm_id;
  });

  dispatchingModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    var sco = button.getAttribute('data-sco');
    var cm_id = button.getAttribute('data-id');

    // Get today's date
    const today = new Date();
    
    // Format it as YYYY-MM-DD
    const formattedDate = today.toISOString().split('T')[0];

    // Format the time as HH:mm
    const formattedTime = today.toTimeString().slice(0, 5);

    console.log(formattedDate)
    var modal_sco = dispatchingModal.querySelector('#sco_dispatched');
    var modal_cm_id = dispatchingModal.querySelector('#cm_id');
    var modal_dispatched_date = dispatchingModal.querySelector('#date_dispatched');
    var modal_dispatched_time = dispatchingModal.querySelector('#time_dispatched');

    modal_sco.value = sco;
    modal_cm_id.value = cm_id;
    modal_dispatched_date.value = formattedDate;
    modal_dispatched_time.value = formattedTime;
  });

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
        crew.setAttribute('required', 'required');
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
        crew.removeAttribute('required');
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
    $('#search_sco_no').val('');
    $('#search_first_name').val('');
    $('#search_last_name').val('');
    $('#search_meter_no').val('');
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