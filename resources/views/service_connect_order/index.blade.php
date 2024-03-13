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
                      <a class="btn btn-success" href="{{ route('service-connect-order.create') }}"> Create New SCO </a>
                    @endcan
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
              @foreach ($scos as $key => $sco)
                <div class="col-lg-4 mb-4">
                  <div class="card">
                    <div class="card-header p-1 bg-{{ strpos($sco->SCONo, 'h') ? 'primary' :  'danger'  }}"></div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col fs-5">SCO No. :</div>
                        <div class="col fs-5">{{$sco->SCONo}}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-5">Complainant Name:</div>
                        <div class="col fs-5">{{$sco->SCONo}}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-5">Date of Complaint:</div>
                        <div class="col fs-5">{{$sco->SCONo}}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-5">Nature Of Complaint:</div>
                        <div class="col fs-5">{{$sco->SCONo}}</div>
                      </div>
                      <div class="row">
                        <div class="col fs-5">Act of misconduct:</div>
                        <div class="col fs-5">
                        </div>
                      </div>
                      <div class="row">
                        <div class="col fs-5">Remarks:</div>
                        <div class="col fs-5">{{$sco->SCONo}}</div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              </div>
              <table class="table table-bordered">
                <tr>
                  <th>SCO No</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Spouse</th>
                  <th>Address</th>
                  <th>Meter No.</th>
                  <th>Date Installed</th>
                  <th width="280px">Action</th>
                </tr>
                  
              </table>
              <div id="pagination">{{ $scos->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection