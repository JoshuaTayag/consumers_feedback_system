@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Create Complaint</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('electricianComplaintIndex') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'electricianComplaintStore','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
            <div class="row">
              <h5 class="styled-heading">Complainant Basic Information</h5>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="control_number" class="form-label mb-1">Complaint No. *</label>
                    <input type="text" class="form-control" id="control_number" name="control_number" value="{{$control_id}}" disabled>
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="complainant" class="form-label mb-1">Name of Complainant *</label>
                  <input type="text" class="form-control" id="complainant" name="complainant" value="{{old('complainant')}}" required>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="contact_no" class="form-label">Contact No (Ex: 09*********)</label>
                  <input type="text" class="form-control" value="{{ old('contact_no') }}" id="contact_no" pattern="^((09))[0-9]{9}" name="contact_no" maxlength="11">
                </div>
              </div>
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
            </div>
            <div class="row">
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="municipality" class="form-label mb-1">Municipality *</label>
                  <select id="municipality" class="form-control" name="municipality" required></select>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="barangay" class="form-label mb-1">Barangay *</label>
                  <select id="barangay" class="form-control" name="barangay" required></select>
                </div>
              </div>
            </div>
            
            <hr>

            <div class="row">
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="electrician" class="form-label mb-1">Electrician *</label>
                    <select id="electrician" class="form-control" name="electrician" required>
                      <option value="">Choose...</option>
                        @foreach ($electricians as $electrician)          
                          <option value="{{ $electrician->id }}" id="" {{ old('electrician') ? 'selected' : '' }}>{{ $electrician->last_name }}, {{ $electrician->first_name }}</option>
                        @endforeach 
                    </select>
                  <!-- <input type="text" class="form-control" id="electrician" name="electrician" value="{{old('electrician')}}" required> -->
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="nature_of_complaint" class="form-label mb-1">Nature of Complaint *</label>
                    <select id="nature_of_complaint" class="form-control" name="nature_of_complaint" required>
                      <option value="">Choose...</option>
                      @foreach (Config::get('constants.nature_of_complaint_barangay_electrician') as $complaint)          
                        <option value="{{ $complaint['id'] }}" id="" {{ old('nature_of_complaint') ? 'selected' : '' }}>{{ $complaint['name'] }}</option>
                      @endforeach 
                      <option value="others">Others</option>
                    </select>
                    <!-- <input type="text" class="form-control" id="nature_of_complaint" name="nature_of_complaint" value="{{old('nature_of_complaint')}}" required> -->
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="other_complaint" class="form-label mb-1">Other Complaint.</label>
                    <input type="text" class="form-control" id="other_complaint" name="other_complaint" disabled>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="complaint_date" class="form-label mb-1">Date *</label>
                    <input type="date" class="form-control" id="complaint_date" name="complaint_date" value="{{old('complaint_date')}}" required>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="sanction_type" class="form-label mb-1">Diciplinary Action/Sanction *</label>
                    <select name="sanction_type" id="sanction_type" class="form-control" required>
                      <option value="">Choose...</option>
                      <option value="1">Reprimand</option>
                      <option value="2">Suspension</option>
                      <option value="3">Revocation of Accreditation</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-2 d-none" id="revocation_date_col">
                <div class="mb-2">
                  <label for="revocation_date" class="form-label mb-1">Effectivity Date: </label>
                    <input type="date" class="form-control border border-warning" id="revocation_date" name="revocation_date">
                </div>
              </div>
              <div class="col-lg-2 d-none" id="suspension_from_col">
                <div class="mb-2">
                  <label for="suspension_from" class="form-label mb-1">Suspension From: </label>
                    <input type="date" class="form-control border border-warning" id="suspension_from" name="suspension_from">
                </div>
              </div>
              <div class="col-lg-2 d-none" id="suspension_to_col">
                <div class="mb-2">
                  <label for="suspension_to" class="form-label mb-1">Suspension To: </label>
                    <input type="date" class="form-control border border-warning" id="suspension_to" name="suspension_to">
                </div>
              </div>
              <div class="col-lg-6" id="sanction_remarks">
                <div class="mb-2">
                  <label for="sanction_remarks" class="form-label mb-1">Sanction Remarks: </label>
                    <input type="text" class="form-control border"  name="sanction_remarks">
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="status_of_complaint" class="form-label mb-1">Status of Complaint *</label>
                    <select name="status_of_complaint" id="status_of_complaint" class="form-control" required>
                      <option value="">Choose...</option>
                      <option value="1">Ongoing</option>
                      <option value="2">Resolved</option>
                      <option value="3">Un-Resolved</option>
                    </select>
                </div>
              </div>
              <div class="col-lg-5 d-none" id="explanation_col">
                <div class="mb-2">
                  <label for="status_explanation" class="form-label mb-1">Explanation</label>
                    <input type="text" class="form-control border border-warning" id="status_explanation" name="status_explanation">
                </div>
              </div>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="act_of_misconduct" class="form-label mb-1">Act of Misconduct *</label>
                    <select id="act_of_misconduct" class="form-control" name="act_of_misconduct" required>
                      <option value="">Choose...</option>
                      @foreach (Config::get('constants.act_of_misconduct') as $complaint)          
                        <option value="{{ $complaint['id'] }}" id="" {{ old('act_of_misconduct') ? 'selected' : '' }}>{{ $complaint['name'] }}</option>
                      @endforeach 
                    </select>
                    <!-- <input type="text" class="form-control" id="name_ext" name="name_ext" value="{{old('name_ext')}}" required> -->
                </div>
              </div>
              <div class="col-lg-4">
                <div class="mb-2">
                  <label for="attached_file" class="form-label mb-1">Attach File (PDF) *</label>
                    <input type="file" class="form-control border border-default" id="attached_file" name="attached_file">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="remarks" class="form-label mb-1">Complaint Remarks</label>
                  <!-- <input type="text" class="form-control" id="remarks" name="remarks" value="{{ old('remarks') }}"> -->
                  <textarea  class="form-control" name="remarks" value="{{ old('remarks') }}"></textarea>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  document.addEventListener('DOMContentLoaded', function () {
      // Get references to the textboxes
      const textbox1 = document.getElementById('nature_of_complaint');
      const textbox2 = document.getElementById('other_complaint');
      
      // Add event listener to textbox1
      textbox1.addEventListener('input', function () {
          // Check if textbox1 has a value
          // console.log(textbox1.value.trim());
          if (textbox1.value.trim() == 'others') {
              // Enable textbox2 if textbox1 has a value
              textbox2.disabled = false;
              textbox2.required = true;
          } else {
              // Otherwise, disable textbox2
              textbox2.disabled = true;
              textbox2.value = "";
              textbox2.required = false;
          }
      });
  });

  // Get dropdown and text field elements
  const sanction = document.getElementById('sanction_type');
  const revocation_date_col = document.getElementById('revocation_date_col');
  const suspension_from_col = document.getElementById('suspension_from_col');
  const suspension_to_col = document.getElementById('suspension_to_col');

  const revocation_date = document.getElementById('revocation_date');
  const suspension_from = document.getElementById('suspension_from');
  const suspension_to = document.getElementById('suspension_to');

  const status_of_complaint = document.getElementById('status_of_complaint');
  const explanation_col = document.getElementById('explanation_col');
  const status_explanation = document.getElementById('status_explanation');
  

  // Add event listener to dropdown
  sanction.addEventListener('change', function() {
      // Toggle visibility of text field based on selected option
      if (sanction.value == 2) {
        suspension_from_col.classList.remove('d-none');
        suspension_to_col.classList.remove('d-none');
        revocation_date_col.classList.add('d-none');

        // add required attribute in to textfield
        suspension_to.setAttribute('required', 'required');
        suspension_from.setAttribute('required', 'required');
        revocation_date.removeAttribute('required');
        revocation_date.value = '';
      } else if (sanction.value == 3) {
        revocation_date_col.classList.remove('d-none');
        suspension_from_col.classList.add('d-none');
        suspension_to_col.classList.add('d-none');

        // add required attribute in to textfield
        revocation_date.setAttribute('required', 'required');
        suspension_from.removeAttribute('required');
        suspension_to.removeAttribute('required');
        suspension_from.value = '';
        suspension_to.value = '';
      } else {
        suspension_from_col.classList.add('d-none');
        suspension_to_col.classList.add('d-none');
        revocation_date_col.classList.add('d-none');

        // add required attribute and clear the value of textfield
        suspension_from.removeAttribute('required');
        suspension_to.removeAttribute('required');
        revocation_date.removeAttribute('required');
        suspension_from.value = '';
        suspension_to.value = '';
        revocation_date.value = '';
      }
  });

  // Add event listener to dropdown
  status_of_complaint.addEventListener('change', function() {
      // Toggle visibility of text field based on selected option
      if (status_of_complaint.value == 3) {
        explanation_col.classList.remove('d-none');
        status_explanation.setAttribute('required', 'required');
      } else {
        explanation_col.classList.add('d-none');
        status_explanation.removeAttribute('required');
        status_explanation.value = '';
      }
  });

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