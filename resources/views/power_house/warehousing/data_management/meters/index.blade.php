@extends('layouts.app')

@section('styles')
<style>


.meter-card {
    transition: transform 0.2s;
}

.meter-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
}



.form-floating label {
    color: #6c757d;
}

.form-floating .form-control:focus ~ label,
.form-floating .form-control:not(:placeholder-shown) ~ label {
    color: #007bff;
}

.validation-message {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.validation-message.valid {
    color: #28a745;
}

.validation-message.invalid {
    color: #dc3545;
}



@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-vertical {
        width: 100%;
    }
    
    .btn-group-vertical .btn {
        margin-bottom: 5px;
    }
}
</style>
@endsection

@section('content')
<div class="container">
    <!-- Add/Edit Meter Modal -->
    <div class="modal fade" id="meterModal" tabindex="-1" role="dialog" aria-labelledby="meterModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="meterModalLabel">
                        <i class="fas fa-plus-circle"></i> Add New Meter
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeMeterModal()"></button>
                </div>
                <div class="modal-body">
                    <form id="meterForm">
                        <input type="hidden" id="meter_id" name="meter_id">
                        
                        <!-- Meter Brand -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="meter_type_id" name="meter_type_id" required>
                                        <option value="">Select Meter Type</option>
                                        @foreach($meters_types as $meterType)
                                            <option value="{{ $meterType->id }}" 
                                                    data-brand="{{ $meterType->meter_brand }}"
                                                    data-code="{{ $meterType->meter_code }}" 
                                                    data-description="{{ $meterType->meter_description }}">
                                                {{ $meterType->meter_brand }} ({{ $meterType->meter_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="meter_type_id"><i class="fas fa-industry"></i> Meter Type *</label>
                                </div>
                                <small class="text-muted mt-1" id="meter_description"></small>
                            </div>
                        </div>

                        <!-- Serial Number -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="serial_number" name="serial_number" 
                                           placeholder="Enter serial number" required>
                                    <label for="serial_number"><i class="fas fa-barcode"></i> Serial Number *</label>
                                </div>
                                <div id="serial_validation" class="validation-message"></div>
                            </div>
                        </div>

                        <!-- ERC Seal Number -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="erc_seal_number" name="erc_seal_number" 
                                           placeholder="Enter ERC seal number" required>
                                    <label for="erc_seal_number"><i class="fas fa-certificate"></i> ERC Seal Number *</label>
                                </div>
                                <div id="erc_validation" class="validation-message"></div>
                            </div>
                        </div>

                        <!-- LEYECO Seal Number (Input Only) -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="leyeco_seal_number" name="leyeco_seal_number" 
                                           placeholder="Enter LEYECO seal number" required>
                                    <label for="leyeco_seal_number"><i class="fas fa-seal"></i> LEYECO Seal Number *</label>
                                </div>
                                <div id="leyeco_validation" class="validation-message"></div>
                            </div>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeMeterModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="saveMeterBtn" onclick="saveMeter()">
                        <i class="fas fa-save"></i> <span id="saveButtonText">Save Meter</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Meter Modal -->
    <div class="modal fade" id="assignMeterModal" tabindex="-1" role="dialog" aria-labelledby="assignMeterModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="assignMeterModalLabel">
                        <i class="fas fa-link"></i> Assign Meter to Transaction
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeAssignMeterModal()"></button>
                </div>
                <div class="modal-body">
                    <!-- Meter Information Display -->
                    <div class="alert alert-info mb-3" id="meterInfoDisplay">
                        <h6><i class="fas fa-info-circle"></i> Meter Information:</h6>
                        <div id="meterInfoContent"></div>
                    </div>

                    <form id="assignMeterForm">
                        <input type="hidden" id="assign_meter_id" name="meter_id">
                        
                        <!-- Transaction Type -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select" id="assign_control_type" name="control_type" required>
                                        <option value="">Select Transaction Type</option>
                                        @foreach(config('constants.meter_transaction_type') as $transaction_type)
                                            <option value="{{ $transaction_type['name'] }}">{{ $transaction_type['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <label for="assign_control_type"><i class="fas fa-cogs"></i> Transaction Type *</label>
                                </div>
                            </div>
                        </div>

                        <!-- Control Number (Dynamic based on Transaction Type) -->
                        <div class="row mb-3" id="controlNumberSection">
                            <div class="col-12">
                                <!-- Text Input for non-Change Meter types -->
                                <div class="form-floating" id="controlNumberTextInput" style="display: none;">
                                    <input type="text" class="form-control" id="assign_control_no_text" name="control_no" 
                                           placeholder="Enter control number">
                                    <label for="assign_control_no_text"><i class="fas fa-hashtag"></i> Control Number</label>
                                    <div class="invalid-feedback" id="controlNumberTextFeedback"></div>
                                </div>
                                
                                <!-- Dropdown for Change Meter type -->
                                <div class="form-floating" id="controlNumberDropdown" style="display: none;">
                                    <select class="form-select" id="assign_control_no_select" name="control_no">
                                        <option value="">Loading change meter requests...</option>
                                    </select>
                                    <label for="assign_control_no_select"><i class="fas fa-hashtag"></i> Control Number *</label>
                                    <div class="invalid-feedback" id="controlNumberSelectFeedback"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="assign_account_number" name="account_number" 
                                           placeholder="Enter account number">
                                    <label for="assign_account_number"><i class="fas fa-user"></i> Account Number</label>
                                </div>
                                <small class="text-muted" id="accountNumberHelp" style="display: none;">
                                    <i class="fas fa-info-circle"></i> Account number is automatically filled from the selected change meter request
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="closeAssignMeterModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="btn btn-warning" id="assignMeterBtn" onclick="assignMeter()">
                        <i class="fas fa-link"></i> Assign Meter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0">
                                <i class="fas fa-tachometer-alt"></i> Meter Management System
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-light btn-sm me-2" onclick="refreshMeters()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <button type="button" class="btn btn-success" onclick="openMeterModal()">
                                <i class="fas fa-plus"></i> Add New Meter
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('meters.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="search" 
                                           placeholder="Search by brand, serial number, seal numbers, control number, or account number..."
                                           value="{{ request('search') }}">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i> Search
                                    </button>
                                    @if(request('search') || request('status'))
                                        <a href="{{ route('meters.index') }}" class="btn btn-outline-danger">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <select class="form-select" name="status" onchange="this.form.submit()">
                                    <option value="">All Meters</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                    <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                </select>
                            </div>
                            <div class="col-lg-4 text-end">
                                @if(request('search') || request('status'))
                                    <a href="{{ route('meters.index') }}" class="btn btn-outline-secondary me-2" title="Clear All Filters">
                                        <i class="fas fa-times"></i> Clear All
                                    </a>
                                @endif
                                <button type="button" class="btn btn-outline-primary" onclick="exportMeters()">
                                    <i class="fas fa-download"></i> Export CSV
                                </button>
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
                                        Status: <span class="badge bg-primary">{{ ucfirst(request('status')) }} Meters</span>
                                    @endif
                                    <small class="text-muted">({{ $meters->total() }} {{ Str::plural('result', $meters->total()) }} found)</small>
                                </div>
                                <a href="{{ route('meters.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times"></i> Clear All Filters
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Meters Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-industry"></i> Brand</th>
                                    <th><i class="fas fa-info-circle"></i> Status</th>
                                    <th><i class="fas fa-barcode"></i> Serial No.</th>
                                    <th><i class="fas fa-seal"></i> L5 Seal</th>
                                    <th><i class="fas fa-certificate"></i> ERC Seal</th>
                                    <th><i class="fas fa-cogs"></i> Control Type</th>
                                    <th><i class="fas fa-hashtag"></i> Control No.</th>
                                    <th><i class="fas fa-user"></i> Account No.</th>
                                    <th><i class="fas fa-calendar"></i> Created</th>
                                    <th class="text-center"><i class="fas fa-cog"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody id="metersTableBody">
                                @forelse($meters as $meter)
                                <tr class="meter-row" data-meter-id="{{ $meter->id }}">
                                    <td>
                                        @if(!empty($meter->control_type) || !empty($meter->control_no) || !empty($meter->account_number))
                                            <span class="badge bg-success" title="Assigned Meter">{{ $meter->meterType->meter_brand ?? 'N/A' }}</span>
                                        @else
                                            <span class="badge bg-warning text-dark" title="Available Meter">{{ $meter->meterType->meter_brand ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($meter->control_type) || !empty($meter->control_no) || !empty($meter->account_number))
                                            <small class="text-success"><i class="fas fa-lock"></i> Assigned</small>
                                        @else
                                            <small class="text-warning"><i class="fas fa-unlock"></i> Available</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $meter->serial_number }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $meter->leyeco_seal_number }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $meter->erc_seal_number }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $meter->control_type ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if($meter->changeMeterRequest && $meter->changeMeterRequest->status == 2)
                                            {{-- <span class="text-success" title="Change Meter Request ID: {{ $meter->changeMeterRequest->id }}">
                                                {{ $meter->control_no }}
                                            </span> --}}

                                            <a class="text-success text-decoration-none" href="{{ route('viewCM',  $meter->changeMeterRequest->id) }}">{{ $meter->control_no }}</a>
                                        @else
                                            <code>{{ $meter->control_no ?: '-' }}</code>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $meter->account_number ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $meter->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary" 
                                                    onclick="viewMeter({{ $meter->id }})" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(empty($meter->control_type) && empty($meter->control_no) && empty($meter->account_number))
                                                <button type="button" class="btn btn-outline-warning" 
                                                        onclick="editMeter({{ $meter->id }})" title="Edit Meter">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-warning disabled" 
                                                        title="Cannot edit assigned meter" disabled>
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            @if(empty($meter->control_type) && empty($meter->control_no) && empty($meter->account_number))
                                                <button type="button" class="btn btn-outline-success" 
                                                        onclick="openAssignMeterModal({{ $meter->id }})" title="Assign Meter">
                                                    <i class="fas fa-link"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-outline-secondary" 
                                                        onclick="returnMeter({{ $meter->id }})" title="Return Meter">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-outline-info" 
                                                    onclick="viewAuditLogs({{ $meter->id }})" title="View Audit Logs">
                                                <i class="fas fa-history"></i>
                                            </button>
                                            <button type="button" class="btn btn-outline-danger" 
                                                    onclick="deleteMeter({{ $meter->id }})" title="Delete Meter">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>No meters found. Click "Add New Meter" to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Showing {{ $meters->firstItem() ?? 0 }} to {{ $meters->lastItem() ?? 0 }} 
                                of {{ $meters->total() }} {{ Str::plural('result', $meters->total()) }}
                            </small>
                        </div>
                        <div>
                            {{ $meters->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Modal -->
    <div class="modal fade" id="auditLogsModal" tabindex="-1" aria-labelledby="auditLogsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="auditLogsModalLabel">
                        <i class="fas fa-history"></i> Meter Audit Logs
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="auditLogsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
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
// Document ready function
$(document).ready(function() {
});

// Global function declarations (accessible to onclick events)
window.openMeterModal = function(meterId = null) {
    // Clear previous data and validation messages
    $('#meterForm')[0].reset();
    $('#meter_id').val('');
    $('.validation-message').text('').removeClass('valid invalid');
    
    if (meterId) {
        $('#meterModalLabel').html('<i class="fas fa-edit"></i> Edit Meter');
        $('#saveButtonText').text('Update Meter');
        loadMeterData(meterId);
    } else {
        $('#meterModalLabel').html('<i class="fas fa-plus-circle"></i> Add New Meter');
        $('#saveButtonText').text('Save Meter');
    }
    
    // Show the modal - Bootstrap 5 with static backdrop
    try {
        // Use Bootstrap 5 Modal class or jQuery
        var modal = new bootstrap.Modal(document.getElementById('meterModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    } catch (error) {
        // Fallback to jQuery if Bootstrap 5 Modal class not available
        $('#meterModal').modal('show');
    }
};

window.closeMeterModal = function() {
    try {
        // Use Bootstrap 5 Modal class
        var modal = bootstrap.Modal.getInstance(document.getElementById('meterModal'));
        if (modal) {
            modal.hide();
        } else {
            $('#meterModal').modal('hide');
        }
    } catch (error) {
        // Fallback to jQuery
        $('#meterModal').modal('hide');
    }
};

window.loadMeterData = function(meterId) {
    fetch(`/meters/${meterId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        if (response.success) {
            const meter = response.data;
            $('#meter_id').val(meter.id);
            $('#meter_type_id').val(meter.meter_type_id);
            $('#serial_number').val(meter.serial_number);
            $('#erc_seal_number').val(meter.erc_seal_number);
            $('#leyeco_seal_number').val(meter.leyeco_seal_number);
            
            // Trigger change event to show description
            $('#meter_type_id').trigger('change');
        } else {
            throw new Error(response.message || 'Invalid response format');
        }
    })
    .catch(error => {
        console.error('Error loading meter data:', error);
        showAlert('Failed to load meter data: ' + error.message, 'error');
    });
};

window.saveMeter = function() {
    const form = document.getElementById('meterForm');
    const formData = new FormData(form);
    const meterId = $('#meter_id').val();
    
    // Add CSRF token
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    
    let url, method;
    if (meterId) {
        // Update existing meter
        url = `/meters/${meterId}`;
        method = 'POST';
        formData.append('_method', 'PUT');
    } else {
        // Create new meter
        url = '/meters';
        method = 'POST';
    }
    
    // Disable submit button to prevent double submission
    const submitButton = document.getElementById('saveMeterBtn');
    submitButton.disabled = true;
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.ok) {
            showAlert('Meter saved successfully!', 'success');
            closeMeterModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            return response.json().then(data => {
                throw new Error(data.message || `Server error: ${response.status}`);
            });
        }
    })
    .catch(error => {
        console.error('Error saving meter:', error);
        showAlert('Error saving meter: ' + error.message, 'error');
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
};

// Assign Meter Functions
window.openAssignMeterModal = function(meterId) {
    // Clear previous data
    $('#assignMeterForm')[0].reset();
    $('#assign_meter_id').val(meterId);
    
    // Initialize control number fields
    $('#controlNumberTextInput').show();
    $('#controlNumberDropdown').hide();
    $('#assign_control_no_select').html('<option value="">Select Control Number</option>');
    
    // Reset assign button state
    $('#assignMeterBtn').prop('disabled', false);
    $('#assignMeterBtn').html('<i class="fas fa-link"></i> Assign Meter');
    
    // Clear validation states
    $('#assign_control_no_select, #assign_control_no_text').removeClass('is-invalid');
    $('#controlNumberSelectFeedback, #controlNumberTextFeedback').text('');
    
    // Initialize account number field as editable (default state)
    $('#assign_account_number').prop('readonly', false).removeClass('bg-light');
    $('#accountNumberHelp').hide();
    
    // Load meter information to display
    loadMeterInfoForAssignment(meterId);
    
    // Show the modal
    try {
        var modal = new bootstrap.Modal(document.getElementById('assignMeterModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();
    } catch (error) {
        $('#assignMeterModal').modal('show');
    }
};

window.closeAssignMeterModal = function() {
    try {
        var modal = bootstrap.Modal.getInstance(document.getElementById('assignMeterModal'));
        if (modal) {
            modal.hide();
        } else {
            $('#assignMeterModal').modal('hide');
        }
    } catch (error) {
        $('#assignMeterModal').modal('hide');
    }
};

window.loadMeterInfoForAssignment = function(meterId) {
    fetch(`/meters/${meterId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        if (response.success) {
            const meter = response.data;
            const meterType = meter.meter_type ? meter.meter_type : null;
            
            let infoHtml = `
                <div class="row">
                    <div class="col-md-6">
                        <strong>Meter Brand:</strong> ${meterType ? meterType.meter_brand : 'N/A'}<br>
                        <strong>Meter Code:</strong> ${meterType ? meterType.meter_code : 'N/A'}
                    </div>
                    <div class="col-md-6">
                        <strong>Serial Number:</strong> <code>${meter.serial_number}</code><br>
                        <strong>ERC Seal:</strong> <code>${meter.erc_seal_number}</code><br>
                        <strong>LEYECO Seal:</strong> <code>${meter.leyeco_seal_number}</code>
                    </div>
                </div>
            `;
            
            $('#meterInfoContent').html(infoHtml);
        } else {
            throw new Error(response.message || 'Failed to load meter information');
        }
    })
    .catch(error => {
        console.error('Error loading meter info:', error);
        $('#meterInfoContent').html('<div class="text-danger">Failed to load meter information</div>');
        showAlert('Failed to load meter information: ' + error.message, 'error');
    });
};

window.assignMeter = function() {
    const form = document.getElementById('assignMeterForm');
    const formData = new FormData();
    const meterId = $('#assign_meter_id').val();
    
    // Get control type
    const controlType = $('#assign_control_type').val();
    formData.append('control_type', controlType);
    
    // Get control number from appropriate field
    let controlNo = '';
    if (controlType === 'Change Meter') {
        controlNo = $('#assign_control_no_select').val();
    } else {
        controlNo = $('#assign_control_no_text').val();
    }
    if (controlNo) {
        formData.append('control_no', controlNo);
    }
    
    // Get account number
    const accountNumber = $('#assign_account_number').val();
    if (accountNumber) {
        formData.append('account_number', accountNumber);
    }
    
    // Add CSRF token and method
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('_method', 'PUT');
    
    // Disable submit button
    const submitButton = document.getElementById('assignMeterBtn');
    submitButton.disabled = true;
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Assigning...';
    
    fetch(`/meters/${meterId}/assign`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (response.ok) {
            showAlert('Meter assigned successfully!', 'success');
            closeAssignMeterModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            return response.json().then(data => {
                throw new Error(data.message || `Server error: ${response.status}`);
            });
        }
    })
    .catch(error => {
        console.error('Error assigning meter:', error);
        showAlert('Error assigning meter: ' + error.message, 'error');
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    });
};

// Control Number Management Functions
window.handleTransactionTypeChange = function() {
    const transactionType = $('#assign_control_type').val();
    
    // Reset account number field
    $('#assign_account_number').val('');
    
    // Clear validation states
    $('#assign_control_no_select, #assign_control_no_text').removeClass('is-invalid');
    $('#controlNumberSelectFeedback, #controlNumberTextFeedback').text('');
    
    // Reset assign button state
    $('#assignMeterBtn').prop('disabled', false);
    $('#assignMeterBtn').html('<i class="fas fa-link"></i> Assign Meter');
    
    if (transactionType === 'Change Meter') {
        // Show dropdown, hide text input
        $('#controlNumberTextInput').hide();
        $('#controlNumberDropdown').show();
        
        // Make account number field readonly and add visual indicator
        $('#assign_account_number').prop('readonly', true).addClass('bg-light');
        $('#accountNumberHelp').show();
        
        // Fetch change meter requests
        fetchChangeMeterRequests();
    } else {
        // Show text input, hide dropdown
        $('#controlNumberDropdown').hide();
        $('#controlNumberTextInput').show();
        
        // Make account number field editable
        $('#assign_account_number').prop('readonly', false).removeClass('bg-light');
        $('#accountNumberHelp').hide();
        
        // Clear dropdown
        $('#assign_control_no_select').html('<option value="">Select Control Number</option>');
    }
};

window.fetchChangeMeterRequests = function() {
    // Show loading state
    $('#assign_control_no_select').html('<option value="">Loading change meter requests...</option>');
    
    fetch('/meters/change-meter-requests', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        if (response.success) {
            populateControlNumberDropdown(response.data);
        } else {
            throw new Error(response.message || 'Failed to fetch change meter requests');
        }
    })
    .catch(error => {
        console.error('Error fetching change meter requests:', error);
        $('#assign_control_no_select').html('<option value="">Failed to load change meter requests</option>');
        showAlert('Failed to load change meter requests: ' + error.message, 'error');
    });
};

window.populateControlNumberDropdown = function(requests) {
    let options = '<option value="">Select Control Number</option>';
    
    if (requests && requests.length > 0) {
        requests.forEach(request => {
            options += `<option value="${request.control_no}" data-account="${request.account_number || ''}">${request.display_text}</option>`;
        });
    } else {
        options = '<option value="">No pending change meter requests available</option>';
    }
    
    $('#assign_control_no_select').html(options);
};

window.handleControlNumberChange = function() {
    const selectedOption = $('#assign_control_no_select option:selected');
    const accountNumber = selectedOption.data('account') || '';
    
    // Auto-fill account number if available
    if (accountNumber) {
        $('#assign_account_number').val(accountNumber);
        
        // Brief visual feedback that field was auto-filled
        $('#assign_account_number').addClass('border-success');
        setTimeout(function() {
            $('#assign_account_number').removeClass('border-success');
        }, 1500);
    } else {
        $('#assign_account_number').val('');
    }

    // Validate control number for duplicates
    const controlNo = $('#assign_control_no_select').val();
    if (controlNo) {
        validateControlNumber(controlNo);
    }
};

window.validateControlNumber = function(controlNo) {
    if (!controlNo) return;
    
    const meterId = $('#assign_meter_id').val();
    
    fetch('/meters/validate-control-number', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({
            control_no: controlNo,
            meter_id: meterId
        })
    })
    .then(response => response.json())
    .then(response => {
        if (!response.valid) {
            showAlert(response.message, 'warning');
            // Disable the assign button
            $('#assignMeterBtn').prop('disabled', true);
            $('#assignMeterBtn').html('<i class="fas fa-exclamation-triangle"></i> Control Number Already Used');
            
            // Add warning styling and feedback message
            if ($('#assign_control_type').val() === 'Change Meter') {
                $('#assign_control_no_select').addClass('is-invalid');
                $('#controlNumberSelectFeedback').text(response.message);
            } else {
                $('#assign_control_no_text').addClass('is-invalid');
                $('#controlNumberTextFeedback').text(response.message);
            }
        } else {
            // Re-enable assign button if it was disabled
            $('#assignMeterBtn').prop('disabled', false);
            $('#assignMeterBtn').html('<i class="fas fa-link"></i> Assign Meter');
            
            // Remove warning styling and clear feedback messages
            $('#assign_control_no_select, #assign_control_no_text').removeClass('is-invalid');
            $('#controlNumberSelectFeedback, #controlNumberTextFeedback').text('');
        }
    })
    .catch(error => {
        console.error('Error validating control number:', error);
        showAlert('Error validating control number', 'error');
    });
};

// Validation Functions
window.initializeValidation = function() {
    $('#serial_number').on('blur', function() {
        const value = $(this).val();
        if (value) {
            validateField('serial_number', value);
        }
    });
    
    $('#erc_seal_number').on('blur', function() {
        const value = $(this).val();
        if (value) {
            validateField('erc_seal_number', value);
        }
    });
};

window.validateField = function(fieldType, value) {
    const meterId = $('#meter_id').val();
    let endpoint = '';
    
    if (fieldType === 'serial_number') {
        endpoint = '/meters/validate-serial';
    } else if (fieldType === 'erc_seal_number') {
        endpoint = '/meters/validate-erc-seal';
    } else {
        return;
    }
    
    const data = {
        [fieldType]: value,
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    if (meterId) {
        data.meter_id = meterId;
    }
    
    $.post(endpoint, data)
    .done(function(response) {
        let validationElementId = fieldType === 'serial_number' ? 'serial_validation' : 'erc_validation';
        const messageElement = $(`#${validationElementId}`);
        
        if (response.valid) {
            messageElement.text('✓ ' + response.message).removeClass('invalid').addClass('valid');
        } else {
            messageElement.text('✗ ' + response.message).removeClass('valid').addClass('invalid');
        }
    })
    .fail(function(xhr) {
        let validationElementId = fieldType === 'serial_number' ? 'serial_validation' : 'erc_validation';
        let errorMsg = 'Validation failed';
        
        if (xhr.status === 401) {
            errorMsg = 'Unauthorized - Please refresh the page';
        } else if (xhr.status === 404) {
            errorMsg = 'Validation endpoint not found';
        } else if (xhr.status === 422) {
            errorMsg = 'Invalid data format';
        }
        
        $(`#${validationElementId}`).text('✗ ' + errorMsg).removeClass('valid').addClass('invalid');
    });
};

// Meter Brand Description Functions
window.initializeMeterBrandDescription = function() {
    // Show meter description when type is selected
    $(document).on('change', '#meter_type_id', function() {
        const selectedOption = $(this).find('option:selected');
        const description = selectedOption.data('description');
        const code = selectedOption.data('code');
        const brand = selectedOption.data('brand');
        
        if (description && code && brand) {
            $('#meter_description').text(`${brand} - Code: ${code} - ${description}`);
        } else {
            $('#meter_description').text('');
        }
    });
    
    // Initialize description on modal open (for edit mode)
    $('#meterModal').on('shown.bs.modal', function() {
        const selectedOption = $('#meter_type_id option:selected');
        const description = selectedOption.data('description');
        const code = selectedOption.data('code');
        const brand = selectedOption.data('brand');
        
        if (description && code && brand) {
            $('#meter_description').text(`${brand} - Code: ${code} - ${description}`);
        }
    });
};

// CRUD Functions
window.editMeter = function(meterId) {
    // First check if the meter is assigned by making an API call
    fetch(`/meters/${meterId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(response => {
        if (response.success) {
            const meter = response.data;
            
            // Check if meter is assigned
            if (meter.control_type || meter.control_no || meter.account_number) {
                Swal.fire({
                    title: 'Cannot Edit Assigned Meter',
                    text: 'This meter is currently assigned to a transaction. Please return the meter first to make changes.',
                    icon: 'warning',
                    confirmButtonColor: '#6c757d',
                    confirmButtonText: 'Understood'
                });
                return;
            }
            
            // If not assigned, proceed with edit
            openMeterModal(meterId);
        } else {
            throw new Error(response.message || 'Failed to load meter information');
        }
    })
    .catch(error => {
        console.error('Error checking meter status:', error);
        showAlert('Failed to check meter status: ' + error.message, 'error');
    });
};

window.viewMeter = function(meterId) {
    window.location.href = `/meters/${meterId}`;
};

window.returnMeter = function(meterId) {
    Swal.fire({
        title: 'Return Meter',
        text: 'Are you sure you want to return this meter? This will remove its assignment and make it available for new transactions.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#6c757d',
        cancelButtonColor: '#dc3545',
        confirmButtonText: 'Yes, return it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            // Swal.fire({
            //     title: 'Returning Meter...',
            //     text: 'Please wait while we process your request.',
            //     icon: 'info',
            //     allowOutsideClick: false,
            //     allowEscapeKey: false,
            //     showConfirmButton: false,
            //     didOpen: () => {
            //         Swal.showLoading();
            //     }
            // });

            fetch(`/meters/${meterId}/return`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams({
                    '_method': 'PUT'
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Failed to return meter');
                    });
                }
                return response.json();
            })
            .then(response => {
                Swal.fire({
                    title: 'Success!',
                    text: response.message || 'Meter returned successfully!',
                    icon: 'success',
                    confirmButtonColor: '#28a745'
                }).then(() => {
                    // Reload the page to reflect changes
                    window.location.reload();
                });
            })
            .catch(error => {
                console.error('Error returning meter:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Failed to return meter. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
};

window.deleteMeter = function(meterId) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this meter? This action cannot be undone!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
        fetch(`/meters/${meterId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                showAlert('Meter deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                return response.json().then(data => {
                    throw new Error(data.message || 'Failed to delete meter');
                });
            }
        })
        .catch(error => {
            console.error('Error deleting meter:', error);
            showAlert('Failed to delete meter: ' + error.message, 'error');
        });
        }
    });
};







window.refreshMeters = function() {
    window.location.reload();
};





// Utility function to escape HTML (Security best practice)
window.escapeHtml = function(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
};

window.exportMeters = function() {
    showAlert('Export functionality will be implemented soon', 'info');
};



// Audit Logs
window.viewAuditLogs = function(meterId) {
    $('#auditLogsModal').modal('show');
    
    $('#auditLogsContent').html('<div class="text-center p-4"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    
    fetch(`/meters/${meterId}/audit-logs`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Failed to fetch audit logs');
        }
        return response.json();
    })
    .then(response => {
        if (response.success && response.data) {
            displayAuditLogs(response.data);
        } else {
            $('#auditLogsContent').html('<div class="alert alert-warning">No audit logs found</div>');
        }
    })
    .catch(error => {
        console.error('Error loading audit logs:', error);
        $('#auditLogsContent').html('<div class="alert alert-danger">Failed to load audit logs</div>');
    });
};

window.displayAuditLogs = function(audits) {
    if (!audits || audits.length === 0) {
        $('#auditLogsContent').html('<div class="alert alert-info">No audit logs available</div>');
        return;
    }
    
    let html = '<div class="table-responsive"><table class="table table-striped table-sm">';
    html += '<thead class="table-dark"><tr><th>Date</th><th>User</th><th>Event</th><th>Changes</th></tr></thead><tbody>';
    
    audits.forEach(audit => {
        const eventBadge = audit.event === 'created' ? 'bg-success' : 
                          audit.event === 'updated' ? 'bg-warning' : 
                          audit.event === 'deleted' ? 'bg-danger' : 'bg-secondary';
        
        html += `<tr>
            <td><small>${new Date(audit.created_at).toLocaleString()}</small></td>
            <td><small>${audit.user ? audit.user.name : 'System'}</small></td>
            <td><span class="badge ${eventBadge}">${audit.event.toUpperCase()}</span></td>
            <td><small>`;
        
        if (audit.new_values && Object.keys(audit.new_values).length > 0) {
            Object.keys(audit.new_values).forEach(key => {
                html += `<strong>${key}:</strong> ${audit.new_values[key]}<br>`;
            });
        } else {
            html += 'No changes recorded';
        }
        
        html += '</small></td></tr>';
    });
    
    html += '</tbody></table></div>';
    $('#auditLogsContent').html(html);
};

// Utility Functions
window.showAlert = function(message, type = 'info') {
    // Remove existing alerts
    $('.custom-alert').remove();
    
    let alertClass = 'alert-info';
    let iconClass = 'fas fa-info-circle';
    
    switch(type) {
        case 'success':
            alertClass = 'alert-success';
            iconClass = 'fas fa-check-circle';
            break;
        case 'error':
            alertClass = 'alert-danger';
            iconClass = 'fas fa-exclamation-circle';
            break;
        case 'warning':
            alertClass = 'alert-warning';
            iconClass = 'fas fa-exclamation-triangle';
            break;
    }
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed custom-alert" 
             style="top: 20px; right: 20px; z-index: 9999; max-width: 400px; min-width: 300px;">
            <i class="${iconClass}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    $('body').append(alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(function() {
        $('.custom-alert').fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
};

// Document ready initialization
$(document).ready(function() {
    // Set up CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    // Initialize form validation
    initializeValidation();
    
    // Initialize meter brand description functionality
    initializeMeterBrandDescription();
    
    // Simple form-based search - no complex JavaScript needed
    

    

    

    
    // Initialize modal events (Bootstrap 5)
    $('#meterModal').on('hidden.bs.modal', function () {
        $('#meterForm')[0].reset();
        $('.validation-message').text('').removeClass('valid invalid');
    });
    
    // Ensure close buttons work (Bootstrap 5)
    $('#meterModal').on('click', '[data-bs-dismiss="modal"]', function() {
        closeMeterModal();
    });
    
    // Initialize assign meter modal events
    $('#assignMeterModal').on('hidden.bs.modal', function () {
        $('#assignMeterForm')[0].reset();
        $('#meterInfoContent').html('');
        
        // Reset control number fields to default state
        $('#controlNumberTextInput').show();
        $('#controlNumberDropdown').hide();
        $('#assign_control_no_select').html('<option value="">Select Control Number</option>');
        
        // Reset assign button state
        $('#assignMeterBtn').prop('disabled', false);
        $('#assignMeterBtn').html('<i class="fas fa-link"></i> Assign Meter');
        
        // Clear validation states
        $('#assign_control_no_select, #assign_control_no_text').removeClass('is-invalid');
        $('#controlNumberSelectFeedback, #controlNumberTextFeedback').text('');
        
        // Reset account number field to editable state
        $('#assign_account_number').prop('readonly', false).removeClass('bg-light');
        $('#accountNumberHelp').hide();
    });
    
    $('#assignMeterModal').on('click', '[data-bs-dismiss="modal"]', function() {
        closeAssignMeterModal();
    });

    // Handle transaction type change for control number field
    $(document).on('change', '#assign_control_type', function() {
        handleTransactionTypeChange();
    });

    // Handle control number selection change
    $(document).on('change', '#assign_control_no_select', function() {
        handleControlNumberChange();
    });

    // Handle control number text input change/blur for validation
    $(document).on('blur', '#assign_control_no_text', function() {
        const controlNo = $(this).val().trim();
        if (controlNo) {
            validateControlNumber(controlNo);
        }
    });

    $(document).on('input', '#assign_control_no_text', function() {
        // Reset assign button state when user starts typing
        $('#assignMeterBtn').prop('disabled', false);
        $('#assignMeterBtn').html('<i class="fas fa-link"></i> Assign Meter');
    });

});


</script>
@endsection