@foreach ($scos as $key => $sco)
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header p-1 bg-{{ $sco->Dispatch2 ? 'success' : 'danger' }}"></div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col d-flex align-items-center">
              @if($sco->Acted == 0 || $sco->Dispatch2 == 'NOT COMPLETED')
                  <a class="btn btn-sm btn-secondary rounded-pill me-2" href="{{ route('editCM',$sco->application_id) }}">
                      <i class="bi bi-gear"></i> Update
                  </a>
                  <!-- <a class="btn btn-sm btn-secondary rounded-pill" href="{{ route('editCM',$sco->application_id) }}">
                      <i class="bi bi-gear"></i> Meter Posting
                  </a> -->
                  <button type="button" class="btn btn-sm btn-secondary rounded-pill me-2" data-bs-toggle="modal" data-bs-target="#exampleModal" data-name="{{$sco->Lastname.', '.$sco->Firstname}}" data-sco="{{$sco->SCONo}}" data-process-date="{{ date('F d, Y', strtotime($sco->ProcessDate)) }}">
                    Meter Posting
                  </button>
              @endif
              <a href="{{route('printChangeMeterRequest',$sco->application_id)}}" target="_blank" class="btn btn-sm btn-success"><i class="fa fa-print"></i></a>
          </div>
          <div class="col-lg-3 d-flex align-items-center">
              <div class="mx-end ms-auto"> <!-- Add mx-auto to horizontally center the content -->
                  <p class="badge rounded-pill bg-{{ $sco->Dispatch2 ? 'success' : 'danger' }} p-2 mb-0">{{ $sco->Dispatch2 ? 'Acted' : 'Not Acted' }}</p>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col ">SCO No. :</div>
          <div class="col fw-bold">{{$sco->SCONo}}</div>
        </div>
        <div class="row">
          <div class="col ">Name:</div>
          <div class="col ">{{$sco->Lastname.', '.$sco->Firstname}}</div>
        </div>
        <div class="row">
          <div class="col ">Process Date:</div>
          <div class="col ">{{ date('F d, Y', strtotime($sco->ProcessDate)) }}</div>
        </div>
        <div class="row">
          <div class="col ">Area:</div>
          <div class="col ">{{$sco->Area}}</div>
        </div>
        <div class="row">
          <div class="col ">Address:</div>
          <div class="col ">{{$sco->Sitio.', '.$sco->Brgy.', '. $sco->Municipality}}</div>
        </div>
        <div class="row">
          <div class="col ">Consumer Type:</div>
          <div class="col ">{{$sco->ConsumerType}}</div>
        </div>
        <div class="row">
          <div class="col ">Application Status:</div>
          <div class="col "><p class="badge rounded-pill bg-{{ $sco->Dispatch2 == 'INSTALLED' && $sco->MeterNo ? 'success' : ($sco->Dispatch2 == 'REJECTED' && !$sco->MeterNo ? 'danger' : 'warning')}} p-2 fs-6" >{{$sco->Dispatch2}}</p></div>
        </div>
        <div class="row">
          <div class="col ">Meter No.:</div>
          <div class="col ">{{$sco->MeterNo}}</div>
        </div>
        <div class="row">
          <div class="col ">Date Installed:</div>
          <div class="col ">{{ $sco->{'Date Installed'} ? date('F d, Y', strtotime($sco->{'Date Installed'})) : 'NONE' }}</div>
        </div>
        <div class="row">
          <div class="col-lg-6">Remarks:</div>
          <div class="col-lg-6">{{$sco->Remarks}}</div>
        </div>
      </div>
    </div>
  </div>
@endforeach

<!-- @section('script')
  <script>
    const application_status = document.getElementById('status');
      const meter_no = document.getElementById('meter_no');
      const date_installed = document.getElementById('date_installed');
      const seal_no = document.getElementById('seal_no');
      const serial_no = document.getElementById('serial_no');
      const erc_seal = document.getElementById('erc_seal');
      const feeder = document.getElementById('feeder');
      const last_reading = document.getElementById('last_reading');
      const reading_initial = document.getElementById('reading_initial');
      const crew = document.getElementById('crew');
      const time = document.getElementById('time');

      // Add event listener to dropdown
      application_status.addEventListener('change', function() {
          // Toggle visibility of text field based on selected option
          if (application_status.value == 'INSTALLED') {
            // console.log(meter_no)
            meter_no.setAttribute('required', 'required');
            date_installed.setAttribute('required', 'required');
            seal_no.setAttribute('required', 'required');
            serial_no.setAttribute('required', 'required');
            erc_seal.setAttribute('required', 'required');
            feeder.setAttribute('required', 'required');
            last_reading.setAttribute('required', 'required');
            reading_initial.setAttribute('required', 'required');
            crew.setAttribute('required', 'required');
            time.setAttribute('required', 'required');
          } else {
            meter_no.removeAttribute('required');
            date_installed.removeAttribute('required');
            seal_no.removeAttribute('required');
            serial_no.removeAttribute('required');
            erc_seal.removeAttribute('required');
            feeder.removeAttribute('required');
            last_reading.removeAttribute('required');
            reading_initial.removeAttribute('required');
            crew.removeAttribute('required');
            time.removeAttribute('required');

            meter_no.value = '';
            date_installed.value = '';
            seal_no.value = '';
            serial_no.value = '';
            erc_seal.value = '';
            feeder.value = '';
            last_reading.value = '';
            reading_initial.value = '';
            crew.value = '';
            time.value = '';
          }
      });

      $('#meter_no').blur(function(){
      var error_email = '';
      var meter_no = $(this).val();
      if (meter_no) {
        $.ajax({
          url:"{{ route('validateMeterNo') }}",
          method:"POST",
          data:{
            meter_no: meter_no, 
            _token: '{{csrf_token()}}'
          },
          success:function(result)
          {
            if(result[0] == 'unique') {
              $('#error_email').html('<label class="text-success">Meter No Available</label>');
              $('#meter_no').removeClass('is-invalid');
              $('#meter_no').addClass('is-valid');
              $('#submit_meter_posting').attr('disabled', false);
            }
            else {
              $('#error_email').html('<label class="text-danger">Meter No not Available! Pls refer to SCO NO '+ result[1] +'</label>');
              $('#meter_no').removeClass('is-valid');
              $('#meter_no').addClass('is-invalid');
              $('#submit_meter_posting').attr('disabled', 'disabled');
            }
          }
        })
      }
      else{
        $('#meter_no').removeClass('is-invalid');
        $('#meter_no').removeClass('is-valid');
        $('#error_email').html('');
        $('#submit_meter_posting').attr('disabled', false);
      }
    });
  </script>
@endsection -->