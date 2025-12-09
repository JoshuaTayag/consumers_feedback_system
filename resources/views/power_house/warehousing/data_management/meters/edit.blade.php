@extends('layouts.app')

@section('styles')
<style>
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

.form-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header form-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0">
                                <i class="fas fa-edit"></i> Edit Meter
                            </h4>
                            <small class="opacity-75">Serial: {{ $meter->serial_number }}</small>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a class="btn btn-secondary btn-sm me-2" href="{{ route('meters.show', $meter->id) }}">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a class="btn btn-warning btn-sm" href="{{ route('meters.index') }}">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('meters.update', $meter->id) }}" method="POST" id="meterForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Meter Brand -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select @error('meter_brand') is-invalid @enderror" 
                                            id="meter_brand" name="meter_brand" required>
                                        <option value="">Select Meter Brand</option>
                                        <option value="Schneider" {{ old('meter_brand', $meter->meter_brand) == 'Schneider' ? 'selected' : '' }}>Schneider</option>
                                        <option value="ABB" {{ old('meter_brand', $meter->meter_brand) == 'ABB' ? 'selected' : '' }}>ABB</option>
                                        <option value="Siemens" {{ old('meter_brand', $meter->meter_brand) == 'Siemens' ? 'selected' : '' }}>Siemens</option>
                                        <option value="General Electric" {{ old('meter_brand', $meter->meter_brand) == 'General Electric' ? 'selected' : '' }}>General Electric</option>
                                        <option value="Elster" {{ old('meter_brand', $meter->meter_brand) == 'Elster' ? 'selected' : '' }}>Elster</option>
                                        <option value="Landis+Gyr" {{ old('meter_brand', $meter->meter_brand) == 'Landis+Gyr' ? 'selected' : '' }}>Landis+Gyr</option>
                                        <option value="Other" {{ old('meter_brand', $meter->meter_brand) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <label for="meter_brand"><i class="fas fa-industry"></i> Meter Brand *</label>
                                    @error('meter_brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Serial Number -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" 
                                           placeholder="Enter serial number" 
                                           value="{{ old('serial_number', $meter->serial_number) }}" required>
                                    <label for="serial_number"><i class="fas fa-barcode"></i> Serial Number *</label>
                                    @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="serial_validation" class="validation-message"></div>
                            </div>
                        </div>

                        <!-- ERC Seal Number -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('erc_seal_number') is-invalid @enderror" 
                                           id="erc_seal_number" name="erc_seal_number" 
                                           placeholder="Enter ERC seal number" 
                                           value="{{ old('erc_seal_number', $meter->erc_seal_number) }}" required>
                                    <label for="erc_seal_number"><i class="fas fa-certificate"></i> ERC Seal Number *</label>
                                    @error('erc_seal_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="erc_validation" class="validation-message"></div>
                            </div>
                        </div>

                        <!-- LEYECO Seal Number (Input Only) -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('leyeco_seal_number') is-invalid @enderror" 
                                           id="leyeco_seal_number" name="leyeco_seal_number" 
                                           placeholder="Enter LEYECO seal number" 
                                           value="{{ old('leyeco_seal_number', $meter->leyeco_seal_number) }}" required>
                                    <label for="leyeco_seal_number"><i class="fas fa-seal"></i> LEYECO Seal Number *</label>
                                    @error('leyeco_seal_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Optional Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('control_type') is-invalid @enderror" 
                                            id="control_type" name="control_type">
                                        <option value="">Select Control Type</option>
                                        <option value="Single Phase" {{ old('control_type', $meter->control_type) == 'Single Phase' ? 'selected' : '' }}>Single Phase</option>
                                        <option value="Three Phase" {{ old('control_type', $meter->control_type) == 'Three Phase' ? 'selected' : '' }}>Three Phase</option>
                                        <option value="CT Metered" {{ old('control_type', $meter->control_type) == 'CT Metered' ? 'selected' : '' }}>CT Metered</option>
                                    </select>
                                    <label for="control_type"><i class="fas fa-cogs"></i> Control Type</label>
                                    @error('control_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('control_no') is-invalid @enderror" 
                                           id="control_no" name="control_no" 
                                           placeholder="Enter control number" 
                                           value="{{ old('control_no', $meter->control_no) }}">
                                    <label for="control_no"><i class="fas fa-hashtag"></i> Control Number</label>
                                    @error('control_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('account_number') is-invalid @enderror" 
                                           id="account_number" name="account_number" 
                                           placeholder="Enter account number" 
                                           value="{{ old('account_number', $meter->account_number) }}">
                                    <label for="account_number"><i class="fas fa-user"></i> Account Number</label>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                            <i class="fas fa-trash"></i> Delete Meter
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('meters.show', $meter->id) }}" class="btn btn-secondary me-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Meter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Hidden Delete Form -->
                    <form id="deleteForm" action="{{ route('meters.destroy', $meter->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    initializeValidation();
});

function initializeValidation() {
    $('#serial_number').on('blur', function() {
        const value = $(this).val();
        const currentSerial = '{{ $meter->serial_number }}';
        if (value && value !== currentSerial) {
            validateField('serial_number', value, {{ $meter->id }});
        }
    });
    
    $('#erc_seal_number').on('blur', function() {
        const value = $(this).val();
        const currentErc = '{{ $meter->erc_seal_number }}';
        if (value && value !== currentErc) {
            validateField('erc_seal_number', value, {{ $meter->id }});
        }
    });
}

function validateField(fieldType, value, excludeId = null) {
    let endpoint = '';
    
    if (fieldType === 'serial_number') {
        endpoint = '/api/meters/validate-serial';
    } else if (fieldType === 'erc_seal_number') {
        endpoint = '/api/meters/validate-erc-seal';
    } else {
        return;
    }
    
    const data = {
        [fieldType]: value,
        _token: $('meta[name="csrf-token"]').attr('content')
    };
    
    if (excludeId) {
        data.exclude_id = excludeId;
    }
    
    $.post(endpoint, data)
    .done(function(response) {
        const messageElement = $(`#${fieldType.replace('_number', '')}_validation`);
        if (response.valid) {
            messageElement.text('✓ ' + response.message).removeClass('invalid').addClass('valid');
        } else {
            messageElement.text('✗ ' + response.message).removeClass('valid').addClass('invalid');
        }
    });
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete this meter? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection