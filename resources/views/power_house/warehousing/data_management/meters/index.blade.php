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
                                    <select class="form-select" id="meter_brand" name="meter_brand" required>
                                        <option value="">Select Meter Brand</option>
                                        <option value="Schneider">Schneider</option>
                                        <option value="ABB">ABB</option>
                                        <option value="Siemens">Siemens</option>
                                        <option value="General Electric">General Electric</option>
                                        <option value="Elster">Elster</option>
                                        <option value="Landis+Gyr">Landis+Gyr</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <label for="meter_brand"><i class="fas fa-industry"></i> Meter Brand *</label>
                                </div>
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

                        <!-- Optional Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select" id="control_type" name="control_type">
                                        <option value="">Select Control Type</option>
                                        <option value="Single Phase">Single Phase</option>
                                        <option value="Three Phase">Three Phase</option>
                                        <option value="CT Metered">CT Metered</option>
                                    </select>
                                    <label for="control_type"><i class="fas fa-cogs"></i> Control Type</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="control_no" name="control_no" 
                                           placeholder="Enter control number" readonly>
                                    <label for="control_no"><i class="fas fa-hashtag"></i> Control Number</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="account_number" name="account_number" 
                                           placeholder="Enter account number" readonly>
                                    <label for="account_number"><i class="fas fa-user"></i> Account Number</label>
                                </div>
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
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Search by brand, serial number, seal numbers, control number, or account number..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="button" onclick="searchMeters()">
                                    <i class="fas fa-search"></i> Search
                                </button>
                                <button class="btn btn-outline-danger" type="button" onclick="clearSearch()" 
                                        id="clearSearchBtn" style="display: none;">
                                    <i class="fas fa-times"></i> Clear
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-outline-primary" onclick="exportMeters()">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                        </div>
                    </div>

                    <!-- Meters Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-industry"></i> Meter Brand</th>
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
                                        <span class="badge bg-info">{{ $meter->meter_brand }}</span>
                                    </td>
                                    <td>
                                        <code>{{ $meter->serial_number }}</code>
                                    </td>
                                    <td>
                                        <code>{{ $meter->leyeco_seal_number }}</code>
                                    </td>
                                    <td>
                                        <code>{{ $meter->erc_seal_number }}</code>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $meter->control_type ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <code>{{ $meter->control_no ?: '-' }}</code>
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
                                            <button type="button" class="btn btn-outline-warning" 
                                                    onclick="editMeter({{ $meter->id }})" title="Edit Meter">
                                                <i class="fas fa-edit"></i>
                                            </button>
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
                    <div class="d-flex justify-content-center">
                        {{ $meters->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Modal -->
    <div class="modal fade" id="auditLogsModal" tabindex="-1" aria-labelledby="auditLogsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-secondary text-white">
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
            $('#meter_brand').val(meter.meter_brand);
            $('#serial_number').val(meter.serial_number);
            $('#erc_seal_number').val(meter.erc_seal_number);
            $('#leyeco_seal_number').val(meter.leyeco_seal_number);
            $('#control_type').val(meter.control_type || '');
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

// CRUD Functions
window.editMeter = function(meterId) {
    openMeterModal(meterId);
};

window.viewMeter = function(meterId) {
    window.location.href = `/meters/${meterId}`;
};

window.deleteMeter = function(meterId) {
    if (confirm('Are you sure you want to delete this meter? This action cannot be undone.')) {
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
};

window.searchMeters = function() {
    const searchTerm = $('#searchInput').val().trim();
    
    // Show loading state
    $('#metersTableBody').html(`
        <tr>
            <td colspan="9" class="text-center py-4">
                <div class="spinner-border spinner-border-sm me-2" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                Searching meters...
            </td>
        </tr>
    `);
    
    // Make AJAX request to search
    fetch(`/meters/search?search=${encodeURIComponent(searchTerm)}`, {
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
            displaySearchResults(response.data);
        } else {
            throw new Error(response.message || 'Search failed');
        }
    })
    .catch(error => {
        console.error('Error searching meters:', error);
        $('#metersTableBody').html(`
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Error searching meters: ${error.message}
                    </div>
                </td>
            </tr>
        `);
        showAlert('Search failed: ' + error.message, 'error');
    });
};

window.displaySearchResults = function(meters) {
    let html = '';
    
    if (meters && meters.length > 0) {
        meters.forEach(meter => {
            html += `
                <tr class="meter-row" data-meter-id="${meter.id}">
                    <td>
                        <span class="badge bg-info">${meter.meter_brand}</span>
                    </td>
                    <td>
                        <code>${meter.serial_number}</code>
                    </td>
                    <td>
                        <code>${meter.leyeco_seal_number}</code>
                    </td>
                    <td>
                        <code>${meter.erc_seal_number}</code>
                    </td>
                    <td>
                        <span class="badge bg-secondary">${meter.control_type || 'N/A'}</span>
                    </td>
                    <td>
                        <code>${meter.control_no || '-'}</code>
                    </td>
                    <td>
                        <span class="text-muted">${meter.account_number || 'N/A'}</span>
                    </td>
                    <td>
                        <small class="text-muted">${new Date(meter.created_at).toLocaleDateString('en-US', {
                            month: 'short',
                            day: '2-digit',
                            year: 'numeric'
                        })}</small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" 
                                    onclick="viewMeter(${meter.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-outline-warning" 
                                    onclick="editMeter(${meter.id})" title="Edit Meter">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info" 
                                    onclick="viewAuditLogs(${meter.id})" title="View Audit Logs">
                                <i class="fas fa-history"></i>
                            </button>
                            <button type="button" class="btn btn-outline-danger" 
                                    onclick="deleteMeter(${meter.id})" title="Delete Meter">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
    } else {
        html = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <p>No meters found matching your search criteria.</p>
                        <button type="button" class="btn btn-primary btn-sm" onclick="clearSearch()">
                            <i class="fas fa-times"></i> Clear Search
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }
    
    $('#metersTableBody').html(html);
};

window.clearSearch = function() {
    $('#searchInput').val('');
    $('#clearSearchBtn').hide();
    window.location.reload();
};

window.refreshMeters = function() {
    window.location.reload();
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
    
    // Search functionality
    let searchTimeout;
    
    // Search on Enter key
    $('#searchInput').on('keypress', function(e) {
        if (e.which === 13) {
            searchMeters();
        }
    });
    
    // Show/hide clear button based on input content
    $('#searchInput').on('input', function() {
        const value = $(this).val().trim();
        if (value.length > 0) {
            $('#clearSearchBtn').show();
            
            // Debounced search (search after 500ms of no typing)
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                if ($('#searchInput').val().trim().length >= 2) {
                    searchMeters();
                }
            }, 500);
        } else {
            $('#clearSearchBtn').hide();
            clearTimeout(searchTimeout);
        }
    });
    
    // Show clear button if there's initial search value
    if ($('#searchInput').val().trim().length > 0) {
        $('#clearSearchBtn').show();
    }
    
    // Initialize modal events (Bootstrap 5)
    $('#meterModal').on('hidden.bs.modal', function () {
        $('#meterForm')[0].reset();
        $('.validation-message').text('').removeClass('valid invalid');
    });
    
    // Ensure close buttons work (Bootstrap 5)
    $('#meterModal').on('click', '[data-bs-dismiss="modal"]', function() {
        closeMeterModal();
    });
    

});


</script>
@endsection