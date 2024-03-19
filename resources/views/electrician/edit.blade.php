@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit Electrician</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('electrician.index') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => ['electrician.update', $data[0]->id],'method'=>'PUT')) !!}
            <div class="row">
              <h5 class="styled-heading">Basic Information</h5>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="control_no" class="form-label mb-1">Application No. *</label>
                    <input type="text" class="form-control" id="control_no" name="control_no" value="{{ $data[0]->control_number }}" readonly>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="first_name" class="form-label mb-1">First Name *</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" value="{{ $data[0]->first_name }}"  required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="middle_name" class="form-label mb-1">Middle Name</label>
                  <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ $data[0]->middle_name }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="last_name" class="form-label mb-1">Last Name *</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ $data[0]->last_name }}" required>
                </div>
              </div>
              <div class="col-lg-1">
                <div class="mb-2">
                  <label for="name_ext" class="form-label mb-1">Name Ext.</label>
                    <input type="text" class="form-control" id="name_ext" name="name_ext" value="{{ $data[0]->name_ext }}">
                </div>
              </div>
              <div class="col-lg-1">
                <div class="mb-2">
                  <label for="sex" class="form-label mb-1">Sex *</label>
                    <select id="type_of_id" class="form-control" name="sex" required>
                      <option value="0" @selected( $data[0]->sex )>Male</option>
                      <option value="1" @selected( $data[0]->sex )>Female</option>
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
                        <option value="{{ $id['id'] }}" id=""  @selected($data[0]->civil_status == $id['id'] )>{{ $id['status_name'] }}</option>
                      @endforeach 
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $data[0]->date_of_birth }}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="email_address" class="form-label mb-1">Email Address</label>
                    <input type="email" class="form-control" id="email_address" name="email_address" value="{{ $data[0]->email_address }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="facebook_account" class="form-label mb-1">Facebook Account</label>
                    <input type="text" class="form-control" id="facebook_account" name="facebook_account" value="{{ $data[0]->fb_account }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="spouse_first_name" class="form-label mb-1">Spouse First Name</label>
                    <input type="text" class="form-control" id="spouse_first_name" name="spouse_first_name" value="{{ $data[0]->spouse_first_name }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="spouse_middle_name" class="form-label mb-1">Spouse Middle Name</label>
                  <input type="text" class="form-control" id="spouse_middle_name" name="spouse_middle_name" value="{{ $data[0]->spouse_middle_name }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="spouse_last_name" class="form-label mb-1">Spouse Last Name</label>
                  <input type="text" class="form-control" id="spouse_last_name" name="spouse_last_name" value="{{ $data[0]->spouse_last_name }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="type_of_id" class="form-label mb-1">Type of Valid ID *</label>
                  <select id="type_of_id" class="form-control" name="type_of_id" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.valid_id') as $id)          
                      <option value="{{ $id['id'] }}" id="" @selected($data[0]->valid_id_type == $id['id'] )>{{ $id['name'] }}</option>
                    @endforeach 
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="valid_id_no" class="form-label mb-1">Valid ID No. *</label>
                    <input type="text" class="form-control" id="valid_id_no" name="valid_id_no" value="{{ $data[0]->valid_id_number }}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="contact_no" class="form-label mb-1">Primary Contact No. *</label>
                    <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ $data[0]->electrician_contact_numbers[0]->contact_no }}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="contact_no_ext" class="form-label mb-1">Secondary Contact No.</label>
                    <input type="text" class="form-control" id="contact_no_ext" name="contact_no_ext" value="{{ count($data[0]->electrician_contact_numbers) > 1 ? $data[0]->electrician_contact_numbers[1]->contact_no : null }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="application_type" class="form-label mb-1">Application Type</label>
                    <select name="application_type" id="application_type" class="form-control">
                      <option value="1" @selected( $data[0]->application_type == '1' )>New</option>
                      <option value="2" @selected( $data[0]->application_type == '2' )>Renewal</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="application_status" class="form-label mb-1">Application Status</label>
                    <select name="application_status" id="application_status" class="form-control"> 
                      <option value="1" {{ $data[0]->application_status == '1' ? 'selected' : ''}}>Pending</option>
                      <option value="2" {{ $data[0]->application_status == '2' ? 'selected' : ''}}>Approved</option>
                      <option value="3" {{ $data[0]->application_status == '3' ? 'selected' : ''}}>Disapproved</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="date_of_application" class="form-label mb-1">Date of Application *</label>
                    <input type="date" class="form-control" id="date_of_application" name="date_of_application" value="{{ $data[0]->date_of_application }}" {{ $data[0]->date_of_application ? '' : 'disabled' }} required>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="application_remarks" class="form-label mb-1">Application status remarks (why disapproved?)</label>
                  <!-- <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}"> -->
                  <textarea  class="form-control" name="application_remarks" id="application_remarks" value="{{ $data[0]->application_status_remarks }}" {{ $data[0]->application_status_remarks ? '' : 'disabled' }}>{{ $data[0]->application_status_remarks }}</textarea>
                </div>
              </div>
            </div>
            
            <hr>

            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="district" class="form-label mb-1">District *</label>
                  <select id="district" class="form-control" name="district" required>
                      <option value="">Choose...</option>
                      @foreach ($districts as $district)                        
                          <option value="{{ $district->id }}" id="{{ $district->id }}" @selected($data[0]->electrician_addresses[0]->district_id == $district->id )>{{$district->district_name}}</option>
                      @endforeach 
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="municipality" class="form-label mb-1">Municipality *</label>
                  <select id="municipality" class="form-control" name="municipality">
                    <option value="{{ $data[0]->electrician_addresses[0]->municipality->id }}" >{{$data[0]->electrician_addresses[0]->municipality->municipality_name }}</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="barangay" class="form-label mb-1">Barangay</label>
                  <select id="barangay" class="form-control" name="barangay">
                    <option value="{{ $data[0]->electrician_addresses[0]->barangay->id }}" >{{$data[0]->electrician_addresses[0]->barangay->barangay_name }}</option>
                  </select>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="house_no_zone_purok_sitio" class="form-label mb-1">House No./Zone/Purok/Sitio</label>
                    <input type="text" class="form-control" id="house_no_zone_purok_sitio" name="house_no_zone_purok_sitio" value="{{ $data[0]->electrician_addresses[0]->house_no_sitio_purok_street }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="postal_code" class="form-label mb-1">Postal Code *</label>
                  <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ $data[0]->electrician_addresses[0]->postal_code }}">
                </div>
              </div>
            </div>

            <hr>

            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="membership_or" class="form-label mb-1">Membership OR*</label>
                  <input type="text" class="form-control" id="membership_or" name="membership_or" value="{{ $data[0]->electrician_accounts[0]->membership_or }}">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="membership_date" class="form-label mb-1">Membership Date*</label>
                  <input type="date" class="form-control" id="membership_date" name="membership_date" value="{{ $data[0]->electrician_accounts[0]->membership_date }}">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="mb-2">
                
                  <label for="electric_service_details" class="form-label mb-1">Electric Service Details (Search by account number) *</label>
                  @if(count($account) != 0)
                  <input type="hidden" id="hidden_value" value="{{$account[0]->id}} | {{$account[0]->Name}} | {{$account[0]->Address}}">
                  <select class="form-control" id="electric_service_details" name="electric_service_details" readonly required>
                    <option value="{{$account[0]->id}}"></option>
                  </select>
                  @else
                  <select class="form-control" id="electric_service_details" name="electric_service_details">
                  </select>
                  @endif
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
                    @foreach($data[0]->electrician_educational_backgrounds as $school)
                      
                      <tr>
                          <td>
                          
                            <select id="educational_stage" class="form-control" name="educationalBackground[{{$loop->iteration}}][educational_stage]" required>
                            @foreach (Config::get('constants.educational_background') as $background)          
                              <option value="{{ $background['id'] }}" @selected($school->educational_stage == $background['id'] ) >{{ $background['name'] }}</option>
                            @endforeach 
                          </td>
                          <td><input type="text" name="educationalBackground[{{$loop->iteration}}][name_of_school]" placeholder="Name of School" class="form-control" value="{{ $school->name_of_school }}" /></td>
                          <td><input type="text" name="educationalBackground[{{$loop->iteration}}][degree_recieved]" placeholder="Degree Received" class="form-control" value="{{ $school->degree_recieved }}" /></td>
                          <!-- <td><input type="date" name="educationalBackground[{{$loop->iteration}}][year_graduated]" placeholder="Year Graduated" class="form-control" value="{{ $school->year_graduated }}" /></td> -->
                          <td><input type="text" id="yearPicker" name="educationalBackground[{{$loop->iteration}}][year_graduated]" class="form-control" placeholder="YYYY" pattern="[0-9]{4}" maxlength="4" value="{{ $school->year_graduated }}" required></td>
                          <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
                      </tr>
                    @endforeach

                    
                  </table>
                </div>
              </div>
              <!-- <div class="checkbox">
                <label><input type="checkbox" id="changeShip"> Change Ship</label>
              </div> -->
              
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="course_title" class="form-label mb-1">If TESDA &nbsp;</label>
                  <input type="checkbox" class="form-check-input" id="ifTesda" name="tesda" @checked($data[0]->tesda_course_title > 0)>
                </div>
                <div id="withTESDA">
                  <div class="mb-2">
                    <label for="course_title" class="form-label mb-1">Course Title</label>
                    <input type="text" class="form-control" id="course_title" name="course_title" value="{{ $data[0]->tesda_course_title }}">
                  </div>
                  <div class="mb-2">
                    <label for="name_of_school" class="form-label mb-1">Name Of School</label>
                    <input type="text" class="form-control" id="name_of_school" name="name_of_school" value="{{ $data[0]->tesda_name_of_school }}">
                  </div>
                  <div class="mb-2">
                    <label for="certificate_no" class="form-label mb-1">National Certificate No.</label>
                    <input type="text" class="form-control" id="certificate_no" name="certificate_no" value="{{ $data[0]->tesda_national_certificate_no }}">
                  </div>
                  <div class="mb-2">
                    <label for="tesda_date_issued" class="form-label mb-1">Date Issued</label>
                    <input type="date" class="form-control" id="tesda_date_issued" name="tesda_date_issued" value="{{ $data[0]->tesda_date_issued }}">
                  </div>
                  <div class="mb-2">
                    <label for="tesda_validity" class="form-label mb-1">Valid Until</label>
                    <input type="date" class="form-control" id="tesda_validity" name="tesda_validity" value="{{ $data[0]->tesda_valid_until_date }}">
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="RME" class="form-label mb-1">If Registered Master Electrician &nbsp;</label>
                  <input type="checkbox" class="form-check-input" id="ifRME" name="RME" @checked($data[0]->rme_license_no > 0)>
                </div>
                <div id="withRMELicense">
                  <div class="mb-2">
                    <label for="rme_license_no" class="form-label mb-1">RME License No.</label>
                    <input type="text" class="form-control" id="rme_license_no" name="rme_license_no" value="{{ $data[0]->rme_license_no }}">
                  </div>
                  <div class="mb-2">
                    <label for="rme_license_issued_on" class="form-label mb-1">RME Issued On</label>
                    <input type="date" class="form-control" id="rme_license_issued_on" name="rme_license_issued_on" value="{{ $data[0]->rme_issued_on }}">
                  </div>
                  <div class="mb-2">
                    <label for="rme_license_issued_at" class="form-label mb-1">RME Issued At</label>
                    <input type="text" class="form-control" id="rme_license_issued_at" name="rme_license_issued_at" value="{{ $data[0]->rme_issued_at }}">
                  </div>
                  <div class="mb-2">
                    <label for="rme_validity" class="form-label mb-1">RME Valid Until</label>
                    <input type="date" class="form-control" id="rme_validity" name="rme_validity" value="{{ $data[0]->rme_valid_until }}">
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="course_title" class="form-label mb-1">If Registered Electrical Engineer &nbsp;</label>
                  <input type="checkbox" class="form-check-input" id="ifREE" name="REE" @checked($data[0]->ree_license_no > 0)>
                </div>
                <div id="withREELicense">
                  <div class="mb-2">
                    <label for="ree_license_no" class="form-label mb-1">REE License No.</label>
                    <input type="text" class="form-control" id="ree_license_no" name="ree_license_no" value="{{ $data[0]->ree_license_no }}">
                  </div>
                  <div class="mb-2">
                    <label for="ree_license_issued_on" class="form-label mb-1">REE Issued On</label>
                    <input type="date" class="form-control" id="ree_license_issued_on" name="ree_license_issued_on" value="{{ $data[0]->ree_issued_on }}">
                  </div>
                  <div class="mb-2">
                    <label for="ree_license_issued_at" class="form-label mb-1">REE Issued At</label>
                    <input type="text" class="form-control" id="ree_license_issued_at" name="ree_license_issued_at" value="{{ $data[0]->ree_issued_at }}">
                  </div>
                  <div class="mb-2">
                    <label for="ree_validity" class="form-label mb-1">REE Valid Until</label>
                    <input type="date" class="form-control" id="ree_validity" name="ree_validity" value="{{ $data[0]->ree_valid_until }}">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Remarks</label>
                  <!-- <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}"> -->
                  <textarea  class="form-control" name="remarks" value="{{ old('remarks') }}">{{ $data[0]->remarks }}</textarea>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <!-- <hr> -->

            <!-- <div class="row">
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
            </div> -->
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
              `<tr>
                <td>
                  <select id="educational_stage" class="form-control" name="educationalBackground[${i}][educational_stage]" required>
                    <option value="">Choose...</option>
                    @foreach (Config::get('constants.educational_background') as $id)          
                      <option value="{{ $id['id'] }}" id="" {{ old('educational_stage') ? 'selected' : '' }}>{{ $id['name'] }}</option>
                    @endforeach 
                  </select>
                </td>
                <td><input type="text" name="educationalBackground[${i}][name_of_school]" placeholder="Name of School" class="form-control" required/></td>
                <td><input type="text" name="educationalBackground[${i}][degree_recieved]" placeholder="Degree Received" class="form-control" required/></td>
                <td>
                <input type="text" id="yearPicker" name="educationalBackground[${i}][year_graduated]" class="form-control" 
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

    if($('#ifTesda').is(':checked')) {
      TESDABlock.show();
      TESDABlock.find('input').attr('required', true);
    } else {
      TESDABlock.hide();
      TESDABlock.find('input').attr('required', false);
    }

    if($('#ifRME').is(':checked')) {
      RMEBlock.show();
      RMEBlock.find('input').attr('required', true);
    } else {
      RMEBlock.hide();
      RMEBlock.find('input').attr('required', false);
    }

    if($('#ifREE').is(':checked')) {
      REEBlock.show();
      REEBlock.find('input').attr('required', true);
    } else {
      REEBlock.hide();
      REEBlock.find('input').attr('required', false);
    }

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
    var account = "{{ $account }}";
    // console.log(account.length)
    if (account.length != 2) {
      $("#electric_service_details").prop("disabled", true);
    }
  
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