@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Insert Activity / Meeting</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('electricianActivityIndex') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'electricianActivityStore','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
            <div class="row">
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="name_of_activity" class="form-label">Name of Activity *</label>
                  <input type="text" class="form-control" value="{{ old('name_of_activity') }}" id="name_of_activity"  name="name_of_activity" required>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="conducted_by" class="form-label">Conducted by *</label>
                  <input type="text" class="form-control" value="{{ old('conducted_by') }}" id="conducted_by"  name="conducted_by" required>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="date_of_activity" class="form-label">Date of Activity *</label>
                    <div class="d-flex gap-2">
                    <input type="date" class="form-control" value="{{ old('date_of_activity_start') }}" id="date_of_activity_start" name="date_of_activity_start" required placeholder="Start Date">
                    <span class="align-self-center">to</span>
                    <input type="date" class="form-control" value="{{ old('date_of_activity_end') }}" id="date_of_activity_end" name="date_of_activity_end" required placeholder="End Date">
                    </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="time_conducted" class="form-label">Time *</label>
                  <div class="d-flex gap-2">
                    <input type="time" class="form-control" value="{{ old('time_conducted_start') }}" id="time_conducted_start" name="time_conducted_start" required placeholder="Start Time">
                    <span class="align-self-center">to</span>
                    <input type="time" class="form-control" value="{{ old('time_conducted_end') }}" id="time_conducted_end" name="time_conducted_end" required placeholder="End Time">
                  </div>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="venue" class="form-label">Venue *</label>
                  <input type="text" class="form-control" value="{{ old('venue') }}" id="venue"  name="venue" required>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="participants" class="form-label">Target Participants *</label>
                  <select id="district" class="form-control" name="participants" required>
                      <option value="">Choose...</option>
                      @foreach ($districts as $district)                        
                        <option value="{{ $district->id }}" id="{{ $district->id }}">{{$district->district_name}}</option>
                      @endforeach 
                      <option value="100" id="">All</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="attendees" class="form-label">Attendees *</label>
                    <select class="form-control" id="electric_service_detail" name="attendees[]" multiple="multiple" style="width: 100%" required>
                    </select>
                </div>
              </div>
            </div>
            <a class="btn btn-sm btn-primary float-end mx-1" href="{{ route('electricianActivityIndex') }}"><i class="fa fa-ban me-2"></i>Cancel</a>
            <button type="submit" class="btn btn-sm btn-success float-end mx-1"><i class="fa fa-check me-2"></i>Submit</button>
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
          url: "{{route('fetchElectricians')}}",
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
      return data.last_name + ", " +data.first_name
     }

     function templateSelection(data){
      return data.last_name + ", " +data.first_name
     }
</script>
@endsection
@section('style')
<style>
  .select2-container .select2-selection--single {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    height: 36px;
    user-select: none;
    -webkit-user-select: none;
  }
  .styled-heading {
    color: rgb(0, 202, 0);
    margin-bottom: 15px;
  }
</style>
@endsection