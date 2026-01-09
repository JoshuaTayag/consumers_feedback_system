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
                  <div class="col-lg-6 text-end">
                    <a class="btn btn-success btn-sm" href="{{ route('generateKwhMeterReport') }}" target="_blank"> <i class="fa fa-print"></i> Generate Liquidation Report</a>
                    <a class="btn btn-success btn-sm" href="{{ route('kwh-meter-request.create') }}"> <i class="fa fa-plus"></i> Create New Request</a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th>Control No.</th>
                  <th>Requested By</th>
                  <th>Purpose</th>
                  <th>Meter Type</th>
                  <th>Qty Req</th>
                  <th>Qty Assigned</th>
                  <th>Date Requested</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                @foreach ($kwh_meter_requests as $key => $kwh_meter_request)
                 <tr>
                   <td>{{ $kwh_meter_request->control_no }}</td>
                   <td>{{ $kwh_meter_request->user->name }}</td>
                   <td>{{ $kwh_meter_request->purpose }}</td>
                   <td>{{ $kwh_meter_request->meterType->meter_code }}</td>
                   <td>{{ $kwh_meter_request->quantity }}</td>
                   <td>{{ $kwh_meter_request->kwhMeterRequestSerialNumbers->count() }}</td>
                   <td>{{ $kwh_meter_request->created_at->format('m/d/Y') }}</td>
                   <td><span class="badge p-2 {{ $kwh_meter_request->is_liquidated ? 'bg-success' : ($kwh_meter_request->approved_at ? 'bg-info' : ($kwh_meter_request->disapproved_at ? 'bg-danger' : 'bg-warning text-dark')) }}">{{ $kwh_meter_request->is_liquidated ? 'Liquidated' : ($kwh_meter_request->approved_at ? 'Approved' : ($kwh_meter_request->disapproved_at ? 'Disapproved' : 'Pending')) }}</span></td>
                   <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                        @if (!$kwh_meter_request->is_liquidated && $kwh_meter_request->approved_at == null && $kwh_meter_request->disapproved_at == null)
                            <a type="button" class="btn btn-outline-warning" 
                                    href="{{ route('kwh-meter-request.edit',$kwh_meter_request->id) }}" title="Edit Meter Type">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        <a type="button" class="btn btn-outline-info" 
                                href="{{ route('kwh-meter-request.show',$kwh_meter_request->id) }}" title="View Meter Request">
                            <i class="fas fa-eye"></i>
                        </a>
                        @if ($kwh_meter_request->getLiquidationProgress()['progress_percentage'] == 100 && !$kwh_meter_request->is_liquidated && $kwh_meter_request->approved_at)
                            <button type="button" class="btn btn-outline-success" 
                                onclick="openLiquidationModal({{ $kwh_meter_request->id }}, '{{ $kwh_meter_request->control_no }}')" 
                                title="Liquidate">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="confirmDelete({{ $kwh_meter_request->id }}, '{{ $kwh_meter_request->meterType->meter_brand }}')" title="Delete Details">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <form id="delete-form-{{ $kwh_meter_request->id }}" method="POST" action="{{ route('kwh-meter-request.destroy', $kwh_meter_request->id) }}" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                  </td>
                 </tr>
                @endforeach
               </table>
               <div id="pagination">{{ $kwh_meter_requests->links() }}</div>
            </div>
          </div>
      </div>
  </div>
</div>

<!-- Liquidation Modal -->
<div class="modal fade" id="liquidationModal" tabindex="-1" aria-labelledby="liquidationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="liquidationModalLabel">
                    <i class="fas fa-check-circle me-2"></i>Liquidate Request
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="liquidationForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        You are about to liquidate request: <strong id="modalControlNo"></strong>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="checked_by" class="form-label">
                                    <i class="fas fa-warehouse me-1"></i>Checked By (Warehouse)
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="checked_by" name="checked_by" required>
                                    <option value="">Select Warehouse Staff</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a warehouse staff member.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="approved_by_liquidation" class="form-label">
                                    <i class="fas fa-user-tie me-1"></i>Approved By (TSD Manager)
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="approved_by_liquidation" name="approved_by_liquidation" required>
                                    <option value="">Select TSD Manager</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Please select a TSD Manager.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="liquidation_remarks" class="form-label">
                            <i class="fas fa-comment me-1"></i>Remarks (Optional)
                        </label>
                        <textarea class="form-control" id="liquidation_remarks" name="liquidation_remarks" rows="3" 
                                placeholder="Enter any additional remarks for this liquidation..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Confirm Liquidation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function confirmDelete(meterId, meterBrand) {
    Swal.fire({
        title: 'Delete Meter Type?',
        text: `Are you sure you want to delete "${meterBrand}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${meterId}`).submit();
        }
    });
}

function openLiquidationModal(requestId, controlNo) {
    // Set the control number in the modal
    document.getElementById('modalControlNo').textContent = controlNo;
    
    // Set the form action URL using Laravel route
    document.getElementById('liquidationForm').action = `{{ url('kwh-meter-request') }}/${requestId}/liquidate`;
    
    // Reset form validation
    const form = document.getElementById('liquidationForm');
    form.classList.remove('was-validated');
    
    // Clear previous values
    document.getElementById('checked_by').value = '';
    document.getElementById('approved_by_liquidation').value = '';
    document.getElementById('liquidation_remarks').value = '';
    
    // Show the modal - compatible with both Bootstrap 4 and 5
    const modalElement = document.getElementById('liquidationModal');
    if (typeof bootstrap !== 'undefined') {
        // Bootstrap 5
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        // Bootstrap 4 with jQuery
        $(modalElement).modal('show');
    } else {
        // Fallback: manually show modal
        modalElement.style.display = 'block';
        modalElement.classList.add('show');
        document.body.classList.add('modal-open');
        
        // Create backdrop
        const backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        document.body.appendChild(backdrop);
    }
}

// Handle liquidation form submission
document.getElementById('liquidationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const checkedBy = document.getElementById('checked_by').value;
    const approvedBy = document.getElementById('approved_by_liquidation').value;
    
    // Validate required fields
    if (!checkedBy || !approvedBy) {
        form.classList.add('was-validated');
        return;
    }
    
    // Show confirmation dialog
    Swal.fire({
        title: 'Confirm Liquidation',
        text: 'Are you sure you want to liquidate this request?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Liquidate!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait while we process the liquidation.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit the form
            form.submit();
        }
    });
});

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        toast: true,
        position: 'top-end',
        timer: 5000,
        showConfirmButton: false
    });
@endif
</script>
@endsection