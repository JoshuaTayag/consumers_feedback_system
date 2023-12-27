@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Lifeline </span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-secondary btn-md text-end" href="{{ route('lifeline.create') }}">
                      <i class="fa fa-save"></i> Insert Record
                    </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row pb-2">
                <div class="col-lg-4">
                    <input type="text" placeholder="Search by Control No" id="control_number" name="control_number" class="form-control">
                </div>
                <div class="col-lg-4">
                    <input type="text" placeholder="Search by First Name" id="first_name" name="first_name" class="form-control">
                </div>
                <div class="col-lg-4">
                    <input type="text" placeholder="Search by Last Name" id="last_name" name="last_name" class="form-control">
                </div>
              </div>
              <table class="table table-striped table-bordered border-primary">
                <thead>
                  <tr>
                    <th>Control No</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Date Of Birth</th>
                    <th>Account No</th>
                    <th>Applicant Type</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="show_data">
                  @include('lifeline.search')
                </tbody>
               </table>
               <div id="pagination">{{ $lifeline_datas->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('style')
<style>
  .badge{
    line-height: 0.3;
  }
</style>
@endsection