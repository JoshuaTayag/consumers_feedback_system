@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit Change Meter Order</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body" style="background-color: #fafafa">
          <form action="{{ route('updateCM', $change_meter_request->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-lg-8">
                    <div class="mb-2">
                        <label for="electric_service_details" class="form-label mb-1">Account Number</label>
                        <input type="text" id="electric_service_details" name="electric_service_details" class="form-control" value="{{ $change_meter_request->account_number }}" disabled>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-2">
                        <label for="old_meter" class="form-label mb-1">Old Meter</label>
                        <input type="text" id="old_meter" name="old_meter" class="form-control" value="{{ $change_meter_request->old_meter_no }}">
                    </div>
                  </div>
                </div>

                <code class="fs-4">Consumer Details</code>
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="last_name" class="form-label mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $change_meter_request->last_name }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="first_name" class="form-label mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $change_meter_request->first_name }}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="contact_no" class="form-label mb-1">Contact No.</label>
                        <input type="text" id="contact_no" name="contact_no" class="form-control" value="{{ $change_meter_request->contact_no }}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="email" class="form-label mb-1">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ $change_meter_request->email }}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="care_of" class="form-label mb-1">Care of</label>
                        <input type="text" id="care_of" name="care_of" class="form-control" value="{{ $change_meter_request->care_of }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <label for="feeder" class="form-label mb-1">Feeder *</label>
                    <select id="feeder" class="form-control" name="feeder" required>
                      <option value=""></option>
                      @foreach (Config::get('constants.feeders') as $feeder)          
                        <option value="{{ $feeder['name'] }}" id="" {{ $change_meter_request->feeder == $feeder['name'] ? 'selected' : ''}}>{{ $feeder['name'] }}</option>
                      @endforeach 
                    </select>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                        <label for="area" class="form-label mb-1">Area *</label>
                        <select id="area" class="form-control" name="area" value="{{ old('area')}}" required>
                          <option value=""></option>
                          <option value="1" {{ $change_meter_request->area == "1" ? 'selected' : ''}} >A1</option>
                          <option value="2" {{ $change_meter_request->area == "2" ? 'selected' : ''}} >A2</option>
                          <option value="3" {{ $change_meter_request->area == "3" ? 'selected' : ''}} >A3</option>
                          <option value="4" {{ $change_meter_request->area == "4" ? 'selected' : ''}} >A4</option>
                          <option value="5" {{ $change_meter_request->area == "5" ? 'selected' : ''}} >A5</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="municipality" class="form-label mb-1">Municipality *</label>
                        <select id="municipality" class="form-control" name="municipality" value="{{ old('municipality')}}" required>
                          <option value=""></option>
                          @foreach ($municipalities as $municipality)          
                            <option value="{{ $municipality->id }}" id="{{ $municipality->id }}" {{ $change_meter_request->municipality_id == $municipality->id ? 'selected' : ''}}>{{ $municipality->municipality_name }}</option>
                          @endforeach
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="barangay" class="form-label mb-1">Barangay *</label>
                        <select id="barangay" class="form-control" name="barangay" required>
                          <option value="{{ $change_meter_request->barangay_id }}" id="{{ $change_meter_request->barangay_id }}">{{$change_meter_request->barangay_id ? $change_meter_request->barangay->barangay_name : null }}</option>
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="sitio" class="form-label mb-1">Sitio</label>
                        <input type="text" id="sitio" name="sitio" class="form-control" value="{{ $change_meter_request->sitio }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="membership_or" class="form-label mb-1">Membership OR *</label>
                        <input type="text" id="membership_or" name="membership_or" class="form-control" value="{{ $change_meter_request->membership_or }}" readonly>
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                        <label for="consumer_type" class="form-label mb-1">Type *</label>
                        <input type="text" id="consumer_type" name="consumer_type" class="form-control" value="{{ $change_meter_request->consumer_type }}" readonly>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="meter_or_no" class="form-label mb-1">Meter OR #</label>
                        <input type="text" id="meter_or_no" name="meter_or_no" class="form-control" value="{{ $change_meter_request->meter_or_number }}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="process_date" class="form-label mb-1">Process Date *</label>
                        <input type="date" id="process_date" name="process_date" class="form-control" value="{{ date('Y-m-d', strtotime($change_meter_request->process_date)) }}" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-1">
                    <div class="mb-2">
                      <label for="last_reading" class="form-label mb-1">Last Reading</label>
                      <input type="number" id="last_reading" name="last_reading" class="form-control" readonly value="{{ $change_meter_request->last_reading }}">
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                      <label for="reading_initial" class="form-label mb-1">Ini Reading</label>
                      <input type="number" id="reading_initial" name="reading_initial" class="form-control" value="{{ $change_meter_request->initial_reading }}">
                    </div>
                  </div>
                  {{-- <div class="col-lg-4">
                    <div class="mb-2">
                      <label for="kwh_meter_request_control_no" class="form-label mb-1">kWh Meter Request *</label>
                        <select id="kwh_meter_request_control_no" class="form-control" name="kwh_meter_request_control_no" required>
                          <option value="">Select kWh Meter Request</option>
                          @foreach ($kwh_meter_requests as $key => $control_no)          
                            <option value="{{ $key }}" {{ old('kwh_meter_request_control_no') == $control_no ? 'selected' : ''}}>
                            {{ $control_no }}
                            </option>
                          @endforeach 
                        </select>
                    </div>
                  </div> --}}
                  <div class="col-lg-4">
                    <div class="mb-2">
                      <label for="kwh_meter_request_control_no" class="form-label mb-1">kWh Meter Request</label>
                        <select id="kwh_meter_request_control_no" class="form-control" name="kwh_meter_request_control_no" required>
                          <option value="">-- Select kWh Meter Request --</option>
                          @foreach ($kwh_meter_requests as $key => $control_no)          
                            <option value="{{ $key }}" {{ ($change_meter_request->kwh_meter_request_id == $key || old('kwh_meter_request_control_no') == $key) ? 'selected' : ''}}>
                            {{ $control_no }}
                            </option>
                          @endforeach 
                        </select>
                    </div>
                  </div>
                  {{-- <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="liquidation_requested_by" class="form-label mb-1">Requested By</label>
                      <input type="text" id="liquidation_requested_by" name="liquidation_requested_by" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="liquidation_meter_type" class="form-label mb-1">Meter Type</label>
                      <input type="text" id="liquidation_meter_type" name="liquidation_meter_type" class="form-control" readonly>
                    </div>
                  </div> --}}
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="liquidation_requested_by" class="form-label mb-1">Requested By</label>
                      <input type="text" id="liquidation_requested_by" value="{{ $change_meter_request->kwhMeterRequest->user->name ?? '' }}" name="liquidation_requested_by" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="liquidation_meter_type" class="form-label mb-1">Meter Type</label>
                      <input type="text" id="liquidation_meter_type" value="{{ $change_meter_request->kwhMeterRequest->meterType->meter_description ?? '' }}" name="liquidation_meter_type" class="form-control" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label class="form-label mb-1">Remarks </label>
                      <textarea class="form-control" name="remarks" id="remarks">{{ $change_meter_request->remarks ?? '' }}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label class="form-label mb-1">Landmark *</label>
                      <textarea class="form-control" name="location" id="location" required>{{ $change_meter_request->location ?? '' }}</textarea>
                    </div>
                  </div>
                </div>
                <code class="fs-4">Schedule of Fees</code>
                <hr>
                <div class="row">
                  <div class="col-lg-4">
                    @include('service_connect_order.schedule_of_fees')
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                  <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"><i class="fa fa-arrow-left me-2"></i>Back </a>
                  <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check me-2"></i>Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>

  function templateResult(data){
  if (data.loading){
    return data.text
  }
  return data.id + " | " +data.Name + " | " + data.Address
  }

  function templateSelection(data){

  // Assuming data.Name contains a full name
  var fullName = data.Name;
  // Split the full name into parts using a space delimiter
  var partsOfFullName = fullName.split(',');
  // Extract the first name and last name
  var l_name = partsOfFullName[0];
  var f_name = partsOfFullName[1];

  // Assuming data.Date contains the datetime string "1999-08-05 00:00:00"
  var dateTimeString = data.Date;
  var dateOnlyString = dateTimeString.split(' ')[0]; // Extract date part

  var trimmedFirstName = f_name.replace(/\s+$/g, '');
  var trimmedLastName = l_name.replace(/\s+$/g, '');

  document.getElementById('last_name').value = trimmedLastName;
  document.getElementById('first_name').value = trimmedFirstName;
  document.getElementById('membership_or').value = data['OR No'];
  document.getElementById('membership_date').value = dateOnlyString;
  // document.getElementById('last_reading').value = parseFloat(data['Prev Reading'].toFixed(0));
  var prevReading = parseFloat(data['Prev Reading']);
  if (!isNaN(prevReading)) {
      document.getElementById('last_reading').value = prevReading.toFixed(0);
  }
  // document.getElementById('municipality').value = municipality;


  return data.id + " | " +data.Name + " | " + data.Address
  
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

  // HANDLE THE LIQUIDATION PORTION
  $(document).ready(function() {
      // Handle kWh meter request selection change
      $('#kwh_meter_request_control_no').on('change', function() {
          const controlNo = $(this).val();
          
          // Clear dependent fields
          $('#liquidation_requested_by').val('');
          $('#liquidation_meter_type').val('');
          $('#meter_serial_number').html('<option value="">Select Serial Number</option>');
          
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
                          
                          // Load available serial numbers
                          loadSerialNumbers(controlNo);
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
</script>
@endsection
@section('style')
<style>
  #schedule_of_fees{
    border-radius: 10px;
    border: 1px gray;
    background: transparent;
    backdrop-filter: blur(8px);
  }

  /* .container {
      display: flex;
     
  } */
  
  .scrollbar {
    max-height: 450px; overflow-y: auto;
  }
  /*       ScrollBar 1        */
  
  #scrollbar1::-webkit-scrollbar {
      width: 10px;
  }
  
  #scrollbar1::-webkit-scrollbar-track {
      border-radius: 8px;
      background-color: #e7e7e7;
      border: 1px solid #cacaca;
  }
  
  #scrollbar1::-webkit-scrollbar-thumb {
      border-radius: 8px;
      background-color: #e19a00;
  }

  /* Style for disabled meter options */
  #meter_code_no option:disabled {
      color: #999;
      background-color: #f5f5f5;
      font-style: italic;
  }

  /* Style for available meter count display */
  .meter-availability-info {
      font-size: 12px;
      color: #666;
  }

  .meter-unavailable {
      color: #dc3545 !important;
  }

  .meter-available {
      color: #28a745 !important;
  }
</style>
@endsection