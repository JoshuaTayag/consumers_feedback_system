@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create Structure</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('structure.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'structure.store','method'=>'POST')) !!}
            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="structure_code" class="form-label mb-1">Structure Code *</label>
                    <input type="text" class="form-control" id="structure_code" name="structure_code" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="status" class="form-label mb-1">Status *</label>
                  <select id="status" class="form-control" name="status" required>
                    <option value="1" >Active</option>
                    <option value="0" >In Active</option>
                </select>
                </div>
              </div>
        
              <div class="col-lg-8">
                <div class="mb-2">
                  <label for="items" class="form-label mb-1">Items *</label>
                  <select class="js-example-basic-multiple form-control" id="items" name="items[]" required multiple="multiple">
                  </select>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Remarks </label>
                  <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1"></textarea>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-lg-12">
                <input type="submit" class="btn btn-primary" value="Submit">
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
$(document).ready(function () {

      $('.js-example-basic-multiple').select2();

      $( "#items" ).select2({
        ajax: { 
          url: "{{route('fetchItems')}}",
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
      return data.id + " | " +data.ItemCode + " | " + data.Description
     }

     function templateSelection(data){
      return data.id + " | " +data.ItemCode + " | " + data.Description
     }

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

  $('#myTab a').on('click', function (e) {
  e.preventDefault()
  $(this).tab('show')

});
})
</script>
@endsection