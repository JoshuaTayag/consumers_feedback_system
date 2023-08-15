@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Material Requisition Form</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success" href="{{ route('material-requisition-form.create') }}"> Create New Form </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>Project Name</th>
                  <th>Address</th>
                  <th>Items</th>
                  <th>Requested by</th>
                  <th>Status</th>
                  <th width="280px">Action</th>
                </tr>
                <tbody>
                  @foreach ($mrfs as $index => $mrf)                        
                    <tr class="text-center">
                        <th>{{ $mrf->project_name }}</th>
                        <th>{{ $mrf->district->district_name }}, {{ $mrf->barangay->barangay_name }}, {{ $mrf->municipality->municipality_name }}</th>
                        <th>{{ $mrf->items }}</th>
                        <th>{{ $mrf->requested_by }}</th>
                        <th class="badge rounded-pill bg-{{ $mrf->status == 0 ? 'primary' : ($mrf->status == 1 ? 'success' : 'danger') }}"  >{{ $mrf->application_status == 0 ? "Pending" : "Approved" }}</th>
                        
                    </tr>
                  @endforeach
                </tbody>
               </table>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection