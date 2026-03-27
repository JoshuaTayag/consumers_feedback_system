@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Change Meter</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body" style="background-color: #fafafa">
          <form action="{{ route('storeCM') }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-lg-9">
                <div class="alert alert-warning">
                  Note: if the account number is not showing, this means that the account has pending change meter request.
                </div>
                <div class="row">
                  <div class="col-lg-8">
                    <div class="mb-2">
                        <label for="electric_service_detail" class="form-label mb-1">Account Number *</label><br>
                        <select class="form-control" id="electric_service_detail" name="electric_service_detail" style="width: 100%" required></select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-2">
                        <label for="old_meter" class="form-label mb-1">Old Meter</label>
                        <input type="text" id="old_meter" name="old_meter" class="form-control" readonly>
                    </div>
                  </div>
                </div>

                <code class="fs-4">Consumer Details</code>
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="last_name" class="form-label mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        <label for="first_name" class="form-label mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="contact_no" class="form-label mb-1">Contact No.</label>
                        <input type="text" id="contact_no" name="contact_no" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="email" class="form-label mb-1">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        <label for="care_of" class="form-label mb-1">Care of</label>
                        <input type="text" id="care_of" name="care_of" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <label for="feeder" class="form-label mb-1">Feeder *</label>
                    <select id="feeder" class="form-control" name="feeder" required>
                      <option value=""></option>
                      @foreach (Config::get('constants.feeders') as $feeder)          
                        <option value="{{ $feeder['name'] }}" id="">{{ $feeder['name'] }}</option>
                      @endforeach 
                    </select>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                      <label for="area" class="form-label mb-1">Area *</label>
                      <select id="area" class="form-control" name="area" value="{{ old('area')}}" required>
                        <option value=""></option>
                        @foreach (Config::get('constants.coverage_areas') as $area)          
                          <option value="{{ $area['id'] }}" id="">{{ $area['name'] }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="municipality" class="form-label mb-1">Municipality *</label>
                      <select id="municipality" class="form-control" name="municipality" required>
                        <option value="" id=""></option>
                        @foreach ($municipalities as $municipality)                        
                            <option value="{{ $municipality->id }}" id="{{ $municipality->id }}">{{$municipality->municipality_name}}</option>
                        @endforeach 
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="barangay" class="form-label mb-1">Barangay *</label>
                      <select id="barangay" class="form-control" name="barangay" required></select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="sitio" class="form-label mb-1">Sitio </label>
                      <input type="text" id="sitio" name="sitio" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="membership_or" class="form-label mb-1">Membership OR *</label>
                        <input type="text" id="membership_or" name="membership_or" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="membership_date" class="form-label mb-1">Membership Date *</label>
                      <input type="date" id="membership_date" name="membership_date" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                      <label for="consumer_type" class="form-label mb-1">Type *</label>
                      <input type="text" id="consumer_type" name="consumer_type" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="meter_or_no" class="form-label mb-1">Meter OR #</label>
                      <input type="text" id="meter_or_no" name="meter_or_no" class="form-control">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="process_date" class="form-label mb-1">Process Date *</label>
                      <input type="date" id="process_date" name="process_date" class="form-control" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-8">
                    <div class="mb-2">
                      <label for="meter_code_no" class="form-label mb-1">Type Of Meter*</label>
                        <select id="meter_code_no" class="form-control" name="meter_code_no" required>
                          <option value=""></option>
                          @foreach ($type_of_meters as $type_of_meter)          
                            <option value="{{ $type_of_meter->id }}" 
                                    id="" 
                                    {{ old('meter_code_no') == $type_of_meter->meter_code ? 'selected' : ''}}
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
                      <label for="last_reading" class="form-label mb-1">Last Reading</label>
                      <input type="number" id="last_reading" name="last_reading" class="form-control" readonly>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="reading_initial" class="form-label mb-1">Initial Reading</label>
                      <input type="number" id="reading_initial" name="reading_initial" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label class="form-label mb-1">Remarks </label>
                      <textarea class="form-control" name="remarks" id="remarks"></textarea>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label class="form-label mb-1">Landmark *</label>
                      <textarea class="form-control" name="location" id="location" required></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-3 mb-3" id="schedule_of_fees" >
                <div class="col text-center"><h2>Schedule of Fees</h2></div>
                @include('service_connect_order.schedule_of_fees')
              </div>

              <div class="col-lg-8">
                <code class="fs-4">Liquidation Details</code>
                <hr>
                  <div class="row">
                    <div class="col-lg-5">
                      <div class="mb-2">
                        <label for="kwh_meter_request_control_no" class="form-label mb-1">kWh Meter Request</label>
                          <select id="kwh_meter_request_control_no" class="form-control" name="kwh_meter_request_control_no">
                            <option value="">Select kWh Meter Request</option>
                            @foreach ($kwh_meter_requests as $key => $control_no)          
                              <option value="{{ $key }}" {{ old('kwh_meter_request_control_no') == $control_no ? 'selected' : ''}}>
                              {{ $control_no }}
                              </option>
                            @endforeach 
                          </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="mb-2">
                        <label for="liquidation_requested_by" class="form-label mb-1">Requested By</label>
                        <input type="text" id="liquidation_requested_by" name="liquidation_requested_by" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="liquidation_meter_type" class="form-label mb-1">Meter Type</label>
                        <input type="text" id="liquidation_meter_type" name="liquidation_meter_type" class="form-control" readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="meter_serial_number" class="form-label mb-1">Serial Number</label>
                          <select id="meter_serial_number" class="form-control" name="meter_serial_number">
                            <option value="">Select Serial Number</option>
                          </select>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="liquidation_erc_seal" class="form-label mb-1">ERC Seal</label>
                        <input type="text" id="liquidation_erc_seal" name="liquidation_erc_seal" class="form-control" readonly>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-2">
                        <label for="liquidation_leyeco_seal" class="form-label mb-1">Leyeco 5 Seal</label>
                        <input type="text" id="liquidation_leyeco_seal" name="liquidation_leyeco_seal" class="form-control" readonly>
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
  $( "#electric_service_detail" ).select2({
    ajax: { 
      url: "{{route('cmFetchAccounts')}}",
      type: "get",
      dataType: 'json',
      // delay: 100,
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

      return data.id + " | " + data.Name + " | " + data.Address;
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

<script>
$(document).ready(function() {
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

    // Handle kWh meter request selection change
    $('#kwh_meter_request_control_no').on('change', function() {
        const controlNo = $(this).val();
        
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

        // remove type of meter required validation
        if (controlNo) {
            $('#meter_code_no').prop('required', false);
        } else {
            $('#meter_code_no').prop('required', true);
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
            data: { control_no: controlNo },
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
</script>

@endsection