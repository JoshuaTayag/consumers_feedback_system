@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Electricians Complaints</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-secondary btn-md text-end" href="{{ route('electricianComplaintCreate') }}">
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
                    <input type="text" placeholder="Search by Complainant" id="complaint" name="complaint" class="form-control">
                </div>
                <div class="col-lg-4">
                    <input type="text" placeholder="Search by Electrician" id="electrician" name="electrician" class="form-control">
                </div>
              </div>
              <table class="table table-striped table-bordered border-primary">
                <thead>
                  <tr class="text-center">
                    <th>Control No.</th>
                    <th>Date</th>
                    <th>Complainant</th>
                    <th>Electrician</th>
                    <th>Nature of Complaint</th>
                    <th>Act of Misconduct</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="show_data">
                  @include('electrician.complaint.search')
                </tbody>
               </table>
               <div id="pagination">{{ $data->links() }}</div>
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