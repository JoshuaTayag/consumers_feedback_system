@extends('layouts.app')


@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-lg-6">
                <span class="mb-0 align-middle fs-3">Record New Feeder</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-primary" href="{{ route('feeders.index') }}"> Back </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form action="{{ route('feeders.store') }}" method="POST">
            @csrf
            <div class="row g-2 align-items-end">
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>Area</strong>
                  <select name="area_id" class="form-control" required>
                    <option value="">-- Select Area --</option>
                    @foreach($areas as $area)
                      <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>Feeder</strong>
                  <input type="text" name="feeder_name" class="form-control" placeholder="Enter Feeder Name" required>
                </div>
              </div>
              <div class="col-12 col-md-12">
                <div class="form-group">
                  <strong>Coverage Area</strong>
                  <input type="text" name="coverage_name" class="form-control" placeholder="Enter Coverage Area" required>
                </div>
              </div>
              <div class="col-12 col-md-12 text-md-end">
                <div class="form-group mb-0">
                  <strong class="d-block">&nbsp;</strong>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
