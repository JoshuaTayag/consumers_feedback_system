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
          {!! Form::open(array('route' => 'storeCM','method'=>'POST')) !!}
            <div class="row">
              <div class="col-lg-8">
                <div class="row">
                  <div class="col-lg-8">
                    <div class="mb-2">
                        <!-- {{ Form::label('account_no', 'Account No') }}
                        {{ Form::text('account_no', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }} -->
                        <label for="electric_service_detail" class="form-label mb-1">Account Number *</label><br>
                        <select class="form-control" id="electric_service_detail" name="electric_service_detail" style="width: 100%" required></select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-2">
                        {{ Form::label('old_meter', 'Old Meter') }}
                        {{ Form::text('old_meter', null, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <!-- <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('sco', 'SCO No') }}
                        {{ Form::text('sco', null, array('class' => 'form-control', 'disabled')) }}
                    </div>
                  </div> -->
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('last_name', 'Last Name') }}
                        {{ Form::text('last_name', null, array('class' => 'form-control', 'readonly', 'required')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('first_name', 'First Name') }}
                        {{ Form::text('first_name', null, array('class' => 'form-control', 'required')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('contact_no', 'Contact No.') }}
                        {{ Form::text('contact_no', null, array('class' => 'form-control')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('care_of', 'Care of') }}
                        {{ Form::text('care_of', null, array('class' => 'form-control')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <!-- <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('or_number', 'OR number') }}
                        {{ Form::text('or_number', null, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div> -->
                  <div class="col-lg-2">
                    <label for="feeder">Feeder *</label>
                    <!-- <input type="text" value="" id="care_of" name="care_of" class="form-control" readonly> -->
                    <select id="feeder" class="form-control" name="feeder" required>
                      <option value=""></option>
                      @foreach (Config::get('constants.feeders') as $feeder)          
                        <option value="{{ $feeder['name'] }}" id="">{{ $feeder['name'] }}</option>
                      @endforeach 
                    </select>
                  </div>
                  <div class="col-lg-1">
                    <div class="mb-2">
                        {{ Form::label('area', 'Area *') }}
                        <select id="area" class="form-control" name="area" value="{{ old('area')}}" required>
                          <option value=""></option>
                          <option value="1" {{ old('area') == "A1" ? 'selected' : ''}} >A1</option>
                          <option value="2" {{ old('area') == "A2" ? 'selected' : ''}} >A2</option>
                          <option value="3" {{ old('area') == "A3" ? 'selected' : ''}} >A3</option>
                          <option value="4" {{ old('area') == "A4" ? 'selected' : ''}} >A4</option>
                          <option value="5" {{ old('area') == "A5" ? 'selected' : ''}} >A5</option>
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
                      {{ Form::label('sitio', 'Sitio *') }}
                      {{ Form::text('sitio', null, array('class' => 'form-control', 'required')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('membership_or', 'Membership OR *') }}
                        {{ Form::text('membership_or', null, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('membership_date', 'Membership Date *') }}
                        {{ Form::date('membership_date', null, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('consumer_type', 'Consumer Type *') }}
                        <select id="consumer_type" class="form-control" name="consumer_type" value="{{ old('consumer_type')}}" required>
                          <option value=""></option>
                          @foreach (Config::get('constants.consumer_types') as $consumer_type)          
                            <option value="{{ $consumer_type['id'] }}" id="">{{ $consumer_type['name'] }}</option>
                          @endforeach 
                        </select>
                    </div>
                  </div>
                  <!-- <div class="col-lg-3">
                    <div class="mb-2">
                        {{ Form::label('occupancy_type', 'Occupancy Type') }}
                        <select id="occupancy_type" class="form-control" name="occupancy_type">
                          <option value=""></option>
                          @foreach ($occupancy_types as $occupancy_type)          
                            <option value="{{ $occupancy_type->occupancy_name }}" >{{ $occupancy_type->occupancy_name }}</option>
                          @endforeach 
                        </select>
                    </div>
                  </div> -->
                  <!-- <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('line_type', 'Line Type') }}
                        <select id="line_type" class="form-control" name="line_type">
                          <option value=""></option>
                          @foreach (Config::get('constants.line_types') as $line_type)          
                            <option value="{{ $line_type['name'] }}" id="">{{ $line_type['name'] }}</option>
                          @endforeach 
                        </select>
                    </div>
                  </div> -->
                  <!-- <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('meter_no', 'Meter No') }}
                        {{ Form::text('meter_no', null, array('class' => 'form-control')) }}
                    </div>
                  </div> -->
                  <!-- <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('date_installed', 'Date Installed') }}
                        {{ Form::date('date_installed', null, array('class' => 'form-control')) }}
                    </div>
                  </div> -->
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('meter_or_no', 'Meter OR #') }}
                        {{ Form::text('meter_or_no', null, array('class' => 'form-control')) }}
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('process_date', 'Process Date *') }}
                        {{ Form::date('process_date', null, array('class' => 'form-control', 'required')) }}
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-8">
                    <div class="mb-2">
                        {{ Form::label('meter_code_no', 'Type Of Meter*') }}
                        <select id="meter_code_no" class="form-control" name="meter_code_no" required>
                          <option value=""></option>
                          @foreach ($type_of_meters as $type_of_meter)          
                            <option value="{{ $type_of_meter->meter_code }}" id="" {{ old('meter_code_no') == $type_of_meter->meter_code ? 'selected' : ''}}>
                              <div class="row">
                                <div class="form-group">
                                  <label class="col-xs-6">{{ $type_of_meter->meter_code  }} <span class="fw-bold">|</span></label>
                                  <label class="col-xs-6">{{ $type_of_meter->meter_description  }}</label>
                                </div>
                              </div> 
                            </option>
                          @endforeach 
                        </select>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('last_reading', 'Last Reading') }}
                        {{ Form::number('last_reading', null, array('class' => 'form-control', 'readonly')) }}
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                        {{ Form::label('reading_initial', 'Initial Reading') }}
                        {{ Form::number('reading_initial', null, array('class' => 'form-control')) }}
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
                      <label class="form-label mb-1">Landmark </label>
                      <textarea class="form-control" name="location" id="location"></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4 mb-3" id="schedule_of_fees" >
              <div class="col text-center"><h2>Schedule of Fees</h2></div>
                @include('service_connect_order.schedule_of_fees')
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
    var trimmedSerialNo = data['Serial No'].replace(/\s+$/g, '');

    document.getElementById('last_name').value = trimmedLastName;
    document.getElementById('first_name').value = trimmedFirstName;
    document.getElementById('membership_or').value = data['OR No'];
    document.getElementById('membership_date').value = dateOnlyString;
    // document.getElementById('last_reading').value = parseFloat(data['Prev Reading'].toFixed(0));
    var prevReading = parseFloat(data['Prev Reading']);
    if (!isNaN(prevReading)) {
        document.getElementById('last_reading').value = prevReading.toFixed(0);
    }
    document.getElementById('old_meter').value = trimmedSerialNo;


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
</style>
@endsection