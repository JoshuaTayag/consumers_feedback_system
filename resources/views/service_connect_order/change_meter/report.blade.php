@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Generate Change Meter Report</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    @can('service-connect-order-create')
                      <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"> Back </a>
                    @endcan
                  </div>
              </div>
            </div>
            <form action="{{ route('generateReport') }}" method="GET">
              <div class="row p-3">
                <div class="col-lg-2">
                  {{ Form::label('date_from', 'Date From;') }}
                  {{ Form::date('date_from', null, array('class' => 'form-control', 'required')) }}
                </div>
                <div class="col-lg-2">
                  {{ Form::label('date_to', 'Date To;') }}
                  {{ Form::date('date_to', null, array('class' => 'form-control', 'required')) }}
                </div>
                <div class="col-lg-2">
                  <label for="app_status" class="form-label mb-1">Application Status</label>
                  <select id="app_status" class="form-control" name="app_status">
                    <option value="0" id="">All</option>
                    <option value="1" id="">UNACTED</option>
                    <option value="2" id="">ACTED - COMPLETED</option>
                    <option value="3" id="">ACTED - NOT COMPLETED</option>
                    <option value="4" id="">DISPATCHED</option>
                  </select>
                </div>

                <div class="col-lg-2">
                  <div class="mb-2">
                      {{ Form::label('area', 'Area') }}
                      <select id="area" class="form-control" name="area" value="{{ old('area')}}">
                        <option value="">ALL</option>
                        <option value="1" {{ old('area') == "A1" ? 'selected' : ''}} >A1</option>
                        <option value="2" {{ old('area') == "A2" ? 'selected' : ''}} >A2</option>
                        <option value="3" {{ old('area') == "A3" ? 'selected' : ''}} >A3</option>
                        <option value="4" {{ old('area') == "A4" ? 'selected' : ''}} >A4</option>
                        <option value="5" {{ old('area') == "A5" ? 'selected' : ''}} >A5</option>
                      </select>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label for="municipality" class="form-label mb-1">Municipality</label>
                    <select id="municipality" class="form-control" name="municipality" >
                      <option value="" id="">ALL</option>
                      @foreach ($municipalities as $municipality)                        
                          <option value="{{ $municipality->id }}" id="{{ $municipality->id }}">{{$municipality->municipality_name}}</option>
                      @endforeach 
                    </select>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label for="barangay" class="form-label mb-1">Barangay</label>
                    <select id="barangay" class="form-control" name="barangay">
                      <option value="" id="">ALL</option>
                    </select>
                  </div>
                </div>


                <div class="col-lg-12 text-end pt-2 gap-2">
                  <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-search"></i> GENERATE</button>
                  <button type="button" class="btn btn-sm btn-info" onclick="clearSearch()"><i class="fa fa-eraser"></i> Clear</button>
                </div>
              </div>
            </form>
            <div class="card-body">
              <div class="row">
             
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script>
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
              $('#barangay').html('<option value="">ALL</option>');
              $.each(res.barangays, function (key, value) {
                    $("#barangay").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
              });
          }
      });
  });
</script>
@endsection