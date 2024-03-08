@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Electrician Complaint</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('electricianComplaintIndex') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          {!! Form::open(array('route' => 'electricianComplaintStore','method'=>'POST')) !!}
            <div class="row">
              <h5 class="styled-heading">Basic Information</h5>
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="control_number" class="form-label mb-1">Complaint No. *</label>
                    <input type="number" class="form-control" id="control_number" name="control_number" required>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="complainant" class="form-label mb-1">Name of Complainant *</label>
                  <input type="text" class="form-control" id="complainant" name="complainant" value="{{old('complainant')}}" required>
                </div>
              </div>
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
            </div>
            
            <hr>

            <div class="row">
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
              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="complaint_date" class="form-label mb-1">Date *</label>
                    <input type="date" class="form-control" id="complaint_date" name="complaint_date" value="{{old('complaint_date')}}" required>
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