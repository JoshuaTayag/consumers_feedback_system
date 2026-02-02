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
          {!! Form::open(array('route' => ['updateCM', $change_meter_request->id],'method'=>'PUT')) !!}
            <div class="row">
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-12 mb-3">
                    <span class="fs-4 fw-bold">Control #: <span class="text-danger">{{$change_meter_request->control_no}}</span></span>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-2">
                        <!-- {{ Form::label('account_no', 'Account No') }}
                        {{ Form::text('account_no', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }} -->
                        {{ Form::label('electric_service_details', 'Account Number') }}
                        {{ Form::text('electric_service_details', $change_meter_request->account_number, array('class' => 'form-control', 'disabled')) }}
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-2">
                        {{ Form::label('old_meter', 'Old Meter') }}
                        {{ Form::text('old_meter', $change_meter_request->old_meter_no, array('class' => 'form-control')) }}
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('last_name', 'Last Name') }}
                        {{ Form::text('last_name', $change_meter_request->last_name, array('class' => 'form-control', 'readonly', 'required')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('first_name', 'First Name') }}
                        {{ Form::text('first_name', $change_meter_request->first_name, array('class' => 'form-control', 'required')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('contact_no', 'Contact No.') }}
                        {{ Form::text('contact_no', $change_meter_request->contact_no, array('class' => 'form-control')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('care_of', 'Care of') }}
                        {{ Form::text('care_of', $change_meter_request->care_of, array('class' => 'form-control')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <label for="feeder">Feeder *</label>
                    <!-- <input type="text" value="" id="care_of" name="care_of" class="form-control" readonly> -->
                    <select id="feeder" class="form-control" name="feeder" required>
                      <option value=""></option>
                      @foreach (Config::get('constants.feeders') as $feeder)          
                        <option value="{{ $feeder['name'] }}" id="" {{ $change_meter_request->feeder == $feeder['name'] ? 'selected' : ''}}>{{ $feeder['name'] }}</option>
                      @endforeach 
                    </select>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                        {{ Form::label('area', 'Area *') }}
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
                        {{ Form::label('municipality', 'Municipality *') }}
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
                        {{ Form::label('barangay', 'Barangays *') }}
                        <select id="barangay" class="form-control" name="barangay" required>
                          <!-- <option value=""></option> -->
                          <option value="{{ $change_meter_request->barangay_id }}" id="{{ $change_meter_request->barangay_id }}">{{$change_meter_request->barangay_id ? $change_meter_request->barangay->barangay_name : null }}</option>
                          <!-- @foreach ($barangays as $barangay)        
                            <option value="{{ $barangay->Brgy }}" {{ $change_meter_request->Brgy == rtrim($barangay->Brgy) ? 'selected' : ''}}>{{ $barangay->Brgy }}</option>
                          @endforeach  -->
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('sitio', 'Sitio *') }}
                        {{ Form::text('sitio', $change_meter_request->sitio, array('class' => 'form-control', 'required')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('membership_or', 'Membership OR *') }}
                        {{ Form::text('membership_or', $change_meter_request->membership_or, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      {{ $change_meter_request->{'Membership Date'} }}
                        {{ Form::label('membership_date', 'Membership Date *') }}
                        {{ Form::date('membership_date', date('Y-m-d', strtotime($change_meter_request->{'Membership Date'})) , array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                        {{ Form::label('consumer_type', 'Type *') }}
                        {{ Form::text('consumer_type', $change_meter_request->consumer_type, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('meter_or_no', 'Meter OR #') }}
                        {{ Form::text('meter_or_no', $change_meter_request->meter_or_number, array('class' => 'form-control')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('process_date', 'Process Date *') }}
                        {{ Form::date('process_date', $change_meter_request->process_date, array('class' => 'form-control', 'required')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-8" id="meter_type_section" style="display: {{ $change_meter_request->kwh_meter_request_id ? 'none' : 'block' }};">
                    <div class="mb-2">
                        {{ Form::label('meter_code_no', 'Type Of Meter*') }}
                        <select id="meter_code_no" class="form-control" name="meter_code_no" {{ $change_meter_request->kwh_meter_request_id ? '' : 'required' }}>
                          <option value=""></option>
                          @foreach ($type_of_meters as $type_of_meter)          
                            <option value="{{ $type_of_meter->id }}" 
                                    id="" 
                                    {{ $change_meter_request->type_of_meter == $type_of_meter->id ? 'selected' : ''}}
                                    {{ $type_of_meter->available_count <= 0 ? 'disabled' : '' }}
                                    data-available-count="{{ $type_of_meter->available_count }}">
                              {{ $type_of_meter->meter_code }} - {{ $type_of_meter->meter_description }} 
                              (Available: {{ $type_of_meter->available_count }})
                            </option>
                          @endforeach 
                        </select>
                    </div>
                  </div>
                  
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('last_reading', 'Last Reading') }}
                        {{ Form::number('last_reading', $change_meter_request->last_reading, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('reading_initial', 'Initial Reading') }}
                        {{ Form::number('reading_initial', $change_meter_request->initial_reading, array('class' => 'form-control')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label class="form-label mb-1">Remarks </label>
                      <textarea class="form-control" name="remarks" id="remarks">{{ $change_meter_request->remarks }}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label class="form-label mb-1">Landmark </label>
                      <textarea class="form-control" name="location" id="location">{{ $change_meter_request->location }}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 mb-3" id="schedule_of_fees" >
                <div class="col text-center"><h2>Schedule of Fees</h2></div>
                @if($change_meter_request->changeMeterRequestTransaction)
                  <span class="text-center fw-bold text-warning fs-3">OR: {{ $change_meter_request->changeMeterRequestTransaction->or_no }}</span>
                  
                  <ol class="list-group list-group-numbered">
                    @if(isset($change_meter_request->cmr_fees) && $change_meter_request->cmr_fees->isNotEmpty())
                        @foreach($change_meter_request->cmr_fees as $cm_fees)
                            <li class="list-group-item bg-secondary text-white text-capitalize fw-bold">
                                {{ str_replace('_', ' ', $cm_fees->fees) }} - ₱{{ number_format($cm_fees->amount, 2, '.', '') }}
                            </li>
                        @endforeach
                    @else
                        <!-- <li class="list-group-item bg-secondary text-white text-capitalize fw-bold">No fees available</li> -->
                    @endif
                  </ol>

                @else
                  @include('service_connect_order.schedule_of_fees')
                @endif
              </div>

              <div class="col-lg-8">
                <code class="fs-4">Liquidation Details</code>
                <hr>
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="kwh_meter_request_control_no" class="form-label mb-1">kWh Meter Request</label>
                          <select id="kwh_meter_request_control_no" class="form-control" name="kwh_meter_request_control_no">
                            <option value="">-- Select kWh Meter Request --</option>
                            @foreach ($kwh_meter_requests as $key => $control_no)          
                              <option value="{{ $key }}" {{ ($change_meter_request->kwh_meter_request_id == $key || old('kwh_meter_request_control_no') == $key) ? 'selected' : ''}}>
                              {{ $control_no }}
                              </option>
                            @endforeach 
                          </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-2">
                        <label for="liquidation_requested_by" class="form-label mb-1">Requested By</label>
                        {{-- <input type="text" id="liquidation_requested_by" name="liquidation_requested_by" class="form-control" readonly> --}}
                        <input type="text" id="liquidation_requested_by" value="{{ $change_meter_request->kwhMeterRequest->user->name ?? '' }}" name="liquidation_requested_by" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="col-lg-5">
                      <div class="mb-2">
                        <label for="liquidation_meter_type" class="form-label mb-1">Meter Type</label>
                        {{-- <input type="text" id="liquidation_meter_type" name="liquidation_meter_type" class="form-control" readonly> --}}
                        <input type="text" id="liquidation_meter_type" value="{{ $change_meter_request->kwhMeterRequestSerialNumbers->first()?->meter?->meterType?->meter_description ?? '' }}" name="liquidation_meter_type" class="form-control" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="meter_serial_number" class="form-label mb-1">Serial Number</label>
                          <select id="meter_serial_number" class="form-control" name="meter_serial_number">
                              @forelse($change_meter_request->kwhMeterRequestSerialNumbers as $serialNumber)
                                  <option value="{{ $serialNumber->id }}" selected>
                                      {{ $serialNumber->meter->serial_number ?? 'No Serial Number' }}
                                  </option>
                              @empty
                                  <option value="">No serial numbers assigned</option>
                              @endforelse
                          </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="liquidation_erc_seal" class="form-label mb-1">ERC Seal</label>
                        <input type="text" id="liquidation_erc_seal" value="@if($change_meter_request->kwhMeterRequestSerialNumbers->count() > 0 && $change_meter_request->kwhMeterRequestSerialNumbers->first()->meter){{ $change_meter_request->kwhMeterRequestSerialNumbers->first()->meter->erc_seal_number }}@endif" name="liquidation_erc_seal" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="liquidation_leyeco_seal" class="form-label mb-1">Leyeco 5 Seal</label>
                        <input type="text" id="liquidation_leyeco_seal" value="@if($change_meter_request->kwhMeterRequestSerialNumbers->count() > 0 && $change_meter_request->kwhMeterRequestSerialNumbers->first()->meter){{ $change_meter_request->kwhMeterRequestSerialNumbers->first()->meter->leyeco_seal_number }}@endif" name="liquidation_leyeco_seal" class="form-control" readonly>
                      </div>
                    </div>
                    <input type="hidden" id="liquidation_meter_serial_number" name="liquidation_meter_serial_number" class="form-control" readonly>
                </div>
              </div>

              <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                  <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"><i class="fa fa-arrow-left me-2"></i>Back </a>
                  <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-check me-2"></i>Submit</button>
              </div>
            </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $( "#electric_service_detail" ).select2({
    ajax: { 
      url: "{{route('fetchAccounts')}}",
      type: "get",
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
            // _token: '{{csrf_token()}}',
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
    // placeholder:'Search Account Number',
    templateResult: templateResult,
    templateSelection: templateSelection,
  });

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
      console.log(id);
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
          
          // Show/hide meter type section based on kWh meter request selection
          if (controlNo) {
              // Hide meter type section when kWh meter request is selected
              $('#meter_type_section').hide();
              $('#meter_code_no').removeAttr('required');
          } else {
              // Show meter type section when no kWh meter request is selected
              $('#meter_type_section').show();
              $('#meter_code_no').attr('required', 'required');
          }
          
          // Clear dependent fields
          $('#liquidation_requested_by').val('');
          $('#liquidation_meter_type').val('');
          $('#meter_serial_number').html('<option value="">Select Serial Number</option>');
          $('#liquidation_erc_seal').val('');
          $('#liquidation_leyeco_seal').val('');
          $('#liquidation_meter_serial_number').val('');
          
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
      
      // Handle serial number selection change
      $('#meter_serial_number').on('change', function() {
          const meterId = $(this).val();
          
          // Clear seal fields
          $('#liquidation_erc_seal').val('');
          $('#liquidation_leyeco_seal').val('');
          $('#liquidation_meter_serial_number').val('');
          
          if (meterId) {
              // Fetch meter seal details
              $.ajax({
                  url: '{{ route("meterSealDetails") }}',
                  type: 'GET',
                  data: { meter_id: meterId },
                  success: function(response) {
                      if (response.success) {
                          $('#liquidation_erc_seal').val(response.data.erc_seal || '');
                          $('#liquidation_leyeco_seal').val(response.data.leyeco_seal || '');
                          $('#liquidation_meter_serial_number').val(response.data.serial_number || '');
                      } else {
                          alert('Error: ' + response.message);
                      }
                  },
                  error: function(xhr, status, error) {
                      console.error('Error fetching meter seal details:', error);
                      alert('Error loading meter seal details. Please try again.');
                  }
              });
          }
      });
      
      // Function to load serial numbers for selected kWh meter request
      function loadSerialNumbers(controlNo) {
          $('#meter_serial_number').html('<option value="">Loading serial numbers...</option>');
          
          $.ajax({
              url: '{{ route("kwhMeterSerialNumbers") }}',
              type: 'GET',
              data: { 
                  control_no: controlNo, 
                  change_meter_request_id: '{{ $change_meter_request->id }}'
              },
              success: function(response) {
                  let options = '<option value="">Select Serial Number</option>';
                  
                  if (response.success && response.data.length > 0) {
                      response.data.forEach(function(meter) {
                          options += `<option value="${meter.id}">${meter.serial_number}</option>`;
                      });
                  } else {
                      options = '<option value="">No available serial numbers</option>';
                  }
                  
                  $('#meter_serial_number').html(options);
              },
              error: function(xhr, status, error) {
                  console.error('Error fetching serial numbers:', error);
                  $('#meter_serial_number').html('<option value="">Error loading serial numbers</option>');
                  alert('Error loading serial numbers. Please try again.');
              }
          });
      }
  });

  // Add event handler for meter type selection
  $('#meter_code_no').on('change', function() {
      var selectedOption = $(this).find('option:selected');
      var availableCount = selectedOption.data('available-count');
      
      // Check if the selected meter type has available meters
      if (availableCount <= 0 && selectedOption.val() !== '') {
          alert('Warning: No meters available for the selected meter type. Please choose a different meter type.');
          $(this).val(''); // Clear the selection
          return false;
      }
  });
  
  // Style options based on availability when page loads
  $('#meter_code_no option').each(function() {
      var availableCount = $(this).data('available-count');
      if (availableCount <= 0 && $(this).val() !== '') {
          $(this).addClass('meter-unavailable');
          $(this).append(' - OUT OF STOCK');
      } else if ($(this).val() !== '') {
          $(this).addClass('meter-available');
      }
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

  .container {
      display: flex;
     
  }
  
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