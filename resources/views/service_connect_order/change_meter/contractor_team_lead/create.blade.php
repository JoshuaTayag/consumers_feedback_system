@extends('layouts.app')


@section('content')

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <span class="mb-0 align-middle fs-3">Create New Record</span>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form action="{{ route('change-meter-lead-contractor.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                    <strong>Full Name:</strong>
                    <input type="text" name="contractor_team_leader_full_name" placeholder="E.g. Juan Dela Cruz"
                      class="form-control" required>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                    <strong>Coverage Area:</strong>
                    <input type="text" name="area" placeholder="E.g. Area 1 and 4" class="form-control" required>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                    <strong>Municipality:</strong>
                    <input type="text" name="municipality" placeholder="E.g. Ormoc, Kananga" class="form-control" required>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-3 mb-2">
                  <div class="form-group">
                      <strong>Signature</strong>
                      <input type="file" name="signature" class="form-control" required>
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