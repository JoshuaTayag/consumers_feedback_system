@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit kWh Meter Request</span>
              </div>
          </div>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="user_id" class="form-label mb-1">Requested By:</label>
                    <select class="form-select" id="user_id" name="user_id" disabled>
                      <option value="">Select User</option>
                      @foreach($users as $id => $user)
                          <option value="{{ $id }}" @selected($kwh_meter_request->user_id == $id)>{{ $user }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="meter_code_id" class="form-label mb-1">Meter Type</label>
                    <select class="form-select" id="meter_code_id" name="meter_code_id" disabled>
                      <option value="">Select Meter Type</option>
                      @foreach($meters_types as $meter_type)
                          <option value="{{ $meter_type->id }}" @selected($kwh_meter_request->meter_code_id == $meter_type->id)>{{ $meter_type->meter_description }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="quantity" class="form-label mb-1">Quantity</label>
                    <input type="number" class="form-control" id="quantity" value="{{ $kwh_meter_request->quantity }}" max="10" name="quantity" old="quantity" disabled>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="approved_by" class="form-label mb-1">Approved By: <span class="text-danger">{{ $kwh_meter_request->approved_at ?? 'N/A' }}</span></label>
                    <select class="form-select" id="approved_by" name="approved_by" disabled>
                      <option value="">Select User</option>
                      @foreach($users as $id => $user)
                          <option value="{{ $id }}" @selected($kwh_meter_request->approved_by == $id)>{{ $user }}</option>
                      @endforeach
                    </select>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="purpose" class="form-label mb-1">Purpose</label>
                    <textarea name="purpose" id="purpose" class="form-control" disabled>{{ $kwh_meter_request->purpose }}</textarea>
                </div>
              </div>
              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="serial_numbers" class="form-label mb-1 fw-bold">Meter Serial Numbers</label>
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Meter No.</th>
                        <th>Leyeco 5 Seal No.</th>
                        <th>Erc Seal No.</th>
                        <th>Control No</th>
                        <th>Meter Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($kwh_meter_request->kwhMeterRequestSerialNumbers as $serialNumber)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $serialNumber->meter->serial_number }}</td>
                          <td>{{ $serialNumber->meter->leyeco_seal_number }}</td>
                          <td>{{ $serialNumber->meter->erc_seal_number }}</td>
                          <td>{{ $serialNumber->changeMeterRequest ? $serialNumber->changeMeterRequest->control_no : 'N/A' }}</td>
                          <td><span class="badge {{ $serialNumber->status == 0 ? 'bg-warning' : 'bg-success' }}">{{ $serialNumber->status == 0 ? 'Unliquidated' : 'Liquidated' }}</span></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12 text-end pt-2 gap-2">
                <a type="button" class="btn btn-sm btn-warning" href="{{ route('kwh-meter-request.index') }}"><i class="fa fa-times"></i> Back</a>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection