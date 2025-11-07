@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create SCO</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('service-connect-order.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'service-connect-order.store','method'=>'POST')) !!}
          <div class="row">
            <div class="col-lg-4">
              <div class="mb-2">
                  {{ Form::label('project_name', 'Project Name') }}
                  <select id="project_name" class="form-control" name="project_name" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.project_name') as $status)          
                      <option value="{{ $status['name'] }}" id="">{{ $status['name'] }}</option>
                    @endforeach 
                  </select>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-2">
                  {{ Form::label('or_no', 'OR No.') }}
                  {{ Form::text('or_no', null, array('placeholder' => 'OR','class' => 'form-control')) }}
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-2">
                {{ Form::label('occupancy_type', 'Occupancy Type') }}
                <select id="occupancy_type" class="form-control" name="occupancy_type" required>
                  <option value="">Choose...</option>
                  @foreach (Config::get('constants.occupancy_type') as $type)          
                    <option value="{{ $type['name'] }}" id="">{{ $type['name'] }}</option>
                  @endforeach 
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-4">
              <div class="mb-2">
                <label for="first_name" class="form-label mb-1">First Name *</label>
                <input type="text" class="form-control" id="first_name" name="first_name">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-2">
                <label for="middle_name" class="form-label mb-1">Middle Name *</label>
                <input type="text" class="form-control" id="middle_name" name="middle_name">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-2">
                <label for="last_name" class="form-label mb-1">Last Name *</label>
                  <input type="text" class="form-control" id="last_name" name="last_name">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="mb-2">
                <label for="last_name" class="form-label mb-1">Last Name *</label>
                  <input type="text" class="form-control" id="last_name" name="last_name">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-lg-6">
              <div class="mb-2">
                  
              </div>
              <div class="mb-2">
                  
              </div>
              <div class="mb-2">
                  
              </div>
              <div class="mb-2">
                  <div class="row my-2">
                      <div class="col-lg-10">
                          <label for="spouse" class="form-label mb-1">Spouse (If Married)</label>
                          <input type="text" class="form-control" id="spouse" name="spouse">
                      </div>
                      <div class="col-lg-2">
                          <label for="joint" class="form-label mb-1">Joint</label><br>
                          <input type="checkbox" class="form-check-input" id="joint" name="joint">
                      </div>
                      {{-- <div class="col-lg-2">
                          <label for="single" class="form-label mb-1">Single</label><br>
                          <input type="checkbox" class="form-check-input" id="single" name="single" checked>
                      </div> --}}
                  </div>
              </div>
              <div class="mb-2">
                  <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                  <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
              </div>
              <div class="mb-2">
                  <label for="contact_no" class="form-label mb-1">Contact No. </label>
                  <input type="number" class="form-control" id="contact_no" name="contact_no">
              </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
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