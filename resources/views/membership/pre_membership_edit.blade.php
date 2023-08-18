@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">{{ __('Update Record') }}</div>

                <div class="card-body">
                    <form method="POST" action = "{{ route('preMembershipUpdate', $pre_member->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="first_name" class="form-label mb-1">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" value="{{ $pre_member->first_name }}" name="first_name">
                                </div>
                                <div class="mb-2">
                                    <label for="middle_name" class="form-label mb-1">Middle Name *</label>
                                    <input type="text" class="form-control" id="middle_name" value="{{ $pre_member->middle_name }}" name="middle_name">
                                </div>
                                <div class="mb-2">
                                    <label for="last_name" class="form-label mb-1">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" value="{{ $pre_member->last_name }}" name="last_name">
                                </div>
                                <div class="mb-2">
                                    <div class="row my-2">
                                        <div class="col-lg-8">
                                            <label for="spouse" class="form-label mb-1">Spouse (If Married)</label>
                                            <input type="text" class="form-control" id="spouse" value="{{ $pre_member->spouse }}" name="spouse">
                                        </div>
                                        <div class="col-lg-2 text-center">
                                            <label for="joint" class="form-label mb-1">Joint</label><br>
                                            <input type="checkbox" class="form-check-input" id="joint" name="joint" {{  ($pre_member->joint == 1 ? ' checked' : '') }} >
                                        </div>
                                        {{-- <div class="col-lg-2">
                                            <label for="single" class="form-label mb-1">Single</label><br>
                                            <input type="checkbox" class="form-check-input" id="single" name="single" {{  ($pre_member->single == 1 ? ' checked' : '') }} >
                                        </div> --}}
                                        <div class="col-lg-2 text-center">
                                            <label for="juridical" class="form-label mb-1">Juridical</label><br>
                                            <input type="checkbox" class="form-check-input" id="juridical" name="juridical" {{  ($pre_member->juridical == 1 ? ' checked' : '') }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                                    <input type="date" class="form-control" id="date_of_birth" value="{{ $pre_member->date_of_birth }}" name="date_of_birth">
                                </div>
                                <div class="mb-2">
                                    <label for="contact_no" class="form-label mb-1">Contact No. </label>
                                    <input type="number" class="form-control" id="contact_no" value="{{ $pre_member->contact_no }}" name="contact_no">
                                </div>
                                <div class="mb-2">
                                    <label for="place_conducted" class="form-label mb-1">Place Conducted *</label>
                                    {{-- <input type="text" class="form-control" id="place_conducted" value="{{ $pre_member->place_conducted }}" name="place_conducted"> --}}
                                    <select id="place_conducted" class="form-control" name="place_conducted" required>
                                        <option value="">Choose...</option>                     
                                        <option value="LEYECO V Main Office" @selected($pre_member->place_conducted == 'LEYECO V Main Office' ) >LEYECO V Main Office</option>
                                        <option value="OSS Albuera" @selected($pre_member->place_conducted == 'OSS Albuera' ) >OSS Albuera</option>
                                        <option value="OSS Albuera Damulaan" @selected($pre_member->place_conducted == 'OSS Albuera Damulaan' ) >OSS Albuera Damulaan</option>
                                        <option value="OSS Merida" @selected($pre_member->place_conducted == 'OSS Merida' ) >OSS Merida</option>
                                        <option value="OSS Isabel" @selected($pre_member->place_conducted == 'OSS Isabel' ) >OSS Isabel</option>
                                        <option value="OSS Palompon" @selected($pre_member->place_conducted == 'OSS Palompon' ) >OSS Palompon</option>
                                        <option value="OSS Villaba" @selected($pre_member->place_conducted == 'OSS Villaba' ) >OSS Villaba</option>
                                        <option value="OSS Tabango" @selected($pre_member->place_conducted == 'OSS Tabango' ) >OSS Tabango</option>
                                        <option value="OSS San Isidro" @selected($pre_member->place_conducted == 'OSS San Isidro' ) >OSS San Isidro</option>
                                        <option value="OSS Leyte" @selected($pre_member->place_conducted == 'OSS Leyte' ) >OSS Leyte</option>
                                        <option value="OSS Calubian" @selected($pre_member->place_conducted == 'OSS Calubian' ) >OSS Calubian</option>
                                        <option value="OSS Kananga" @selected($pre_member->place_conducted == 'OSS Kananga' ) >OSS Kananga</option>
                                        <option value="OSS Matag-ob" @selected($pre_member->place_conducted == 'OSS Matag-ob' ) >OSS Matag-ob</option>
                                        <option value="SEP Cabaloan Libertad Kananga" @selected($pre_member->place_conducted == 'SEP Cabaloan Libertad Kananga' ) >SEP Cabaloan Libertad Kananga</option>
                                        <option value="SEP Proper San Isidro Kananga" @selected($pre_member->place_conducted == 'SEP Proper San Isidro Kananga' ) >SEP Proper San Isidro Kananga</option>
                                        <option value="OSS Merida Calunangan" @selected($pre_member->place_conducted == 'OSS Merida Calunangan' ) >OSS Merida Calunangan</option>
                                        <option value="OSS Merida Lundag" @selected($pre_member->place_conducted == 'OSS Merida Lundag' ) >OSS Merida Lundag</option>
                                        <option value="OSS Merida Poblacion" @selected($pre_member->place_conducted == 'OSS Merida Poblacion' ) >OSS Merida Poblacion</option>
                                        <option value="OSS Merida Libas" @selected($pre_member->place_conducted == 'OSS Merida Libas' ) >OSS Merida Libas</option>

                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="district" class="form-label mb-1">District *</label>
                                    <select id="district" class="form-control" name="district" required>
                                        @foreach ($districts as $district)                        
                                            <option value="{{ $district->id }}" id="{{ $district->id }}" @selected( $district->id == $pre_member->district->id) >{{$district->district_name}}</option>
                                            <!-- <option value="{{ $district->id }}" id="{{ $district->id }}" {{ $district->id == $pre_member->district->id ? 'selected' : '' }} >{{ $district->district_name }}</option> -->
                                        @endforeach 
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="municipality" class="form-label mb-1">Municipality *</label>
                                    <select id="municipality" class="form-control" name="municipality">
                                        <option value="{{ $pre_member->municipality->id }}" id="{{ $pre_member->municipality->id }}">{{$pre_member->municipality->municipality_name }}</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="barangay" class="form-label mb-1">Barangay</label>
                                    <select id="barangay" class="form-control" name="barangay">
                                        <option value="{{ $pre_member->barangay->id }}" id="{{ $pre_member->barangay->id }}">{{$pre_member->barangay->barangay_name }}</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="sitio" class="form-label mb-1">Sitio </label>
                                    <input type="text" class="form-control" id="sitio" value="{{ $pre_member->sitio }}" name="sitio">
                                </div>
                                <div class="mb-2">
                                    <label for="date_conducted" class="form-label mb-1">Date *</label>
                                    <input type="date" class="form-control" id="date_conducted" value="{{ date('Y-m-d', strtotime($pre_member->date_and_time_conducted)) }}" name="date_conducted" required>
                                </div>
                                <div class="mb-2">
                                    <label for="time_conducted" class="form-label mb-1">Time *</label>
                                    {{-- <input type="time" class="form-control" id="time_conducted" value="{{ date('Y-m-d', strtotime($pre_member->date_and_time_conducted)) }}" name="time_conducted" required> --}}
                                    <select id="time_conducted" class="form-control" name="time_conducted" required>
                                        <option value="">Choose...</option>                     
                                        <option value="10:00:00" @selected( date('H:i:s', strtotime($pre_member->date_and_time_conducted))  == '10:00:00' ) >10:00 AM</option>
                                        <option value="14:00:00" @selected( date('H:i:s', strtotime($pre_member->date_and_time_conducted))  == '14:00:00' ) >02:00 PM</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="pms_conductor" class="form-label mb-1">PMS Facilitator *</label>
                                    <select id="pms_conductor" class="form-control" name="pms_conductor" value="{{ $pre_member->pms_conductor }}" required>
                                        <option value="">Choose...</option>                     
                                        <option value="Mark Gregory C. Tugonon" @selected($pre_member->pms_conductor == 'Mark Gregory C. Tugonon' ) >Mark Gregory C. Tugonon</option>
                                        <option value="Yancy Luke O. Hermoso" @selected($pre_member->pms_conductor == 'Yancy Luke O. Hermoso' ) >Yancy Luke O. Hermoso</option>
                                        <option value="Gideon Tahum" @selected($pre_member->pms_conductor == 'Gideon Tahum' ) >Gideon Tahum</option>
                                        <option value="Raymund O. Aparijo " @selected($pre_member->pms_conductor == 'Raymund O. Aparijo' ) >Raymund O. Aparijo </option>
                                        <option value="Nestor A. Ranoco, Jr." @selected($pre_member->pms_conductor == 'Nestor A. Ranoco, Jr.' ) >Nestor A. Ranoco, Jr.</option>
                                        <option value="Kent Vincent D. Jugar" @selected($pre_member->pms_conductor == 'Kent Vincent D. Jugar' ) >Kent Vincent D. Jugar</option>
                                        <option value="Vinz Alvin C. Caberos" @selected($pre_member->pms_conductor == 'Vinz Alvin C. Caberos' ) >Vinz Alvin C. Caberos</option>
                                        <option value="Quenie Marie L. Sotabento" @selected($pre_member->pms_conductor == 'Quenie Marie L. Sotabento' ) >Quenie Marie L. Sotabento</option>
                                        <option value="Irish Kathleen Y. Fantonial" @selected($pre_member->pms_conductor == 'Irish Kathleen Y. Fantonial' ) >Irish Kathleen Y. Fantonial</option>
                                        <option value="Paul Jason C. Dagamac" @selected($pre_member->pms_conductor == 'Paul Jason C. Dagamac') >Paul Jason C. Dagamac</option>
                                        <option value="Nino Rey C. Poniente" @selected($pre_member->pms_conductor == 'Nino Rey C. Poniente') >Nino Rey C. Poniente</option>
                                        <option value="Elma L. Gasatan" @selected($pre_member->pms_conductor == 'Elma L. Gasatan') >Elma L. Gasatan</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="col-lg-12 pt-2">
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
                        .id + '" id="'+ value.id +'">' + value.municipality_name + '</option>');
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
                        .id + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
              });
          }
      });
  });

  $("#juridical").click(function() {
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
    $('#juridical').prop('checked', false)
    }
});

});
</script>
@endsection