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