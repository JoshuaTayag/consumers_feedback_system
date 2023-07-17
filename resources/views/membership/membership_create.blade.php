@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <span class="mb-0 align-middle fs-3">Insert Record</span>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a class="btn btn-danger btn-md text-end" href="{{ route('membership.index') }}">
                                <i class="fa fa-cancel"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action = "{{ route('membership.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="or_no" class="form-label mb-1">OR No. *</label>
                                    <input type="text" class="form-control" id="or_no" name="or_no">
                                </div>
                                <div class="mb-2">
                                    <label for="first_name" class="form-label mb-1">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name">
                                </div>
                                <div class="mb-2">
                                    <label for="middle_name" class="form-label mb-1">Middle Name *</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                                </div>
                                <div class="mb-2">
                                    <label for="last_name" class="form-label mb-1">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name">
                                </div>
                                <div class="mb-2">
                                    <div class="row my-2">
                                        <div class="col-lg-10">
                                            <label for="spouse" class="form-label mb-1">Spouse (If Married)</label>
                                            <input type="text" class="form-control" id="spouse" name="spouse">
                                        </div>
                                        <div class="col-lg-2">
                                            <label for="joint" class="form-label mb-1">Joint</label><br>
                                            <input type="checkbox" class="form-check-input" id="joint" name="joint">
                                        </div>
                                        {{-- <div class="col-lg-2">
                                            <label for="single" class="form-label mb-1">Single</label><br>
                                            <input type="checkbox" class="form-check-input" id="single" name="single" checked>
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                                <div class="mb-2">
                                    <label for="contact_no" class="form-label mb-1">Contact No. </label>
                                    <input type="number" class="form-control" id="contact_no" name="contact_no">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="or_no_issued" class="form-label mb-1">OR No. Issued *</label>
                                    <input type="date" class="form-control" id="or_no_issued" name="or_no_issued">
                                </div>
                                <div class="mb-2">
                                    <label for="district" class="form-label mb-1">District *</label>
                                    <select id="district" class="form-control" name="district" required>
                                        <option value="">Choose...</option>
                                        @foreach ($districts as $district)                        
                                            <option value="{{ $district->district_name }}" id="{{ $district->id }}">{{$district->district_name}}</option>
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="municipality" class="form-label mb-1">Municipality *</label>
                                    <select id="municipality" class="form-control" name="municipality"></select>
                                </div>
                                <div class="mb-2">
                                    <label for="barangay" class="form-label mb-1">Barangay</label>
                                    <select id="barangay" class="form-control" name="barangay"></select>
                                </div>
                                <div class="mb-2">
                                    <label for="sitio" class="form-label mb-1">Sitio </label>
                                    <input type="text" class="form-control" id="sitio" name="sitio">
                                </div>
                                <div class="mb-2">
                                    <label for="membership_status" class="form-label mb-1">Membership Status *</label>
                                    <select id="membership_status" class="form-control" name="membership_status" required>
                                      <option value="">Choose...</option>
                                      @foreach (Config::get('constants.membership_status') as $status)          
                                        <option value="{{ $status['status_name'] }}" id="">{{ $status['status_name'] }}</option>
                                      @endforeach 
                                    </select>
                                </div>
                                <div class="mb-2">
                                  <label for="membership_type" class="form-label mb-1">Membership Type *</label>
                                  <select id="membership_type" class="form-control" name="membership_type" required>
                                    <option value="">Choose...</option>
                                    @foreach (Config::get('constants.membership_type') as $type)          
                                      <option value="{{ $type['name'] }}" id="">{{ $type['name'] }}</option>
                                    @endforeach 
                                  </select>
                              </div>
                            </div>  
                            <div class="col-lg-12">
                              <label for="remarks" class="form-label mb-1">Remarks</label>
                              <textarea class="form-control" name="remarks" id="exampleFormControlTextarea1"></textarea>
                            </div>
                            <div class="col-lg-12 pt-3">
                            <button type="submit" class="btn btn-primary col-lg-12">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
$(document).ready(function () {
  
  /*------------------------------------------
  --------------------------------------------
  Country Dropdown Change Event
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
                        .municipality_name + '" id="'+ value.id +'">' + value.municipality_name + '</option>');
                });
              $('#barangay').html('<option value="">-- Select Barangay --</option>');
          }
      });
  });

  /*------------------------------------------
  --------------------------------------------
  State Dropdown Change Event
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
                        .barangay_name + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
              });
          }
      });
  });

  $("#single").click(function() {
    if( $("#spouse").val().length != "" )  {
    alert('Pls clear the Spouse field!');
    $(this).prop('checked', false)
    }
    
});

$("#joint").click(function() {
    if( $("#spouse").val().length == "" )  {
    alert('Pls fill-up the Spouse field!');
    $(this).prop('checked', false)
    }
    
});

$("#spouse").on("input",function(){
    if( $(this).val().length == "" )  {
    $('#joint').prop('checked', false)
    }
    else{
    $('#single').prop('checked', false)
    }
});

});
</script>
@endsection