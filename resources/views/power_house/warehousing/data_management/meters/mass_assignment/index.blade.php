@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h4 class="mb-0"><i class="fas fa-qrcode me-2"></i>Mass Assignment - kWh Meter Requests</h4>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('meters.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back to Meters
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Alert Messages -->
                    <div id="alert-container"></div>

                    <div class="row">
                        <!-- Left Panel: kWh Meter Requests -->
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Available kWh Meter Requests</h5>
                                </div>
                                <div class="card-body">
                                    @if($kwhMeterRequests->isEmpty())
                                        <div class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <h5 class="text-muted">No kWh Meter Requests Available</h5>
                                            <p class="text-muted">All approved requests are either complete or fully liquidated.</p>
                                        </div>
                                    @else
                                        <div class="list-group" id="kwh-requests-list">
                                            @foreach($kwhMeterRequests as $request)
                                                <div class="list-group-item list-group-item-action kwh-request-item" 
                                                     data-request-id="{{ $request['id'] }}"
                                                     data-meter-type-id="{{ $request['meter_type_id'] }}"
                                                     data-remaining="{{ $request['remaining_count'] }}">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">{{ $request['control_no'] }}</h6>
                                                        <small class="badge bg-info">{{ $request['assigned_count'] }}/{{ $request['requested_quantity'] }}</small>
                                                    </div>
                                                    <p class="mb-1">
                                                        <strong>Requested by:</strong> {{ $request['user_name'] }}<br>
                                                        <strong>Meter Type:</strong> {{ $request['meter_type'] }}<br>
                                                        <strong>Purpose:</strong> {{ $request['purpose'] }}<br>
                                                        <strong>Remaining:</strong> <span class="text-danger fw-bold">{{ $request['remaining_count'] }} meters</span>
                                                    </p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Scanner & Assignment -->
                        <div class="col-md-6">
                            <div id="assignment-panel" class="card border-success" style="display: none;">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0"><i class="fas fa-qrcode me-2"></i>Meter Assignment Scanner</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Selected Request Info -->
                                    <div id="selected-request-info" class="alert alert-info mb-3"></div>

                                    <!-- Withdrawn By Selection -->
                                    <div class="mb-3">
                                        <div class="form-floating">
                                            <select class="form-select" id="mass-withdrawn-by" name="withdrawn_by" required>
                                                <option value="">Select Withdrawn By</option>
                                                @foreach($employees as $id => $name)
                                                <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="mass-withdrawn-by">Withdrawn By</label>
                                        </div>
                                        <small class="text-muted">Select who is withdrawing/assigning these meters</small>
                                    </div>

                                    <!-- Scanner Form -->
                    <form id="scanner-form" class="mb-4" onsubmit="return false;">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-barcode"></i>
                            </span>
                            <input type="text" 
                                   id="serial-scanner" 
                                   class="form-control" 
                                   placeholder="Scan or type meter serial number..."
                                   autocomplete="off"
                                   autofocus>
                            <button type="submit" class="btn btn-success" id="assign-btn">
                              <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Focus on this field and scan the meter barcode or type the serial number manually.</small>
                                    </form>

                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Assignment Progress</span>
                                            <span class="text-muted" id="progress-text">0/0</span>
                                        </div>
                                        <div class="progress">
                                            <div id="assignment-progress" 
                                                 class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
                                                 role="progressbar" 
                                                 style="width: 0%">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Assigned Meters List -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-check-circle text-success me-2"></i>Assigned Meters
                                                <span class="badge bg-success" id="assigned-count">0</span>
                                            </h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="assigned-meters-container" class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-hover mb-0">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th width="30%">Serial Number</th>
                                                            <th width="25%">ERC Seal</th>
                                                            <th width="25%">Leyeco Seal</th>
                                                            <th width="15%">Assigned</th>
                                                            <th width="5%">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="assigned-meters-list">
                                                        <tr id="no-assigned-meters">
                                                            <td colspan="5" class="text-center text-muted py-4">
                                                                <i class="fas fa-info-circle me-2"></i>No meters assigned yet
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Instruction Panel -->
                            <div id="instruction-panel" class="card border-secondary">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Instructions</h5>
                                </div>
                                <div class="card-body">
                                    <ol class="mb-0">
                                        <li class="mb-2">Select a kWh meter request from the list on the left</li>
                                        <li class="mb-2">Use the scanner field to scan or type meter serial numbers</li>
                                        <li class="mb-2">The system will automatically validate meter compatibility</li>
                                        <li class="mb-2">Assigned meters will appear in the list below</li>
                                        <li class="mb-0">Complete assignment when all required meters are scanned</li>
                                    </ol>
                                </div>
                            </div>
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
$(document).ready(function() {
    let selectedRequestId = null;
    let selectedRequestData = null;

    // Handle request selection
    $('.kwh-request-item').on('click', function() {
        selectedRequestId = $(this).data('request-id');
        selectedRequestData = {
            id: $(this).data('request-id'),
            meterTypeId: $(this).data('meter-type-id'),
            remaining: $(this).data('remaining'),
            controlNo: $(this).find('h6').text(),
            meterType: $(this).find('p').html().split('<strong>Meter Type:</strong> ')[1].split('<br>')[0],
            requestedBy: $(this).find('p').html().split('<strong>Requested by:</strong> ')[1].split('<br>')[0]
        };

        // Update UI
        $('.kwh-request-item').removeClass('active');
        $(this).addClass('active');
        
        $('#instruction-panel').hide();
        $('#assignment-panel').show();
        
        updateSelectedRequestInfo();
        loadAssignedMeters();
        
        // Focus on scanner
        $('#serial-scanner').focus();
    });

    // Handle scanner form submission
    $('#scanner-form').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const serialNumber = $('#serial-scanner').val().trim();
        const withdrawnBy = $('#mass-withdrawn-by').val();
        
        if (!serialNumber) {
            showAlert('Please enter a meter serial number', 'warning');
            return false;
        }

        if (!withdrawnBy) {
            showAlert('Please select who is withdrawing the meter', 'warning');
            return false;
        }

        assignMeter(serialNumber, withdrawnBy);
        return false;
    });

    // Auto-submit when Enter is pressed (for barcode scanners)
    $('#serial-scanner').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            e.stopPropagation();
            $('#scanner-form').submit();
            return false;
        }
    });

    function assignMeter(serialNumber, withdrawnBy) {
        if (!selectedRequestId) {
            showAlert('Please select a kWh meter request first', 'warning');
            return;
        }

        // Show loading state
        const submitBtn = $('#assign-btn');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i>Assigning...').prop('disabled', true);

        // Also disable the input to prevent multiple submissions
        $('#serial-scanner').prop('disabled', true);

        $.ajax({
            url: '{{ route("meters.massAssignmentAssign") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                kwh_meter_request_id: selectedRequestId,
                serial_number: serialNumber,
                withdrawn_by: withdrawnBy,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('Meter assigned successfully!', 'success');
                    
                    // Clear scanner field
                    $('#serial-scanner').val('').focus();
                    
                    // Update progress
                    updateProgress(response.data.assigned_count, selectedRequestData.remaining + response.data.assigned_count);
                    
                    // Reload assigned meters
                    loadAssignedMeters();
                    
                    // Update request item remaining count
                    const currentRequestItem = $(`.kwh-request-item[data-request-id="${selectedRequestId}"]`);
                    const newRemainingCount = response.data.remaining_count;
                    
                    // Update the remaining count in the selectedRequestData
                    selectedRequestData.remaining = newRemainingCount;
                    
                    // Update the badge and data attributes
                    currentRequestItem.find('.badge').text(response.data.assigned_count + '/' + (newRemainingCount + response.data.assigned_count));
                    currentRequestItem.attr('data-remaining', newRemainingCount);
                    
                    // Update the remaining count text in the list item
                    const remainingText = currentRequestItem.find('p .text-danger.fw-bold');
                    remainingText.text(newRemainingCount + ' meters');
                    
                    // Update the selected request info panel
                    updateSelectedRequestInfo();
                    
                    // Show completion message if done
                    if (response.data.is_complete) {
                        showAlert('kWh meter request assignment completed!', 'success');
                        
                        // Remove completed request from list
                        setTimeout(() => {
                            $(`.kwh-request-item[data-request-id="${selectedRequestId}"]`).fadeOut();
                        }, 2000);
                    }
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                const message = response ? response.message : 'Failed to assign meter';
                showAlert(message, 'error');
            },
            complete: function() {
                submitBtn.html(originalText).prop('disabled', false);
                $('#serial-scanner').prop('disabled', false).focus();
            }
        });
    }

    function loadAssignedMeters() {
        if (!selectedRequestId) return;

        $.ajax({
            url: '{{ route("meters.massAssignmentGetAssignedMeters") }}',
            type: 'GET',
            dataType: 'json',
            data: { kwh_meter_request_id: selectedRequestId },
            success: function(response) {
                if (response.success) {
                    updateAssignedMetersList(response.data);
                }
            },
            error: function() {
                console.error('Failed to load assigned meters');
            }
        });
    }

    function updateAssignedMetersList(meters) {
        const tbody = $('#assigned-meters-list');
        tbody.empty();

        if (meters.length === 0) {
            tbody.append(`
                <tr id="no-assigned-meters">
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-info-circle me-2"></i>No meters assigned yet
                    </td>
                </tr>
            `);
        } else {
            meters.forEach(meter => {
                const statusBadge = meter.status == 0 ? 
                    '<span class="badge bg-warning">Unliquidated</span>' :
                    '<span class="badge bg-success">Liquidated</span>';

                tbody.append(`
                    <tr>
                        <td><strong>${meter.serial_number}</strong></td>
                        <td>${meter.erc_seal || 'N/A'}</td>
                        <td>${meter.leyeco_seal || 'N/A'}</td>
                        <td>
                            <small class="text-muted">${meter.assigned_at}</small><br>
                            ${statusBadge}
                        </td>
                        <td>
                            ${meter.status == 0 ? 
                                `<button class="btn btn-danger btn-sm remove-meter" data-assignment-id="${meter.id}">
                                    <i class="fas fa-trash"></i>
                                </button>` : 
                                '<small class="text-muted">Liquidated</small>'
                            }
                        </td>
                    </tr>
                `);
            });
        }

        $('#assigned-count').text(meters.length);
        updateProgress(meters.length, selectedRequestData.remaining + meters.length);
    }

    // Handle meter removal
    $(document).on('click', '.remove-meter', function() {
        const assignmentId = $(this).data('assignment-id');
        
        if (confirm('Are you sure you want to remove this meter assignment?')) {
            removeMeterAssignment(assignmentId);
        }
    });

    function removeMeterAssignment(assignmentId) {
        $.ajax({
            url: '{{ route("meters.massAssignmentRemove") }}',
            type: 'DELETE',
            dataType: 'json',
            data: {
                assignment_id: assignmentId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showAlert('Meter assignment removed successfully!', 'success');
                    
                    // Reload assigned meters list
                    loadAssignedMeters();
                    
                    // Update counts and progress
                    if (response.data) {
                        const newAssignedCount = response.data.assigned_count;
                        const totalQuantity = selectedRequestData.remaining + newAssignedCount;
                        
                        // Update progress
                        updateProgress(newAssignedCount, totalQuantity);
                        
                        // Update request item badge and data
                        const currentRequestItem = $(`.kwh-request-item[data-request-id="${selectedRequestId}"]`);
                        currentRequestItem.find('.badge').text(newAssignedCount + '/' + totalQuantity);
                        
                        // Update remaining count in selectedRequestData
                        selectedRequestData.remaining = response.data.remaining_count;
                        currentRequestItem.attr('data-remaining', response.data.remaining_count);
                        
                        // Update remaining count text in the list item
                        const remainingText = currentRequestItem.find('p .text-danger.fw-bold');
                        remainingText.text(response.data.remaining_count + ' meters');
                        
                        // Update selected request info panel
                        updateSelectedRequestInfo();
                    }
                } else {
                    showAlert(response.message, 'error');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                const message = response ? response.message : 'Failed to remove assignment';
                showAlert(message, 'error');
            }
        });
    }

    function updateSelectedRequestInfo() {
        $('#selected-request-info').html(`
            <strong>Selected Request:</strong> ${selectedRequestData.controlNo}<br>
            <strong>Requested by:</strong> ${selectedRequestData.requestedBy}<br>
            <strong>Meter Type:</strong> ${selectedRequestData.meterType}<br>
            <strong>Remaining to assign:</strong> <span class="text-danger fw-bold">${selectedRequestData.remaining} meters</span>
        `);
    }

    function updateProgress(assigned, total) {
        const percentage = total > 0 ? Math.round((assigned / total) * 100) : 0;
        $('#assignment-progress').css('width', percentage + '%');
        $('#progress-text').text(`${assigned}/${total}`);
        
        if (assigned >= total) {
            $('#assignment-progress').removeClass('progress-bar-animated');
        }
    }

    function showAlert(message, type) {
        const alertClass = type === 'error' ? 'danger' : type;
        const iconClass = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';
        
        const alert = $(`
            <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas fa-${iconClass} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('#alert-container').prepend(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
        
        // Scroll to top to show alert
        $('html, body').animate({ scrollTop: 0 }, 300);
    }
});
</script>
@endsection

@section('style')
<style>
.kwh-request-item {
    cursor: pointer;
    transition: all 0.2s ease;
}

.kwh-request-item:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd;
}

.kwh-request-item.active {
    background-color: #cff4fc;
    color: #055160;
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    position: relative;
}

.kwh-request-item.active::before {
    content: "SELECTED";
    position: absolute;
    top: -8px;
    right: -8px;
    background: #0d6efd;
    color: white;
    font-size: 0.7rem;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 3px;
    z-index: 10;
}

#serial-scanner {
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    font-weight: bold;
}

.table-responsive {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.progress-bar-animated {
    animation: progress-bar-stripes 1s linear infinite;
}

@keyframes progress-bar-stripes {
    0% {
        background-position: 1rem 0;
    }
    100% {
        background-position: 0 0;
    }
}

.sticky-top {
    position: sticky;
    top: 0;
    z-index: 10;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.card-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.remove-meter {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endsection