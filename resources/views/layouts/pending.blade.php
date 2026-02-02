@extends('layouts.app')

@section('styles')
<style>
.status-badge {
    font-size: 0.875rem;
    min-width: 80px;
    text-align: center;
}
.table-responsive {
    border-radius: 0.375rem;
}
.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

/* Ensure badge colors are properly displayed */
.badge.bg-success {
    background-color: #198754 !important;
    color: white !important;
}
.badge.bg-danger {
    background-color: #dc3545 !important;
    color: white !important;
}
.badge.bg-warning {
    background-color: #ffc107 !important;
    color: #212529 !important;
}
.badge.bg-secondary {
    background-color: #6c757d !important;
    color: white !important;
}
</style>
@endsection

@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <div class="row align-items-center">
              <div class="col-md-6">
                  <h4 class="mb-0">
                      <i class="fas fa-clock me-2"></i>Pending Requests
                  </h4>
              </div>
              <div class="col-md-6 text-end">
                  <button type="button" class="btn btn-light btn-sm" onclick="refreshPage()">
                      <i class="fas fa-sync-alt"></i> Refresh
                  </button>
              </div>
          </div>
        </div>
        <div class="card-body">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('pending.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search by transaction, table name, or status..."
                                   value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('pending.index') }}" class="btn btn-outline-danger">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Approved</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Disapproved</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-end">
                        @if(request('search') || request('status'))
                            <a href="{{ route('pending.index') }}" class="btn btn-outline-secondary" title="Clear All Filters">
                                <i class="fas fa-times"></i> Clear All
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <!-- Search Results Indicator -->
            @if(request('search') || request('status'))
                <div class="alert alert-info mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-filter"></i> 
                            <strong>Active Filters:</strong>
                            @if(request('search'))
                                Search: "<em>{{ request('search') }}</em>"
                            @endif
                            @if(request('status'))
                                @if(request('search')) | @endif
                                Status: <span class="badge bg-primary">
                                    @if(request('status') == '0')
                                        Pending
                                    @elseif(request('status') == '1')
                                        Approved
                                    @elseif(request('status') == '2')
                                        Disapproved
                                    @else
                                        {{ request('status') }}
                                    @endif
                                </span>
                            @endif
                            <small class="text-muted">({{ $pendings->total() }} {{ Str::plural('result', $pendings->total()) }} found)</small>
                        </div>
                        <a href="{{ route('pending.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear All Filters
                        </a>
                    </div>
                </div>
            @endif

            <div class="row">
              <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th><i class="fas fa-hashtag"></i> No.</th>
                          <th><i class="fas fa-exchange-alt"></i> Transaction</th>
                          <th><i class="fas fa-barcode"></i> Control No.</th>
                          <th><i class="fas fa-flag"></i> Status</th>
                          <th><i class="fas fa-user"></i> Requested By</th>
                          <th><i class="fas fa-calendar"></i> Date</th>
                          <th><i class="fas fa-eye"></i> View Details</th>
                          <th class="text-center"><i class="fas fa-cog"></i> Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse($pendings as $pending)
                          <tr>
                            <td>{{ $pendings->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="fw-bold">{{ $pending->transaction }}</span>
                            </td>
                            <td>
                                <span>{{ $pending->kwhMeterRequest->control_no ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @php
                                    // Convert status to integer for proper matching
                                    $statusValue = (int) $pending->status;
                                    
                                    if ($statusValue === 1) {
                                        $statusClass = 'bg-success text-white';
                                        $statusIcon = 'fas fa-check me-1';
                                        $statusText = 'Approved';
                                    } elseif ($statusValue === 2) {
                                        $statusClass = 'bg-danger text-white';
                                        $statusIcon = 'fas fa-times me-1';
                                        $statusText = 'Disapproved';
                                    } else {
                                        $statusClass = 'bg-warning text-dark';
                                        $statusIcon = 'fas fa-clock me-1';
                                        $statusText = 'Pending';
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }} status-badge">
                                    <i class="{{ $statusIcon }}"></i>{{ $statusText }}
                                </span>
                            </td>
                            <td>{{ $pending->related_record->user->name ?? 'N/A' }}</td>
                            <td>
                                <small class="text-muted">{{ $pending->created_at->format('M d, Y H:i') }}</small>
                            </td>
                            <td>
                              <button type="button" class="btn btn-info btn-sm" onclick="openDetailsModal('{{ route('pending.details', $pending->id) }}', '{{ $pending->transaction }}')">
                                  <i class="fas fa-eye"></i> View
                              </button>
                            </td>
                            <td class="text-center">
                              @if($pending->status == 0 || $pending->status === null)
                                  <div class="btn-group btn-group-sm" role="group">
                                      <button type="button" class="btn btn-outline-success" 
                                              onclick="confirmApproval({{ $pending->id }})" 
                                              title="Approve Transaction">
                                          <i class="fas fa-check"></i> Approve
                                      </button>
                                      <button type="button" class="btn btn-outline-danger" 
                                              onclick="confirmDisapproval({{ $pending->id }})" 
                                              title="Disapprove Transaction">
                                          <i class="fas fa-times"></i> Disapprove
                                      </button>
                                  </div>
                              @else
                                  <span class="text-muted">
                                      <i class="fas fa-check-circle"></i> Processed
                                  </span>
                              @endif
                            </td> 
                          </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No pending transactions found.</p>
                                        @if(request('search') || request('status'))
                                            <a href="{{ route('pending.index') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-times"></i> Clear Filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                      </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($pendings->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <small class="text-muted">
                                Showing {{ $pendings->firstItem() ?? 0 }} to {{ $pendings->lastItem() ?? 0 }} 
                                of {{ $pendings->total() }} {{ Str::plural('result', $pendings->total()) }}
                            </small>
                        </div>
                        <div>
                            {{ $pendings->links() }}
                        </div>
                    </div>
                @endif
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Details Modal -->
  <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header bg-info text-white">
          <h5 class="modal-title" id="detailsModalLabel">
            <i class="fas fa-eye me-2"></i>Transaction Details
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="detailsModalBody" style="min-height: 400px;">
          <div class="text-center py-5">
            <div class="spinner-border text-info" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading transaction details...</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times"></i> Close
          </button>
          <button type="button" class="btn btn-info" id="openInNewTabBtn">
            <i class="fas fa-external-link-alt"></i> Open in New Tab
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
// Global function declarations (accessible to onclick events)
window.confirmApproval = function(pendingId) {
    Swal.fire({
        title: 'Approve Transaction?',
        text: 'Are you sure you want to approve this pending transaction? This action cannot be undone.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check"></i> Yes, Approve',
        cancelButtonText: '<i class="fas fa-times"></i> Cancel',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return approvePending(pendingId);
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed) {
            // Success is handled in the approvePending function
        }
    });
};

window.confirmDisapproval = function(pendingId) {
    Swal.fire({
        title: 'Disapprove Transaction?',
        html: `
            <p>Are you sure you want to disapprove this pending transaction?</p>
            <div class="mt-3">
                <label for="disapproval_remarks" class="form-label text-start d-block">
                    <strong>Reason for Disapproval: <span class="text-danger">*</span></strong>
                </label>
                <textarea 
                    id="disapproval_remarks" 
                    class="form-control" 
                    rows="4" 
                    placeholder="Please provide a reason for disapproving this transaction..."
                    maxlength="500"
                    required
                ></textarea>
                <small class="text-muted d-block mt-1">Maximum 500 characters</small>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-times"></i> Yes, Disapprove',
        cancelButtonText: '<i class="fas fa-arrow-left"></i> Cancel',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const remarks = document.getElementById('disapproval_remarks').value.trim();
            if (!remarks) {
                Swal.showValidationMessage('Please provide a reason for disapproval');
                return false;
            }
            if (remarks.length < 10) {
                Swal.showValidationMessage('Please provide at least 10 characters for the reason');
                return false;
            }
            return disapprovePending(pendingId, remarks);
        },
        allowOutsideClick: () => !Swal.isLoading(),
        didOpen: () => {
            // Focus on textarea when modal opens
            document.getElementById('disapproval_remarks').focus();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Success is handled in the disapprovePending function
        }
    });
};

window.disapprovePending = function(pendingId, remarks) {
    const formData = new FormData();
    formData.append('pending_id', pendingId);
    formData.append('remarks', remarks);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    return fetch('{{ route("pending.disapprove") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Transaction Disapproved!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#007bff',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload the page to reflect changes
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to disapprove transaction');
        }
    })
    .catch(error => {
        console.error('Error disapproving transaction:', error);
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Failed to disapprove transaction. Please try again.',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        throw error; // Re-throw to prevent "success" handling
    });
};

window.approvePending = function(pendingId) {
    const formData = new FormData();
    formData.append('pending_id', pendingId);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    return fetch('{{ route("pending.approve") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#007bff',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload the page to reflect changes
                window.location.reload();
            });
        } else {
            throw new Error(data.message || 'Failed to approve transaction');
        }
    })
    .catch(error => {
        console.error('Error approving transaction:', error);
        Swal.fire({
            title: 'Error!',
            text: error.message || 'Failed to approve transaction. Please try again.',
            icon: 'error',
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK'
        });
        throw error; // Re-throw to prevent "success" handling
    });
};


window.refreshPage = function() {
    window.location.reload();
};

// Modal functions
window.openDetailsModal = function(detailsUrl, transactionName) {
    // Update modal title
    $('#detailsModalLabel').html('<i class="fas fa-eye me-2"></i>' + transactionName + ' - Transaction Details');
    
    // Show loading state
    $('#detailsModalBody').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-info" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading transaction details...</p>
        </div>
    `);
    
    // Store details URL for retry, we'll get the original URL from the response
    $('#openInNewTabBtn').data('details-url', detailsUrl);
    
    // Show modal
    $('#detailsModal').modal('show');
    
    // Load content via AJAX
    fetch(detailsUrl, {
        method: 'GET',
        headers: {
            'Accept': 'text/html',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();
    })
    .then(html => {
        // Load the HTML content into the modal
        $('#detailsModalBody').html(html);
        
        // Look for original URL link in the loaded content for "Open in New Tab"
        const originalLink = $('#detailsModalBody').find('a[target="_blank"]').attr('href');
        if (originalLink) {
            $('#openInNewTabBtn').data('url', originalLink);
        }
        
        // Re-initialize any JavaScript components in the loaded content if needed
        $('[data-bs-toggle="tooltip"]').tooltip();
    })
    .catch(error => {
        console.error('Error loading transaction details:', error);
        $('#detailsModalBody').html(`
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Error Loading Details</h5>
                <p>Failed to load transaction details: ${error.message}</p>
                <div class="mt-3">
                    <button class="btn btn-outline-danger btn-sm" onclick="openDetailsModal('${detailsUrl}', '${transactionName}')">
                        <i class="fas fa-redo"></i> Try Again
                    </button>
                </div>
            </div>
        `);
    });
};

window.closeDetailsModal = function() {
    $('#detailsModal').modal('hide');
};

// Document ready function
$(document).ready(function() {
    // Handle "Open in New Tab" button in modal
    $('#openInNewTabBtn').on('click', function() {
        const originalUrl = $(this).data('url');
        const detailsUrl = $(this).data('details-url');
        
        if (originalUrl) {
            // Use original transaction URL if available
            window.open(originalUrl, '_blank');
        } else if (detailsUrl) {
            // Fallback to details URL if original URL not found
            window.open(detailsUrl, '_blank');
        }
    });
    
    // Clean up modal content when closed
    $('#detailsModal').on('hidden.bs.modal', function() {
        $('#detailsModalBody').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-info" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading transaction details...</p>
            </div>
        `);
    });
    
    // Add any initialization code here if needed
    console.log('Pending transactions page loaded');
});
</script>
@endsection