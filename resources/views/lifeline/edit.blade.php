@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit Lifeline</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('lifeline.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::model($lifeline, ['method' => 'PUT','route' => ['lifeline.update', $lifeline->id]]) !!}
            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="control_no" class="form-label mb-1">Control No. *</label>
                  <input type="text" class="form-control" id="control_no" name="control_no" value="{{$lifeline->control_no}}" required readonly>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="first_name" class="form-label mb-1">First Name *</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" value="{{$lifeline->first_name}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="middle_name" class="form-label mb-1">Middle Name</label>
                  <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{$lifeline->middle_name}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="last_name" class="form-label mb-1">Last Name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{$lifeline->last_name}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="maiden_name" class="form-label mb-1">Maiden Name</label>
                    <input type="text" class="form-control" id="maiden_name" name="maiden_name" value="{{$lifeline->maiden_name}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="contact_no" class="form-label mb-1">Contact No.</label>
                    <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{$lifeline->contact_no}}">
                </div>
              </div>
            </div>
            
            <hr>

            <div class="row">
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="house_no_zone_purok_sitio" class="form-label mb-1">House No./Zone/Purok/Sitio</label>
                    <input type="text" class="form-control" id="house_no_zone_purok_sitio" name="house_no_zone_purok_sitio" value="{{$lifeline->house_no_zone_purok_sitio}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="street" class="form-label mb-1">Street</label>
                    <input type="text" class="form-control" id="street" name="street" value="{{$lifeline->street}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="district" class="form-label mb-1">District *</label>
                  <select id="district" class="form-control" name="district" required>
                      <option value="">Choose...</option>
                      @foreach ($districts as $district)                        
                          <option value="{{ $district->id }}" id="{{ $district->id }}" @selected( $lifeline->district_id == $district->id) >{{$district->district_name}}</option>
                      @endforeach 
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="municipality" class="form-label mb-1">Municipality *</label>
                  <select id="municipality" class="form-control" name="municipality">
                    <option value="{{ $lifeline->municipality->id }}" id="{{ $lifeline->municipality->id }}">{{$lifeline->municipality->municipality_name }}</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="barangay" class="form-label mb-1">Barangay</label>
                  <select id="barangay" class="form-control" name="barangay">
                    <option value="{{ $lifeline->barangay->id }}" id="{{ $lifeline->barangay->id }}">{{$lifeline->barangay->barangay_name }}</option>
                  </select>
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                  <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{$lifeline->date_of_birth}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="marital_status" class="form-label mb-1">Marital Status *</label>
                  <select id="marital_status" class="form-control" name="marital_status" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.civil_status') as $status)          
                      <option value="{{ $status['id'] }}" id="" @selected($lifeline->marital_status == $status['id'] )>{{ $status['status_name'] }}</option>
                    @endforeach 
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="ownership" class="form-label mb-1">Ownership *</label>
                  <select id="ownership" class="form-control" name="ownership" required>
                      <option value="">Choose...</option>
                      @foreach (Config::get('constants.ownership') as $ownership)          
                        <option value="{{ $ownership['id'] }}" id="" @selected($lifeline->ownership == $ownership['id'] ) >{{ $ownership['name'] }}</option>
                      @endforeach 
                  </select>
                </div>
              </div>
              
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="electric_service_details" class="form-label mb-1">Electric Service Details (Search by account number) *</label>
                  <input type="hidden" id="hidden_value" value="{{$account[0]->id}} | {{$account[0]->Name}} | {{$account[0]->Address}}">
                  <select class="form-control" id="electric_service_details" name="electric_service_details" readonly required>
                    <option value="{{$account[0]->id}}"></option>
                  </select>
                </div>
              </div>
            </div>
          
            <hr>

            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="type_of_id" class="form-label mb-1">Type of Valid ID *</label>
                  <select id="type_of_id" class="form-control" name="type_of_id" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.valid_id') as $id)          
                      <option value="{{ $id['id'] }}" id="" @selected($lifeline->valid_id_type == $id['id'] )>{{ $id['name'] }}</option>
                    @endforeach 
                  </select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="id_no" class="form-label mb-1">Valid ID No. *</label>
                  <input type="text" class="form-control" id="id_no" name="id_no" value="{{$lifeline->valid_id_no}}" required>
                </div>
              </div>
              @if($lifeline->pppp_id)
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label for="household_id_no" class="form-label mb-1">4Ps Household ID No. *</label>
                    <input type="text" class="form-control" id="household_id_no" name="household_id_no" value="{{$lifeline->pppp_id}}" required>
                  </div>
                </div>
              @endif
              @if(!$lifeline->pppp_id)
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="validity_period_from" class="form-label mb-1">Validity Period From *</label>
                  <input type="date" class="form-control" id="validity_period_from" name="validity_period_from" value="{{$lifeline->validity_period_from}}" readonly required>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="validity_period_to" class="form-label mb-1">Validity Period To *</label>
                  <input type="date" class="form-control" id="validity_period_to" name="validity_period_to" value="{{$lifeline->validity_period_to}}" readonly required>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="annual_income" class="form-label mb-1">Annual Income *</label>
                  <input type="text" class="form-control numbers" id="annual_income" name="annual_income" value="{{$lifeline->annual_income}}" required>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="swdo_certification" class="form-label mb-1">SWDO Certification No. *</label>
                  <input type="text" class="form-control" id="swdo_certification" name="swdo_certification" value="{{$lifeline->swdo_certificate_no}}"  required>
                </div>
              </div>
              @endif
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="date_of_application" class="form-label mb-1">Date Of Application*</label>
                  <input type="date" class="form-control" id="date_of_application" name="date_of_application" value="{{$lifeline->date_of_application}}" required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="representative_id_no" class="form-label mb-1">Rep ID No. (IF FILED THROUGH A REPRESENTATIVE)</label>
                  <input type="text" class="form-control" id="representative_id_no" name="representative_id_no" value="{{$lifeline->representative_id_no}}">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="representative_fullname" class="form-label mb-1">Rep Full Name (IF FILED THROUGH A REPRESENTATIVE)</label>
                  <input type="text" class="form-control" id="representative_fullname" name="representative_fullname" value="{{$lifeline->representative_full_name}}" >
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-9">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Remarks</label>
                  <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1">{{$lifeline->remarks}}</textarea>
                </div>
              </div>
              <div class="col-lg-3 position-relative">
                <button type="submit" class="btn btn-primary position-absolute" style="bottom: 0; right: 0;">Submit</button>
              </div>
            </div>
          {!! Form::close() !!}
        </div>
              
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

    $('input.numbers').keyup(function(event) {

      // skip for arrow keys
      if(event.which >= 37 && event.which <= 40){
        event.preventDefault();
      }

      $(this).val(function(index, value) {
        return value
          .replace(/\D/g, "")
          .replace(/([0-9])([0-9]{2})$/, '$1.$2')  
          .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
        ;
      });
    });

    $('.js-example-basic-single').select2({
        theme: "classic"
    });
    $("#electric_service_details").prop("disabled", true);

      $( "#electric_service_details" ).select2({
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
      if(!data.Name == ""){
        console.log(data);
        return data.id + " | " +data.Name + " | " + data.Address
      }
      else{
        const result = $('#hidden_value').val();
        console.log(result);
        return result;
      }
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

  $('#district1').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#municipality1").html('');
      console.log(id);
      $.ajax({
          url: "{{url('api/fetch-municipalities')}}",
          type: "POST",
          data: {
              district_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (result) {
              $('#municipality1').html('<option value="">-- Select Municipality --</option>');
              
              $.each(result.municipalities, function (key, value) {
                    $("#municipality1").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.municipality_name + '</option>');
                });
              $('#barangay1').html('<option value="">-- Select Barangay --</option>');
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

  $('#municipality1').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#barangay1").html('');
      $.ajax({
          url: "{{url('api/fetch-barangays')}}",
          type: "POST",
          data: {
              municipality_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (res) {
              $('#barangay1').html('<option value="">-- Select Barangay --</option>');
              $.each(res.barangays, function (key, value) {
                    $("#barangay1").append('<option value="' + value
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
</style>
@endsection