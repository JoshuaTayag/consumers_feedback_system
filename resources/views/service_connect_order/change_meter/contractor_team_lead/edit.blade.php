@extends('layouts.app')


@section('content')

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <span class="mb-0 align-middle fs-3">Edit Record</span>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form action="{{ route('change-meter-lead-contractor.update', $lead_contractor->id) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                  <div class="form-group">
                    <strong>Full Name:</strong>
                    <input type="text" name="contractor_team_leader_full_name" placeholder="Full Name"
                      value="{{ $lead_contractor->contractor_team_leader_full_name }}" class="form-control" required>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                  <div class="form-group">
                    <strong>Area:</strong>
                    <input type="text" name="area" placeholder="Area"
                      value="{{ $lead_contractor->area }}" class="form-control" required>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                  <div class="form-group">
                    <strong>Municipality:</strong>
                    <input type="text" name="municipality" placeholder="Municipality" value="{{ $lead_contractor->municipality }}"
                      class="form-control" required>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                  <a class="btn btn-warning" href="{{ route('change-meter-lead-contractor.index') }}"><i
                      class="fa fa-times"></i> Cancel </a>
                  <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection