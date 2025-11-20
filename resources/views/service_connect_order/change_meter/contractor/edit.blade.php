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
          <form action="{{ route('change-meter-contractor.update', $contractor->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                  <strong>First Name:</strong>
                  <input type="text" name="first_name" placeholder="First Name" value="{{ $contractor->first_name }}" class="form-control" required>
                </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                  <strong>Middle Name:</strong>
                  <input type="text" name="middle_name" placeholder="Middle Name" value="{{ $contractor->middle_name }}" class="form-control">
                </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                  <strong>Last Name:</strong>
                  <input type="text" name="last_name" placeholder="Last Name" value="{{ $contractor->last_name }}" class="form-control" required>
                </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                  <strong>Address:</strong>
                  <input type="text" name="address" placeholder="Address" value="{{ $contractor->address }}" class="form-control" required>
                </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                  <strong>Mobile No.:</strong>
                  <input type="text" name="mobile_number" placeholder="Mobile Number" value="{{ $contractor->mobile_number }}" class="form-control" required>
                </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 mb-2">
                <div class="form-group">
                  <strong>Account:</strong>
                  <select name="user_id" id="user_id" class="form-control">
                    <option value="">Select Account</option>
                    @foreach ($users as $id => $email)
                      <option value="{{ $id }}" {{ $contractor->user_id == $id ? 'selected' : '' }}>{{ $email }}</option>
                    @endforeach
                  </select>
                </div>
                </div>
              <div class="col-xs-12 col-sm-12 col-md-12 text-end">
                <a class="btn btn-warning" href="{{ route('change-meter-contractor.index') }}"><i class="fa fa-times"></i> Cancel </a>
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