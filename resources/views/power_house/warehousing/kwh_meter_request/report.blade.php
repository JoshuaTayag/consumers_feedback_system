@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-10">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-12">
                      <span class="mb-0 align-middle fs-3">Generate KWH Meter Request Liquidation Report</span>
                  </div>
              </div>
            </div>
            <form action="{{ route('KwhMeterPdfReport') }}" method="GET">
              <div class="row p-3">
                <div class="col-lg-3">
                  <label for="date_from">Date From:</label>
                  <input type="date" class="form-control" name="date_from" id="date_from" required>
                </div>
                <div class="col-lg-3">
                  <label for="date_to">Date To:</label>
                  <input type="date" class="form-control" name="date_to" id="date_to" required>
                </div>
                <div class="col-lg-3">
                  <label for="requesitioner">Requesitioner:</label>
                  <select class="form-control" name="requesitioner" id="requesitioner" required>
                    <option value="" disabled selected>Select Requesitioner</option>
                    @foreach($requesitioners as $user)
                      <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-3 text-start gap-2">
                  <label>&nbsp;</label><br>
                  <a class="btn btn-sm btn-warning" href="{{ route('kwh-meter-request.index') }}"> Back </a>
                  <button type="submit" class="btn btn-sm btn-info">GENERATE</button>
              </div>
            </form>
          </div>
      </div>
  </div>
</div>
@endsection