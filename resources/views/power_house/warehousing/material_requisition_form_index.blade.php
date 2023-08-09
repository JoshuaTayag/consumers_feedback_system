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
                  <th>Code</th>
                  <th>Notes</th>
                  <th>Items</th>
                  <th>Status</th>
                  <th width="280px">Action</th>
                </tr>
               </table>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection