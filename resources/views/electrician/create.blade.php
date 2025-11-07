@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">New Electrician</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('electrician.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'electrician.store','method'=>'POST')) !!}
            <div class="row">
              <h5 class="styled-heading">Basic Information</h5>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="control_no" class="form-label mb-1">Application No. *</label>
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
              <div class="col-lg-1">
                <div class="mb-2">
                  <label for="name_ext" class="form-label mb-1">Name Ext.</label>
                    <input type="text" class="form-control" id="name_ext" name="name_ext" value="{{old('name_ext')}}">
                </div>
              </div>
              <div class="col-lg-1">
                <div class="mb-2">
                  <label for="sex" class="form-label mb-1">Sex *</label>
                    <select id="type_of_id" class="form-control" name="sex" required>
                      <option value="0">Male</option>
                      <option value="1">Female</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="civil_status" class="form-label mb-1">Civil Status *</label>
                    <!-- <input type="text" class="form-control" id="civil_status" name="civil_status" value="{{old('civil_status')}}"> -->
                    <select id="type_of_id" class="form-control" name="civil_status" required>
                      <option value="">Choose...</option>
                      @foreach (Config::get('constants.civil_status') as $id)          
                        <option value="{{ $id['id'] }}" id="" {{ old('type_of_id') ? 'selected' : '' }}>{{ $id['status_name'] }}</option>
                      @endforeach 
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{old('date_of_birth')}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="email_address" class="form-label mb-1">Email Address</label>
                    <input type="email" class="form-control" id="email_address" name="email_address" value="{{old('email_address')}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="facebook_account" class="form-label mb-1">Facebook Account</label>
                    <input type="text" class="form-control" id="facebook_account" name="facebook_account" value="{{old('facebook_account')}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="spouse_first_name" class="form-label mb-1">Spouse First Name</label>
                    <input type="text" class="form-control" id="spouse_first_name" name="spouse_first_name" value="{{old('spouse_first_name')}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="spouse_middle_name" class="form-label mb-1">Spouse Middle Name</label>
                  <input type="text" class="form-control" id="spouse_middle_name" name="spouse_middle_name" value="{{old('spouse_middle_name')}}" >
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="spouse_last_name" class="form-label mb-1">Spouse Last Name</label>
                  <input type="text" class="form-control" id="spouse_last_name" name="spouse_last_name" value="{{old('spouse_last_name')}}">
                </div>
              </div>
              <div class="col-lg-2">
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
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="valid_id_no" class="form-label mb-1">Valid ID No. *</label>
                    <input type="text" class="form-control" id="valid_id_no" name="valid_id_no" value="{{old('valid_id_no')}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="contact_no" class="form-label mb-1">Primary Contact No. *</label>
                    <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{old('contact_no')}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="contact_no_ext" class="form-label mb-1">Secondary Contact No.</label>
                    <input type="text" class="form-control" id="contact_no_ext" name="contact_no_ext" value="{{old('contact_no_ext')}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="application_type" class="form-label mb-1">Application Type</label>
                    <select name="application_type" id="application_type" class="form-control">
                      <option value="1">New</option>
                      <option value="2">Renewal</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="application_status" class="form-label mb-1">Application Status</label>
                    <select name="application_status" id="application_status" class="form-control" required>
                      <option value="">Choose...</option>
                      <option value="1">Pending</option>
                      <option value="2">Approved</option>
                      <option value="3">Disapproved</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="date_of_application" class="form-label mb-1">Date of Application *</label>
                    <input type="date" class="form-control" id="date_of_application" name="date_of_application" value="{{old('date_of_application')}}" disabled required>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="application_remarks" class="form-label mb-1">Application status remarks (why disapproved?)</label>
                  <!-- <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}"> -->
                  <textarea  class="form-control" name="application_remarks" id="application_remarks" value="{{ old('remarks') }}" disabled></textarea>
                </div>
              </div>
            </div>
            
            <hr>

            <div class="row">
              <h5 class="styled-heading">Address</h5>
              <div class="col-lg-2">
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
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="municipality" class="form-label mb-1">Municipality *</label>
                  <select id="municipality" class="form-control" name="municipality"></select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="barangay" class="form-label mb-1">Barangay</label>
                  <select id="barangay" class="form-control" name="barangay"></select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="house_no_zone_purok_sitio" class="form-label mb-1">House No./Zone/Purok/Sitio</label>
                    <input type="text" class="form-control" id="house_no_zone_purok_sitio" name="house_no_zone_purok_sitio" value="{{old('house_no_zone_purok_sitio')}}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="postal_code" class="form-label mb-1">Postal Code *</label>
                  <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{old('postal_code')}}" required>
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
              <h5 class="styled-heading">Electric Service Details</h5>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="membership_or" class="form-label mb-1">Membership OR *</label>
                  <input type="text" class="form-control" id="membership_or" name="membership_or" value="{{old('membership_or')}}" readonly required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="membership_date" class="form-label mb-1">Membership Date *</label>
                  <input type="date" class="form-control" id="membership_date" name="membership_date" value="{{old('membership_date')}}" readonly required>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                  <label for="electric_service_details" class="form-label mb-1">Electric Service Details (Search by account number)</label>
                  <select class="form-control" id="electric_service_details" name="electric_service_details">
                  </select>
                </div>
              </div>
            </div>
          
            <hr>

            <div class="row">
              <h5 class="styled-heading">Educational/Professional Background</h5>
              <div class="col-lg-12">
                <div class="mb-2">
                  <table class="table table-bordered" id="dynamicAddRemove">
                    <tr>
                        <th>Educational Stage</th>
                        <th>Name of School</th>
                        <th>Degree Received</th>
                        <th>Year Graduated</th>
                        <th><button type="button" name="add" id="dynamic-ar" class="btn btn-outline-primary">Add School</button></th>
                    </tr>
                    <!-- <tr>
                        <td><input type="text" name="educationalBackground[0][stage]" placeholder="Ex: Elementary" class="form-control" /></td>
                        <td><input type="text" name="educationalBackground[0][name_of_school]" placeholder="Name of School" class="form-control" /></td>
                        <td><input type="text" name="educationalBackground[0][degree_received]" placeholder="Degree Received" class="form-control" /></td>
                        <td><input type="date" name="educationalBackground[0][year_graduated]" placeholder="Year Graduated" class="form-control" /></td>
                    </tr> -->
                  </table>
                </div>
              </div>
              <!-- <div class="checkbox">
                <label><input type="checkbox" id="changeShip"> Change Ship</label>
              </div> -->
              
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="course_title" class="form-label mb-1">If TESDA &nbsp;</label>
                  <input type="checkbox" class="form-check-input" id="ifTesda" name="tesda">
                </div>
                <div id="withTESDA">
                  <div class="mb-2">
                    <label for="course_title" class="form-label mb-1">Course Title</label>
                    <input type="text" class="form-control" id="course_title" name="course_title" value="{{ old('course_title') }}">
                  </div>
                  <div class="mb-2">
                    <label for="name_of_school" class="form-label mb-1">Name Of School</label>
                    <input type="text" class="form-control" id="name_of_school" name="name_of_school" value="{{ old('name_of_school') }}">
                  </div>
                  <div class="mb-2">
                    <label for="certificate_no" class="form-label mb-1">National Certificate No.</label>
                    <input type="text" class="form-control" id="certificate_no" name="certificate_no" value="{{ old('certificate_no') }}">
                  </div>
                  <div class="mb-2">
                    <label for="tesda_date_issued" class="form-label mb-1">Date Issued</label>
                    <input type="date" class="form-control" id="tesda_date_issued" name="tesda_date_issued" value="{{ old('tesda_date_issued') }}">
                  </div>
                  <div class="mb-2">
                    <label for="tesda_validity" class="form-label mb-1">Valid Until</label>
                    <input type="date" class="form-control" id="tesda_validity" name="tesda_validity" value="{{ old('tesda_validity') }}">
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="RME" class="form-label mb-1">If Registered Master Electrician &nbsp;</label>
                  <input type="checkbox" class="form-check-input" id="ifRME" name="RME">
                </div>
                <div id="withRMELicense">
                  <div class="mb-2">
                    <label for="rme_license_no" class="form-label mb-1">RME License No.</label>
                    <input type="text" class="form-control" id="rme_license_no" name="rme_license_no" value="{{ old('rme_license_no') }}">
                  </div>
                  <div class="mb-2">
                    <label for="rme_license_issued_on" class="form-label mb-1">RME Issued On</label>
                    <input type="date" class="form-control" id="rme_license_issued_on" name="rme_license_issued_on" value="{{ old('rme_license_issued_on') }}">
                  </div>
                  <div class="mb-2">
                    <label for="rme_license_issued_at" class="form-label mb-1">RME Issued At</label>
                    <input type="text" class="form-control" id="rme_license_issued_at" name="rme_license_issued_at" value="{{ old('rme_license_issued_at') }}">
                  </div>
                  <div class="mb-2">
                    <label for="rme_validity" class="form-label mb-1">RME Valid Until</label>
                    <input type="date" class="form-control" id="rme_validity" name="rme_validity" value="{{ old('rme_validity') }}">
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="course_title" class="form-label mb-1">If Registered Electrical Engineer &nbsp;</label>
                  <input type="checkbox" class="form-check-input" id="ifREE" name="REE">
                </div>
                <div id="withREELicense">
                  <div class="mb-2">
                    <label for="ree_license_no" class="form-label mb-1">RME License No.</label>
                    <input type="text" class="form-control" id="ree_license_no" name="ree_license_no" value="{{ old('ree_license_no') }}">
                  </div>
                  <div class="mb-2">
                    <label for="ree_license_issued_on" class="form-label mb-1">RME Issued On</label>
                    <input type="date" class="form-control" id="ree_license_issued_on" name="ree_license_issued_on" value="{{ old('ree_license_issued_on') }}">
                  </div>
                  <div class="mb-2">
                    <label for="ree_license_issued_at" class="form-label mb-1">RME Issued At</label>
                    <input type="text" class="form-control" id="ree_license_issued_at" name="ree_license_issued_at" value="{{ old('ree_license_issued_at') }}">
                  </div>
                  <div class="mb-2">
                    <label for="ree_validity" class="form-label mb-1">RME Valid Until</label>
                    <input type="date" class="form-control" id="ree_validity" name="ree_validity" value="{{ old('ree_validity') }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Remarks</label>
                  <!-- <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}"> -->
                  <textarea  class="form-control" name="remarks" value="{{ old('remarks') }}"></textarea>
                </div>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                <a class="btn btn-primary" href="{{ route('electrician.index') }}"><i class="fa fa-arrow-left me-2"></i>Back </a>
                <button type="submit" class="btn btn-success"><i class="fa fa-check me-2"></i>Submit</button>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  var form = $('#myForm'),
    TESDAcheckbox = $('#ifTesda'),
    TESDABlock = $('#withTESDA');

    RMEcheckbox = $('#ifRME'),
    RMEBlock = $('#withRMELicense');

    REEcheckbox = $('#ifREE'),
    REEBlock = $('#withREELicense');

  TESDABlock.hide();
  TESDABlock.find('input').attr('required', false);
  RMEBlock.hide();
  RMEBlock.find('input').attr('required', false);
  REEBlock.hide();
  REEBlock.find('input').attr('required', false);

  TESDAcheckbox.on('click', function() {
      if($(this).is(':checked')) {
        TESDABlock.show();
        TESDABlock.find('input').attr('required', true);
      } else {
        TESDABlock.hide();
        TESDABlock.find('input').attr('required', false);
      }
  })

  RMEcheckbox.on('click', function() {
      if($(this).is(':checked')) {
        RMEBlock.show();
        RMEBlock.find('input').attr('required', true);
      } else {
        RMEBlock.hide();
        RMEBlock.find('input').attr('required', false);
      }
  })

  REEcheckbox.on('click', function() {
      if($(this).is(':checked')) {
        REEBlock.show();
        REEBlock.find('input').attr('required', true);
      } else {
        REEBlock.hide();
        REEBlock.find('input').attr('required', false);
      }
  })

  var i = 0;
    $("#dynamic-ar").click(function () {
        ++i;
        // $("#dynamicAddRemove").append('<tr><td><input type="text" name="educationalBackground[' + i +
        //     '][subject]" placeholder="Enter subject" class="form-control" /> <input type="text" name="educationalBackground[' + i +
        //     '][subject]" placeholder="Enter subject" class="form-control" /> <input type="text" name="educationalBackground[' + i +
        //     '][subject]" placeholder="Enter subject" class="form-control" /></td><td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td> </tr>'
        //     );

            $("#dynamicAddRemove").append(
              // `<tr>
              //   <td>
              //     <select id="educational_stage" class="form-control" name="educationalBackground[`+ i +`][educational_stage]" required>
              //       <option value="">Choose...</option>
              //       @foreach (Config::get('constants.educational_background') as $id)          
              //         <option value="{{ $id['id'] }}" id="" {{ old('educational_stage') ? 'selected' : '' }}>{{ $id['name'] }}</option>
              //       @endforeach 
              //     </select>
              //   </td>
              //   <td><input type="text" name="educationalBackground[`+ i +`][name_of_school]" placeholder="Name of School" class="form-control" required /></td>
              //   <td><input type="text" name="educationalBackground[`+ i +`][degree_recieved]" placeholder="Degree Received" class="form-control" required /></td>
              //   <td><input type="date" name="educationalBackground[`+ i +`][year_graduated]" placeholder="Year Graduated" class="form-control" required /></td>
              //   <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
              // </tr>`
              `<tr>
                <td>
                  <select id="educational_stage" class="form-control" name="educationalBackground[`+ i +`][educational_stage]" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.educational_background') as $id)          
                      <option value="{{ $id['id'] }}" id="" {{ old('educational_stage') ? 'selected' : '' }}>{{ $id['name'] }}</option>
                    @endforeach 
                  </select>
                </td>
                <td><input type="text" name="educationalBackground[`+ i +`][name_of_school]" placeholder="Name of School" class="form-control" required /></td>
                <td><input type="text" name="educationalBackground[`+ i +`][degree_recieved]" placeholder="Degree Received" class="form-control" required /></td>
                <td>
                <input type="text" id="yearPicker" name="educationalBackground[`+ i +`][year_graduated]" class="form-control" 
       placeholder="YYYY" pattern="[0-9]{4}" maxlength="4" required>
                </td>
                <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
              </tr>`
            );
    });
    $(document).on('click', '.remove-input-field', function () {
        $(this).parents('tr').remove();
    });

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
          url: "{{route('fetchAllAccounts')}}",
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
      document.getElementById('membership_or').value = data.or_no;
      document.getElementById('membership_date').value = data.or_no_date;
      return data.id + " | " +data.Name + " | " + data.Address
      // console.log(data.OR No Issued);
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

  const application_status = document.getElementById('application_status');
  const application_remarks = document.getElementById('application_remarks');
  const date_of_application = document.getElementById('date_of_application');

  // Add event listener to dropdown
  application_status.addEventListener('change', function() {
      // Toggle visibility of text field based on selected option
      if (application_status.value == 3) {
        application_remarks.setAttribute('required', 'required');
        application_remarks.removeAttribute('disabled');

        date_of_application.removeAttribute('required');
        date_of_application.setAttribute('disabled', 'disabled');
        date_of_application.value = '';

      } else if (application_status.value == 2) {
        date_of_application.setAttribute('required', 'required');
        date_of_application.removeAttribute('disabled');

        application_remarks.removeAttribute('required');
        application_remarks.setAttribute('disabled', 'disabled');
        application_remarks.value = '';
      } else {
        application_remarks.removeAttribute('required');
        application_remarks.setAttribute('disabled', 'disabled');
        application_remarks.value = '';

        date_of_application.removeAttribute('required');
        date_of_application.setAttribute('disabled', 'disabled');
        date_of_application.value = '';
      }
  });

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