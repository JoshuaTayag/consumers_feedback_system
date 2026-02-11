@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">kWh Meter Request</span>
              </div>
          </div>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-lg-2">
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
              
              <div class="col-lg-5">
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
              
              <div class="col-lg-1">
                <div class="mb-2">
                  <label for="quantity" class="form-label mb-1">Quantity</label>
                    <input type="number" class="form-control" id="quantity" value="{{ $kwh_meter_request->quantity }}" max="{{ env('TSD_MAX_METER_REQUEST', 10) }}" name="quantity" old="quantity" disabled>
                </div>
              </div>

              <div class="col-lg-2">
                <div class="mb-2">
                  <label for="date_requested" class="form-label mb-1">Date Requested</label>
                  <input type="date" class="form-control" id="date_requested" value="{{ $kwh_meter_request->created_at->format('Y-m-d') }}" name="date_requested" old="date_requested" disabled>
                </div>
              </div>

              <div class="col-lg-2">
                <div class="mb-2">
                  @if ($kwh_meter_request->approved_at)
                  <label for="approved_by" class="form-label mb-1">Approved By: <span class="text-success fw-bold">{{ $kwh_meter_request->approved_at ? $kwh_meter_request->approved_at->format('m/d/Y') : 'N/A' }}</span></label>
                  @elseif ($kwh_meter_request->disapproved_at)
                  <label for="approved_by" class="form-label mb-1">Disapproved By: <span class="text-danger fw-bold">{{ $kwh_meter_request->disapproved_at ? $kwh_meter_request->disapproved_at->format('m/d/Y') : 'N/A' }}</span></label>
                  @else
                  <label for="approved_by" class="form-label mb-1">Approved By:</label>
                  @endif
                  
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

              <!-- Liquidation Status Card -->
              <div class="col-lg-12 my-3">
                <h5 class="text-primary mb-2 fw-bold">
                    <i class="fas fa-check-circle me-2"></i>Liquidation Status
                </h5>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded-circle py-1 px-2 me-3">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-muted">Checked By (Warehouse)</h6>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">
                                {{ $kwh_meter_request->checkedBy ? $kwh_meter_request->checkedBy->employee->full_name : 'Not yet checked' }}
                            </h5>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $kwh_meter_request->liquidated_at ? $kwh_meter_request->liquidated_at->format('M d, Y g:i A') : 'Pending' }}
                            </small>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded-circle py-1 px-2 me-3">
                                    <i class="fas fa-warehouse"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-muted">Audited By (IAD)</h6>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">
                                {{ $kwh_meter_request->auditedBy ? $kwh_meter_request->auditedBy->employee->full_name : 'Not yet audited' }}
                            </h5>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $kwh_meter_request->audited_at ? $kwh_meter_request->audited_at->format('M d, Y g:i A') : 'Pending' }}
                            </small>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-primary text-white rounded-circle py-1 px-2 me-3">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-muted">Approved By (Manager)</h6>
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark mb-1">
                                {{ $kwh_meter_request->liquidatedBy ? $kwh_meter_request->liquidatedBy->employee->full_name : 'Not yet Approved' }}
                            </h5>
                            <small class="text-muted">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $kwh_meter_request->liquidated_at ? $kwh_meter_request->liquidated_at->format('M d, Y g:i A') : 'Pending' }}
                            </small>
                        </div>
                      </div>

                      <div class="col-lg-3 col-md-12">
                          <div class="border rounded-3 p-3 h-100 bg-light">
                              <div class="d-flex align-items-center mb-2">
                                  <div class="bg-primary text-white rounded-circle py-1 px-2 me-3">
                                      <i class="fas fa-info-circle"></i>
                                  </div>
                                  <div>
                                      <h6 class="mb-0 text-muted">Current Status</h6>
                                  </div>
                              </div>
                              <h5 class="mb-2">
                                  <span class="badge fs-6 px-3 py-2 rounded-pill {{ $kwh_meter_request->is_liquidated ? 'bg-success' : ($kwh_meter_request->approved_at ? 'bg-info' : ($kwh_meter_request->disapproved_at ? 'bg-danger' : 'bg-warning text-dark')) }}">
                                    {{ $kwh_meter_request->is_liquidated ? 'Liquidated' : ($kwh_meter_request->approved_at ? 'Approved' : ($kwh_meter_request->disapproved_at ? 'Disapproved' : 'Pending')) }}
                                  </span>
                              </h5>
                              @if($kwh_meter_request->liquidation_remarks)
                              <small class="text-muted">
                                  <i class="fas fa-sticky-note me-1"></i>
                                  {{ $kwh_meter_request->liquidation_remarks }}
                              </small>
                              @endif
                          </div>
                      </div>
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
                          <td><span class="badge {{ $serialNumber->status == 0 ? 'bg-warning' : 'bg-success' }}">{{ $serialNumber->status == 0 &&  !$serialNumber->change_meter_request_id ? 'Unassigned' : ($serialNumber->changeMeterRequest->status == 2 ? 'Installed' : 'Unacted') }}</span></td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="col-lg-12">
                <!-- Audit Trail Section -->
                <div class="my-3">
                  <h5 class="text-primary mb-2 fw-bold">
                      <i class="fas fa-history me-2"></i>Audit Trail
                  </h5>
                  <div class="card border-0 shadow-sm">
                    <div class="card-body p-0">
                      <div class="table-responsive">
                        <table class="table table-hover mb-0">
                          <thead class="table-dark">
                            <tr>
                              <th class="border-0">
                                  <i class="fas fa-hashtag me-1"></i>Step
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-exchange-alt me-1"></i>Transaction
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-user me-1"></i>From
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-user-check me-1"></i>To
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-flag me-1"></i>Status
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-calendar me-1"></i>Date
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-wrench me-1"></i>Action
                              </th>
                              <th class="border-0">
                                  <i class="fas fa-comment me-1"></i>Notes
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            @forelse($audit_trail as $audit)
                            <tr>
                              <td class="fw-bold">{{ $audit->approval_step }}</td>
                              <td class="fw-bold">{{ $audit->transaction }}</td>
                              <td>
                                <div class="d-flex align-items-center">
                                  <div class="bg-primary text-white rounded-circle p-1 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user fa-sm"></i>
                                  </div>
                                  <div>
                                    <div class="fw-bold">{{ $audit->senderUser->name ?? 'N/A' }}</div>
                                    <small class="text-muted">Sender</small>
                                  </div>
                                </div>
                              </td>
                              <td>
                                <div class="d-flex align-items-center">
                                  <div class="bg-success text-white rounded-circle p-1 me-2" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user-check fa-sm"></i>
                                  </div>
                                  <div>
                                    <div class="fw-bold">{{ $audit->recipientUser->name ?? 'N/A' }}</div>
                                    <small class="text-muted">Recipient</small>
                                  </div>
                                </div>
                              </td>
                              <td>
                                @php
                                    $statusClass = 'bg-secondary';
                                    $statusIcon = 'fas fa-question';
                                    $statusText = 'Unknown';
                                    
                                    switch($audit->status) {
                                        case 0:
                                            $statusClass = 'bg-warning text-dark';
                                            $statusIcon = 'fas fa-clock';
                                            $statusText = 'Pending';
                                            break;
                                        case 1:
                                            $statusClass = 'bg-success';
                                            $statusIcon = 'fas fa-check';
                                            $statusText = 'Approved';
                                            break;
                                        case 2:
                                            $statusClass = 'bg-danger';
                                            $statusIcon = 'fas fa-times';
                                            $statusText = 'Disapproved';
                                            break;
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} px-3 py-2">
                                    <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                </span>
                              </td>
                              <td>
                                <div class="text-muted">
                                  <i class="fas fa-calendar-alt me-1"></i>
                                  {{ $audit->created_at->format('M d, Y') }}
                                  <br>
                                  <small><i class="fas fa-clock me-1"></i>{{ $audit->created_at->format('g:i A') }}</small>
                                </div>
                              </td>
                              <td>
                                @if($audit->status == 0)
                                  <span class="text-muted">
                                    <i class="fas fa-hourglass-half me-1"></i>Awaiting Action
                                  </span>
                                @elseif($audit->status == 1)
                                  <span class="text-success">
                                    <i class="fas fa-thumbs-up me-1"></i>Approved Request
                                  </span>
                                @else
                                  <span class="text-danger">
                                    <i class="fas fa-thumbs-down me-1"></i>Disapproved Request
                                  </span>
                                @endif
                              </td>
                              <td class="fw-bold">
                                <textarea name="remarks" id="remarks" class="form-control" readonly>{{ $audit->remarks }}</textarea></td>
                            </tr>
                            @empty
                            <tr>
                              <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                  <i class="fas fa-inbox fa-2x mb-2"></i>
                                  <p class="mb-0">No audit trail records found</p>
                                </div>
                              </td>
                            </tr>
                            @endforelse
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
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
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection