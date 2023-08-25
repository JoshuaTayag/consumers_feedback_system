@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Lifeline Report</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              {!! Form::open(array('route' => 'lifeline.generate.report','method'=>'GET')) !!}
              <div class="row">
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label for="date_from" class="form-label mb-1">Period From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from">
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label for="date_to" class="form-label mb-1">Period To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to">
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label for="status_type" class="form-label mb-1">Status</label>
                    <select id="status_type" class="form-control" name="status_type">
                      <option value="">All</option>
                      <option value="1">Active</option>
                      <option value="2">Delisted</option>
                  </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="mb-2">
                    <label for="district" class="form-label mb-1">District *</label>
                    <select id="district" class="form-control" name="district">
                        <option value="">Choose...</option>
                        @foreach ($districts as $district)                        
                            <option value="{{ $district->id }}" id="{{ $district->id }}">{{$district->district_name}}</option>
                        @endforeach 
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="mb-2">
                    <label for="municipality" class="form-label mb-1">Municipality *</label>
                    <select id="municipality" class="form-control" name="municipality">
                      <option value="">Choose...</option>
                    </select>
                  </div>
                </div>
                <div class="col-lg-3">
                  <button type="submit" class="btn btn-primary" >Generate</button>
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
  /*------------------------------------------
  --------------------------------------------
  District Dropdown Change Event
  --------------------------------------------
  --------------------------------------------*/
  $('#district').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#municipality").html('');
      $.ajax({
          url: "{{url('api/fetch-municipalities')}}",
          type: "POST",
          data: {
              district_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
              $('#municipality').html('<option value="">-- Select Municipality --</option>');
              
              $.each(result.municipalities, function (key, value) {
                    $("#municipality").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.municipality_name + '</option>');
                });
              $('#barangay').html('<option value="">-- Select Barangay --</option>');
          }
      });
  });

  /*------------------------------------------
  --------------------------------------------
  Municipality Dropdown Change Event
  --------------------------------------------
  --------------------------------------------*/
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
  .badge{
    line-height: 0.3;
  }
</style>
@endsection