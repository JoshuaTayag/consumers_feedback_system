@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-lg-6">
                    <span class="mb-0 align-middle fs-3">Material Requisition Form <span class="badge rounded-pill bg-success text-white fs-6">Liquidated</span> </span>
                </div>
                <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('material-requisition-form.index') }}"> Back </a>
              </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4 d-flex">
                  <div class="card w-100" style="height:auto;">
                    <div class="card-header bg-info">
                      Project Name
                    </div>
                    <div class="card-body text-left">
                      <span class="fs-5">{{$mrf->project_name}}</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="card">
                    <div class="card-header bg-info">
                      Requested By
                    </div>
                    <div class="card-body text-left">
                      <span id="requested_by" class="fs-5">{{ $mrf->requested_name }}</span><br>
                      <span id="requested_by" class="fs-5 fw-bold text-danger">{{ date('F d, Y h:i A', strtotime($mrf->requested_by)) }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4">
                  <div class="card">
                    <div class="card-header bg-info">
                      Approved By
                    </div>
                    <div class="card-body text-left">
                      <span id="requested_by" class="fs-5">{{ $mrf->approved_name }}</span><br>
                      <span id="requested_by" class="fs-5 fw-bold text-danger">{{ date('F d, Y h:i A', strtotime($mrf->approved_by)) }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <hr clas="p-5">

              <div class="row">
                <div class="col-lg-4">
                  <div class="card">
                    <div class="card-header bg-info">
                      Address
                    </div>
                    <div class="card-body fs-5 text-left">
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          District :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control mb-2" value="{{$mrf->district->district_name}}" readonly>
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Municipality :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control mb-2" value="{{$mrf->municipality->municipality_name}}" readonly>
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Barangay :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control mb-2" value="{{$mrf->barangay->barangay_name}}" readonly>
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Sitio :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control mb-2" value="{{$mrf->sitio}}" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-8 d-flex">
                  <div class="card w-100">
                    <div class="card-header bg-info">
                      Project Remarks
                    </div>
                    <div class="card-body fs-5">
                      <textarea class="form-control" readonly >{{$mrf->remarks}}</textarea>
                    </div>
                  </div>
                </div>
              </div>

              <hr>

              <div class="row">
                <div class="col-lg-7">
                  <div class="card w-100">
                    <div class="card-header bg-info">
                      Liquidation Details
                    </div>
                    <div class="card-body fs-5">
                      <div class="row">
                        @foreach ($mrf_liquidation as $index => $detail) 
                          <div class="col-lg-2">
                            {{$detail->type}} : 
                          </div>
                          <div class="col-lg-4">
                            <input type="text" class="form-control mb-2" value="{{$detail->type_number}}" readonly>
                          </div>
                        @endforeach
                      </div>

                      <hr>

                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Date Acted :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" value="{{ date('F d, Y', strtotime($mrf_liquidation[0]->date_acted)) }}" readonly>
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Date Finished :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" value="{{ date('F d, Y', strtotime($mrf_liquidation[0]->date_finished)) }}" readonly>
                        </div>
                      </div>

                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Linemans :
                        </div>
                        <div class="col-lg-8">
                          <input type="text" class="form-control" value="{{$mrf_liquidation->last()->lineman}}" readonly>
                        </div>
                      </div>
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          Remarks :
                        </div>
                        <div class="col-lg-8">
                          <textarea class="form-control" readonly >{{ $mrf_liquidation->last()->remarks}} </textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-5">
                  <div class="card w-100">
                    <div class="card-header bg-info">
                      Image
                    </div>
                    <div class="card-body fs-5">
                      <img src="{{ asset($mrf_liquidation[0]->image_path)}}" class="img-fluid pt-2" style="max-height: 40vh;" alt="...">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script>
</script>
@endsection