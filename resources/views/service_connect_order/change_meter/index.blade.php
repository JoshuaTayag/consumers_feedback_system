@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Service Connect Order</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    @can('service-connect-order-create')
                      <a class="btn btn-sm btn-success" href="{{ route('change-meter-request.create') }}"> Create New SCO </a>
                    @endcan
                  </div>
              </div>
            </div>
            <form action="{{ route('cm.search') }}" method="GET">
              <div class="row p-3">
                <div class="col-lg-2">
                    <input type="text" placeholder="Search by SCO No." id="search_sco_no" name="sco_no" class="form-control" value="{{ request('sco_no') }}">
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
            <div class="card-body {{ count($cm_requests) == 3 ? 'd-flex flex-wrap' : '' }}">
              <div class="row" id="show_data">
              @foreach ($cm_requests as $key => $sco)
                <div class="col-lg-4 mb-4">
                  <div class="card h-100">
                    <div class="card-header p-1 bg-{{ $sco->Dispatch2 ? 'success' : 'danger' }}"></div>
                    <div class="card-body">
                      <div class="row mb-3">
                        <div class="col d-flex align-items-center">
                            @if($sco->Acted == 0 || $sco->Dispatch2 == 'NOT COMPLETED')
                                <a class="btn btn-sm btn-secondary rounded-pill me-2" href="{{ route('editCM',$sco->application_id) }}">
                                    <i class="bi bi-gear"></i> Update
                                </a>
                                <!-- <a class="btn btn-sm btn-secondary rounded-pill" href="{{ route('editCM',$sco->application_id) }}">
                                    <i class="bi bi-gear"></i> Meter Posting
                                </a> -->
                                <button type="button" class="btn btn-sm btn-secondary rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" data-name="{{$sco->Lastname.', '.$sco->Firstname}}" data-sco="{{$sco->SCONo}}" data-area="{{$sco->Area}}" data-feeder="{{$sco->Feeder}}" data-process-date="{{ date('F d, Y', strtotime($sco->ProcessDate)) }}">
                                  Meter Posting
                                </button>
                            @endif
                            <a href="{{route('printChangeMeterRequest',$sco->application_id)}}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-print"></i></a>
                        </div>
                        <div class="col-lg-3 d-flex align-items-center">
                            <div class="mx-end ms-auto"> <!-- Add mx-auto to horizontally center the content -->
                                <p class="badge rounded-pill bg-{{ $sco->Dispatch2 ? 'success' : 'danger' }} p-2 mb-0">{{ $sco->Dispatch2 ? 'Acted' : 'Not Acted' }}</p>
                            </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col ">SCO No. :</div>
                        <div class="col fw-bold">{{$sco->SCONo}}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Name:</div>
                        <div class="col ">{{$sco->Lastname.', '.$sco->Firstname}}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Account:</div>
                        <div class="col ">{{ substr($sco->NextAcctNo, 0, 2) }}-{{ substr($sco->NextAcctNo, 2, 4) }}-{{ substr($sco->NextAcctNo, 6, 4) }} </div>
                      </div>
                      <div class="row">
                        <div class="col ">Process Date:</div>
                        <div class="col ">{{ date('F d, Y', strtotime($sco->ProcessDate)) }}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Area:</div>
                        <div class="col ">{{$sco->Area}}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Address:</div>
                        <div class="col ">{{$sco->Sitio.', '.$sco->Brgy.', '. $sco->Municipality}}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Consumer Type:</div>
                        <div class="col ">{{$sco->ConsumerType}}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Application Status:</div>
                        <div class="col "><p class="badge rounded-pill bg-{{ $sco->Dispatch2 == 'INSTALLED' && $sco->MeterNo ? 'success' : ($sco->Dispatch2 == 'REJECTED' && !$sco->MeterNo ? 'danger' : 'warning')}} p-2 fs-6" >{{$sco->Dispatch2}}</p></div>
                      </div>
                      <div class="row">
                        <div class="col ">Meter No.:</div>
                        <div class="col ">{{$sco->MeterNo}}</div>
                      </div>
                      <div class="row">
                        <div class="col ">Date Installed:</div>
                        <div class="col ">{{ $sco->{'Date Installed'} ? date('F d, Y', strtotime($sco->{'Date Installed'})) : 'NONE' }}</div>
                      </div>
                      <div class="row">
                        <div class="col-lg-6">Remarks:</div>
                        <div class="col-lg-6">{{$sco->Remarks}}</div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
              <div id="pagination">{{ $cm_requests->links() }}</div>
            </div>
              @include('service_connect_order.change_meter.meter_posting')
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script>
  var myModal = document.getElementById('exampleModal');
  myModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;

    var sco = button.getAttribute('data-sco');
    var full_name = button.getAttribute('data-name');
    var process_date = button.getAttribute('data-process-date');
    var area = button.getAttribute('data-area');
    var feeder = button.getAttribute('data-feeder');

    // console.log(feeder)
    var modal_sco = myModal.querySelector('#sco');
    var modal_name = myModal.querySelector('#full_name');
    var modal_process_date = myModal.querySelector('#process_date');
    var modal_area = myModal.querySelector('#area');
    var modal_feeder = myModal.querySelector('#feeder');

    modal_sco.value = sco;
    modal_name.value = full_name;
    modal_process_date.value = process_date;
    modal_area.value = area;
    modal_feeder.value = feeder;
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
      if (application_status.value == 'INSTALLED') {
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
            $('#error_meter').html('<label class="text-danger">Meter No. not Available! Pls refer to SCO NO '+ result[1] +'</label>');
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