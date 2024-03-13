@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Service Connect Order</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    @can('service-connect-order-create')
                      <a class="btn btn-success" href="{{ route('createCM') }}"> Create New SCO </a>
                    @endcan
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
              @foreach ($scos as $key => $sco)
                <div class="col-lg-4 mb-4">
                  <div class="card">
                    <div class="card-header p-1 bg-{{ $sco->{'Date Installed'} && $sco->MeterNo ? 'success' : 'danger' }}"></div>
                    <div class="card-body">
                      <div class="div text-end">
                        <p class="badge rounded-pill bg-{{ $sco->{'Date Installed'} && $sco->MeterNo ? 'success' : 'danger' }} p-2"  >{{ $sco->{'Date Installed'} && $sco->MeterNo ? 'Acted' : 'Not Acted' }}</p>
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
              </div>
              <div id="pagination">{{ $scos->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection