@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Insert Change Meter</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('indexCM') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'service-connect-order.store','method'=>'POST')) !!}
          <div class="row">
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('sco', 'SCO No') }}
                  {{ Form::text('sco', null, array('placeholder' => 'Ex: h00001','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-2">
                  {{ Form::label('last_name', 'Last Name') }}
                  {{ Form::text('last_name', null, array('placeholder' => 'Ex: Dela Cruz','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-2">
                  {{ Form::label('first_name', 'First Name') }}
                  {{ Form::text('first_name', null, array('placeholder' => 'Ex: Juan','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('contact_no', 'Contact No.') }}
                  {{ Form::text('contact_no', null, array('placeholder' => 'Ex: Juan','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('or_number', 'OR number') }}
                  {{ Form::text('or_number', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('area', 'Area') }}
                  <select id="area" class="form-control" name="project_name" required>
                    <option value="">Choose...</option>
                    <option value="A1" >A1</option>
                    <option value="A2" >A2</option>
                    <option value="A3" >A3</option>
                    <option value="A4" >A4</option>
                    <option value="A5" >A5</option>
                  </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('sitio', 'Sitio') }}
                  <select id="sitio" class="form-control" name="project_name" required>
                    <option value="">Choose...</option>
                    @foreach ($sitios as $sitio)          
                      <option value="{{ $sitio->Sitio }}" >{{ $sitio->Sitio }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('barangay', 'Barangay') }}
                  <select id="barangay" class="form-control" name="project_name" required>
                    <option value="">Choose...</option>
                    @foreach ($barangays as $barangay)          
                      <option value="{{ $barangay->Brgy }}" >{{ $barangay->Brgy }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('municipality', 'Municipality') }}
                  <select id="municipality" class="form-control" name="project_name" required>
                    <option value="">Choose...</option>
                    @foreach ($municipalities as $municipality)          
                      <option value="{{ $municipality->Municipality }}" >{{ $municipality->Municipality }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('membership_or', 'Membership OR') }}
                  {{ Form::text('membership_or', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('membership_date', 'Membership Date') }}
                  {{ Form::date('membership_date', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-2">
                  {{ Form::label('consumer_type', 'Consumer Type') }}
                  <select id="consumer_type" class="form-control" name="consumer_type" required>
                    <option value="">Choose...</option>
                    @foreach ($consumer_types as $consumer_type)          
                      <option value="{{ $consumer_type->name_type }}" >{{ $consumer_type->name_type }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-2">
                  {{ Form::label('occupancy_type', 'Occupancy Type') }}
                  <select id="occupancy_type" class="form-control" name="occupancy_type" required>
                    <option value="">Choose...</option>
                    @foreach ($occupancy_types as $occupancy_type)          
                      <option value="{{ $occupancy_type->occupancy_name }}" >{{ $occupancy_type->occupancy_name }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('line_type', 'Line Type') }}
                  <select id="line_type" class="form-control" name="line_type" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.line_types') as $line_type)          
                      <option value="{{ $line_type['name'] }}" id="">{{ $line_type['name'] }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('meter_no', 'Meter No') }}
                  {{ Form::text('meter_no', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('date_installed', 'Date Installed') }}
                  {{ Form::date('date_installed', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mb-2">
                  {{ Form::label('meter_or_no', 'Meter OR #') }}
                  {{ Form::text('meter_or_no', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-5">
              <div class="mb-2">
                  {{ Form::label('meter_code_no', 'Type Of Meter') }}
                  <select id="meter_code_no" class="form-control" name="meter_code_no" required>
                    <option value="">Choose...</option>
                    @foreach ($type_of_meters as $type_of_meter)          
                      <option value="{{ $type_of_meter->meter_code }}" id="">
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
            <div class="col-lg-3">
              <div class="mb-2">
                  {{ Form::label('account_no', 'Account No') }}
                  {{ Form::text('account_no', null, array('placeholder' => 'Ex: 0111111','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('last_reading', 'Last Reading') }}
                  {{ Form::number('last_reading', null, array('placeholder' => 'Ex: 80','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-2">
              <div class="mb-2">
                  {{ Form::label('reading_initial', 'Initial Reading') }}
                  {{ Form::number('reading_initial', null, array('placeholder' => 'Ex: 100','class' => 'form-control')) }}
              </div>
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Remarks </label>
                  <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1"></textarea>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="location" class="form-label mb-1">Location </label>
                  <textarea class="form-control" name="location" id="exampleFormControlTextarea1"></textarea>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                <a class="btn btn-primary" href="{{ route('indexCM') }}"><i class="fa fa-arrow-left me-2"></i>Back </a>
                <button type="submit" class="btn btn-success"><i class="fa fa-check me-2"></i>Submit</button>
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
<script>
$('#checkAll').click(function () {    
  $('input:checkbox').prop('checked', this.checked);    
});
</script>
@endsection