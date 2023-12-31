@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Apply New Lifeline</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('lifeline.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="4ps-tab" data-toggle="tab" href="#4ps" role="tab" aria-controls="4ps" aria-selected="true">4Ps Applicant</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="non4ps-tab" data-toggle="tab" href="#non4ps" role="tab" aria-controls="non4ps" aria-selected="false">Non 4Ps Applicant</a>
            </li>
          </ul>
          <div class="tab-content my-3" id="myTabContent">
            <div class="tab-pane fade show active" id="4ps" role="tabpanel" aria-labelledby="4ps-tab">
              {!! Form::open(array('route' => 'lifeline.store','method'=>'POST')) !!}
                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="control_no" class="form-label mb-1">Control No. *</label>
                        <input type="text" class="form-control" id="control_no" name="control_no" value="{{$control_id}}" readonly>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="first_name" class="form-label mb-1">First Name *</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" value="{{old('first_name')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="middle_name" class="form-label mb-1">Middle Name</label>
                      <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{old('middle_name')}}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="last_name" class="form-label mb-1">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{old('last_name')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="maiden_name" class="form-label mb-1">Maiden Name</label>
                        <input type="text" class="form-control" id="maiden_name" name="maiden_name" value="{{old('maiden_name')}}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="contact_no" class="form-label mb-1">Contact No.</label>
                        <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{old('contact_no')}}">
                    </div>
                  </div>
                </div>
                
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="house_no_zone_purok_sitio" class="form-label mb-1">House No./Zone/Purok/Sitio</label>
                        <input type="text" class="form-control" id="house_no_zone_purok_sitio" name="house_no_zone_purok_sitio" value="{{old('house_no_zone_purok_sitio')}}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="street" class="form-label mb-1">Street</label>
                        <input type="text" class="form-control" id="street" name="street" value="{{old('street')}}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="district" class="form-label mb-1">District *</label>
                      <select id="district" class="form-control" name="district" required>
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
                      <select id="municipality" class="form-control" name="municipality"></select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="barangay" class="form-label mb-1">Barangay</label>
                      <select id="barangay" class="form-control" name="barangay"></select>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="postal_code" class="form-label mb-1">Postal Code</label>
                      <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{old('postal_code')}}" required>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                      <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{old('date_of_birth')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="marital_status" class="form-label mb-1">Marital Status *</label>
                      <select id="marital_status" class="form-control" name="marital_status" required>
                        <option value="">Choose...</option>
                        @foreach (Config::get('constants.civil_status') as $status)          
                          <option value="{{ $status['id'] }}" id="" {{ old('marital_status') ? 'selected' : '' }}>{{ $status['status_name'] }}</option>
                        @endforeach 
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="ownership" class="form-label mb-1">Ownership *</label>
                      <select id="ownership" class="form-control" name="ownership" required>
                          <option value="">Choose...</option>
                          @foreach (Config::get('constants.ownership') as $status)          
                            <option value="{{ $status['id'] }}" id="" {{ old('ownership') ? 'selected' : '' }}>{{ $status['name'] }}</option>
                          @endforeach 
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label for="electric_service_details" class="form-label mb-1">Electric Service Details (Search by account number) *</label>
                      <select class="form-control" id="electric_service_details" name="electric_service_details" required>
                      </select>
                    </div>
                  </div>
                </div>
              
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="type_of_id" class="form-label mb-1">Type of Valid ID *</label>
                      <select id="type_of_id" class="form-control" name="type_of_id" required>
                        <option value="">Choose...</option>
                        @foreach (Config::get('constants.valid_id') as $id)          
                          <option value="{{ $id['id'] }}" id="" {{ old('type_of_id') ? 'selected' : '' }}>{{ $id['name'] }}</option>
                        @endforeach 
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="id_no" class="form-label mb-1">Valid ID No. *</label>
                      <input type="text" class="form-control" id="id_no" name="id_no" value="{{ old('id_no') }}" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="household_id_no" class="form-label mb-1">4Ps Household ID No. *</label>
                      <input type="text" class="form-control" id="household_id_no" name="household_id_no" value="{{ old('household_id_no')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="date_of_application" class="form-label mb-1">Date Of Application*</label>
                      <input type="date" class="form-control" id="date_of_application" name="date_of_application" value="{{ old('date_of_application')}}" required>
                    </div>
                  </div>
                </div>
                
                <hr>

                <div class="row">
                  <div class="col-lg-4">
                    <div class="mb-2">
                      <label for="representative_id_no" class="form-label mb-1">Rep ID No. (IF FILED THROUGH A REPRESENTATIVE)</label>
                      <input type="text" class="form-control" id="representative_id_no" name="representative_id_no" value="{{ old('representative_id_no') }}">
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="mb-2">
                      <label for="representative_fullname" class="form-label mb-1">Rep Full Name (IF FILED THROUGH A REPRESENTATIVE)</label>
                      <input type="text" class="form-control" id="representative_fullname" name="representative_fullname" value="{{ old('representative_fullname')}}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-9">
                    <div class="mb-2">
                      <label for="remarks" class="form-label mb-1">Remarks</label>
                      <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1">{{old('remarks')}}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-3 position-relative">
                    <button type="submit" class="btn btn-primary position-absolute" style="bottom: 0; right: 0;">Submit</button>
                  </div>
                </div>
              {!! Form::close() !!}
              
            </div>
            <div class="tab-pane fade" id="non4ps" role="tabpanel" aria-labelledby="non4ps-tab">
              {!! Form::open(array('route' => 'lifeline.store.non_poor','method'=>'POST')) !!}
                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="control_no" class="form-label mb-1">Control No. *</label>
                      <input type="text" class="form-control" id="control_no" name="control_no" value="{{$control_id}}" readonly>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="first_name" class="form-label mb-1">First Name *</label>
                      <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="middle_name" class="form-label mb-1">Middle Name</label>
                      <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name')}}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="last_name" class="form-label mb-1">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="maiden_name" class="form-label mb-1">Maiden Name</label>
                        <input type="text" class="form-control" id="maiden_name" name="maiden_name" value="{{ old('maiden_name')}}">
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="contact_no" class="form-label mb-1">Contact No.</label>
                        <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ old('contact_no')}}">
                    </div>
                  </div>
                </div>
                
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="house_no_zone_purok_sitio" class="form-label mb-1">House No./Zone/Purok/Sitio</label>
                        <input type="text" class="form-control" id="house_no_zone_purok_sitio" name="house_no_zone_purok_sitio" value="{{ old('house_no_zone_purok_sitio')}}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="street" class="form-label mb-1">Street</label>
                        <input type="text" class="form-control" id="street" name="street" value="{{ old('street')}}">
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="district" class="form-label mb-1">District *</label>
                      <select id="district1" class="form-control" name="district" required>
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
                      <select id="municipality1" class="form-control" name="municipality"></select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="barangay" class="form-label mb-1">Barangay</label>
                      <select id="barangay1" class="form-control" name="barangay"></select>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="postal_code" class="form-label mb-1">Postal Code *</label>
                      <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code')}}" required>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                      <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"  value="{{ old('date_of_birth')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="marital_status" class="form-label mb-1">Marital Status *</label>
                      <select id="marital_status" class="form-control" name="marital_status" required>
                        <option value="">Choose...</option>
                        @foreach (Config::get('constants.civil_status') as $status)          
                          <option value="{{ $status['id'] }}" id="" {{ old('marital_status') ? 'selected' : '' }}>{{ $status['status_name'] }}</option>
                        @endforeach 
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="ownership" class="form-label mb-1">Ownership *</label>
                      <select id="ownership" class="form-control" name="ownership" required>
                          <option value="">Choose...</option>
                          @foreach (Config::get('constants.ownership') as $status)          
                            <option value="{{ $status['id'] }}" id="" {{ old('ownership') ? 'selected' : '' }}>{{ $status['name'] }}</option>
                          @endforeach 
                      </select>
                    </div>
                  </div>
                  
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label for="electric_service_detail" class="form-label mb-1">Electric Service Details (Search by account number) *</label><br>
                      <select class="form-control" id="electric_service_detail" name="electric_service_detail" style="width: 100%" required>
                      </select>
                    </div>
                  </div>
                </div>
              
                <hr>

                <div class="row">
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="type_of_id" class="form-label mb-1">Type of Valid ID *</label>
                      <select id="type_of_id" class="form-control" name="type_of_id" required>
                        <option value="">Choose...</option>
                        @foreach (Config::get('constants.valid_id') as $valid_id)          
                          <option value="{{ $valid_id['id'] }}" id="" {{ old('type_of_id') ? 'selected' : '' }}>{{ $valid_id['name'] }}</option>
                        @endforeach 
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="id_no" class="form-label mb-1">Valid ID No. *</label>
                      <input type="text" class="form-control" id="id_no" name="id_no" value="{{ old('id_no')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="sdwo_certification" class="form-label mb-1">SWDO Certification No. *</label>
                      <input type="text" class="form-control" id="sdwo_certification" name="sdwo_certification" value="{{ old('sdwo_certification')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="annual_income" class="form-label mb-1">Annual Income *</label>
                      <input type="text" class="form-control numbers" id="annual_income" name="annual_income" value="{{ old('annual_income')}}" onblur="numberWithCommas(this)" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="date_of_application" class="form-label mb-1">Date Of Application*</label>
                      <input type="date" class="form-control" id="date_of_application" name="date_of_application" value="{{ old('date_of_application')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="validity_period_from" class="form-label mb-1">Validity Period From *</label>
                      <input type="date" class="form-control" id="validity_period_from" name="validity_period_from" value="{{ old('validity_period_from')}}" required>
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="mb-2">
                      <label for="validity_period_to" class="form-label mb-1">Validity Period To *</label>
                      <input type="date" class="form-control" id="validity_period_to" name="validity_period_to" value="{{ old('validity_period_to')}}" required>
                    </div>
                  </div>
                </div>

                <hr>

                <div class="row">
                  {{-- <div class="col-lg-2">
                    <div class="mb-2">
                      <label for="household_id_no" class="form-label mb-1">4Ps Household ID No. *</label>
                      <input type="text" class="form-control" id="household_id_no" name="household_id_no" required>
                    </div>
                  </div> --}}
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label for="representative_id_no" class="form-label mb-1">Rep ID No. (IF FILED THROUGH A REPRESENTATIVE)</label>
                      <input type="text" class="form-control" id="representative_id_no" name="representative_id_no" value="{{ old('representative_id_no')}}">
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="mb-2">
                      <label for="representative_fullname" class="form-label mb-1">Rep Full Name (IF FILED THROUGH A REPRESENTATIVE)</label>
                      <input type="text" class="form-control" id="representative_fullname" name="representative_fullname" value="{{ old('representative_fullname')}}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-9">
                    <div class="mb-2">
                      <label for="remarks" class="form-label mb-1">Remarks</label>
                      <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1">{{ old('remarks')}}</textarea>
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
      return data.id + " | " +data.Name + " | " + data.Address
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