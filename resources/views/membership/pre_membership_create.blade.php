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
                            <a class="btn btn-danger btn-md text-end" href="{{ route('pre_membership_index') }}">
                                <i class="fa fa-cancel"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action = "{{ route('preMembershipStore') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="first_name" class="form-label mb-1">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="mb-2">
                                    <label for="middle_name" class="form-label mb-1">Middle Name *</label>
                                    <input type="text" class="form-control" id="middle_name" name="middle_name">
                                </div>
                                <div class="mb-2">
                                    <label for="last_name" class="form-label mb-1">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                                <div class="mb-2">
                                    <div class="row my-2">
                                        <div class="col-lg-8">
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
                                        <div class="col-lg-2 text-center">
                                            <label for="juridical" class="form-label mb-1">Juridical</label><br>
                                            <input type="checkbox" class="form-check-input" id="juridical" name="juridical">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label for="date_of_birth" class="form-label mb-1">Date of Birth *</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                                </div>
                                <div class="mb-2">
                                    <label for="contact_no" class="form-label mb-1">Contact No. </label>
                                    <input type="number" class="form-control" id="contact_no" placeholder="Ex: 09951128134" name="contact_no">
                                </div>
                                <div class="mb-2">
                                    <label for="place_conducted" class="form-label mb-1">Place Conducted *</label>
                                    {{-- <input type="text" class="form-control" id="place_conducted" name="place_conducted"> --}}
                                    <select id="place_conducted" class="form-control" name="place_conducted" required>
                                        <option value="">Choose...</option>                     
                                        <option value="LEYECO V Main Office" >LEYECO V Main Office</option>
                                        <option value="OSS Albuera" >OSS Albuera</option>
                                        <option value="OSS Albuera Damulaan" >OSS Albuera Damulaan</option>
                                        <option value="OSS Merida" >OSS Merida</option>
                                        <option value="OSS Isabel" >OSS Isabel</option>
                                        <option value="OSS Palompon" >OSS Palompon</option>
                                        <option value="OSS Villaba" >OSS Villaba</option>
                                        <option value="OSS Tabango" >OSS Tabango</option>
                                        <option value="OSS San Isidro" >OSS San Isidro</option>
                                        <option value="OSS Leyte" >OSS Leyte</option>
                                        <option value="OSS Calubian" >OSS Calubian</option>
                                        <option value="OSS Kananga" >OSS Kananga</option>
                                        <option value="OSS Matag-ob" >OSS Matag-ob</option>
                                        <option value="SEP Cabaloan Libertad Kananga" >SEP Cabaloan Libertad Kananga</option>
                                        <option value="SEP Proper San Isidro Kananga" >SEP Proper San Isidro Kananga</option>
                                        <option value="OSS Merida Calunangan">OSS Merida Calunangan</option>
                                        <option value="OSS Merida Lundag">OSS Merida Lundag</option>
                                        <option value="OSS Merida Poblacion">OSS Merida Poblacion</option>
                                        <option value="OSS Merida Libas">OSS Merida Libas</option>
                                        <option value="OSS Calubian Don Luis">OSS Calubian Don Luis</option>
                                        <option value="OSS Calubian Nipa">OSS Calubian Nipa</option>
                                        <option value="OSS Calubian Herrera">OSS Calubian Herrera</option>
                                        <option value="OSS Calubian Ul-og">OSS Calubian Ul-og</option>
                                        <option value="OSS Calubian F.E. Marcos">OSS Calubian F.E. Marcos</option>
					                    <option value="SEP Mahayag Ormoc">SEP Mahayag Ormoc</option>
                                        <option value="SEP Mahayahay II San Isidro">SEP Mahayahay II San Isidro</option>
                                        <option value="SEP San Isidro Kananga">SEP San Isidro Kananga</option>
                                        <option value="SEP San Andres San Isidro Kananga">SEP San Andres San Isidro Kananga</option>
                                        <option value="SEP Dapanas San Isidro Kananga">SEP Dapanas San Isidro Kananga</option>
                                        <option value="OSS Merida Puertobello">OSS Merida Puertobello</option>
                                        <option value="OSS Albuera Mahayahay">OSS Albuera Mahayahay</option>

                                        <option value="OSS Ormoc Lao">OSS Ormoc Lao</option>
                                        <option value="OSS Ormoc Libertad">OSS Ormoc Libertad</option>
                                        <option value="OSS Ormoc R.M. Tan">OSS Ormoc R.M. Tan</option>
                                        <option value="OSS Ormoc Manlilinao">OSS Ormoc Manlilinao</option>
                                        <option value="OSS Ormoc Mabato">OSS Ormoc Mabato</option>
                                        <option value="OSS Ormoc Valencia">OSS Ormoc Valencia</option>
                                        <option value="OSS Ormoc Guintigui-an">OSS Ormoc Guintigui-an</option>
                                        <option value="OSS Ormoc Matica-a">OSS Ormoc Matica-a</option>
                                        <option value="OSS Ormoc San Jose">OSS Ormoc San Jose</option>
                                        <option value="OSS Ormoc Curva">OSS Ormoc Curva</option>
                                        <option value="OSS Ormoc Sumangga">OSS Ormoc Sumangga</option>
                                        <option value="OSS Ormoc San Juan">OSS Ormoc San Juan</option>
                                        <option value="OSS Ormoc Margen">OSS Ormoc Margen</option>
                                        <option value="SEP Ormoc Green Valley">SEP Ormoc Green Valley</option>
                                        <option value="SEP Isabel Honan">SEP Isabel Honan</option>

                                        <option value="OSS Palompon Rizal">OSS Palompon Rizal</option>
                                        <option value="OSS Ormoc Green Valley">OSS Ormoc Green Valley</option>
                                        <option value="SEP Tabango Tagaytay">SEP Tabango Tagaytay</option>
                                        <option value="SEP Leyte Consuegra">SEP Leyte Consuegra</option>
                                        <option value="SEP San Isidro Busay">SEP San Isidro Busay</option>
                                        <option value="SEP Tabango Gimarco">SEP Tabango Gimarco</option>
                                        <option value="SEP Tabango Manlawa-an">SEP Tabango Manlawa-an</option>
                                        <option value="SEP Calubian Matagok">SEP Calubian Matagok</option>
                                        <option value="SEP Calubian Enage">SEP Calubian Enage</option>
                                        <option value="SEP Leyte Calaguise">SEP Leyte Calaguise</option>
                                        <option value="SEP Villaba Jalas">SEP Villaba Jalas</option>
                                        <option value="SEP Palompon Tinubdan">SEP Palompon Tinubdan</option>
                                        <option value="SEP Merida Benabaye">SEP Merida Benabaye</option>
                                        <option value="SEP Matag-ob Bulak">SEP Matag-ob Bulak</option>
                                        <option value="SEP Calubian Igang">SEP Calubian Igang</option>
                                        <option value="SEP Tabango Campokpok">SEP Tabango Campokpok</option>
                                        <option value="SEP Leyte Poblacion">SEP Leyte Poblacion</option>
                                        <option value="OSS Ormoc Esperanza">OSS Ormoc Esperanza</option>
                                        <option value="SEP Merida Canbantug">SEP Merida Canbantug</option>
                                        <option value="SEP Ormoc Magaswe">SEP Ormoc Magaswe</option>
                                        <option value="SEP Leyte Burabod">SEP Leyte Burabod</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-2">
                                    <label for="district" class="form-label mb-1">District *</label>
                                    <select id="district" class="form-control" name="district" required>
                                        <option value="">Choose...</option>
                                        @foreach ($districts as $district)                        
                                            <option value="{{ $district->id }}" id="{{ $district->id }}">{{$district->district_name}}</option>
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
                                    <label for="date_conducted" class="form-label mb-1">Date *</label>
                                    <input type="date" class="form-control" id="date_conducted" name="date_conducted" required>
                                </div>
                                <div class="mb-2">
                                    <label for="time_conducted" class="form-label mb-1">Time *</label>
                                    <select id="time_conducted" class="form-control" name="time_conducted" required>
                                        <option value="">Choose...</option>                     
                                        <option value="10:00:00" >10:00 AM</option>
                                        <option value="14:00:00" >02:00 PM</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label for="pms_conductor" class="form-label mb-1">PMS Facilitator *</label>
                                    <select id="pms_conductor" class="form-control" name="pms_conductor" required>
                                        <option value="">Choose...</option>                     
                                        <option value="Mark Gregory C. Tugonon" >Mark Gregory C. Tugonon</option>
                                        <option value="Yancy Luke O. Hermoso" >Yancy Luke O. Hermoso</option>
                                        <option value="Gideon Tahum" >Gideon Tahum</option>
                                        <option value="Raymund O. Aparijo " >Raymund O. Aparijo </option>
                                        <option value="Nestor A. Ranoco, Jr." >Nestor A. Ranoco, Jr.</option>
                                        <option value="Kent Vincent D. Jugar" >Kent Vincent D. Jugar</option>
                                        <option value="Vinz Alvin C. Caberos" >Vinz Alvin C. Caberos</option>
                                        <option value="Quenie Marie L. Sotabento" >Quenie Marie L. Sotabento</option>
                                        <option value="Irish Kathleen Y. Fantonial" >Irish Kathleen Y. Fantonial</option>
                                        <option value="Paul Jason C. Dagamac" >Paul Jason C. Dagamac</option>
                                        <option value="Nino Rey C. Poniente" >Nino Rey C. Poniente</option>
                                        <option value="Elma L. Gasatan" >Elma L. Gasatan</option>
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