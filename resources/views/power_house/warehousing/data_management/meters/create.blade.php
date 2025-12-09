@extends('layouts.app')

@section('styles')
<style>
.barcode-scanner {
    border: 2px dashed #007bff;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
}

.barcode-scanner:hover {
    background-color: #e9ecef;
    border-color: #0056b3;
}

.barcode-scanner.active {
    border-color: #28a745;
    background-color: #d4edda;
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

.scanner-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.8);
    z-index: 9999;
    display: none;
}

.scanner-modal {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 10px;
    padding: 20px;
    max-width: 90%;
    max-height: 90%;
}
</style>
@endsection

@section('content')
<div class="container">
    <!-- Barcode Scanner Overlay -->
    <div id="scannerOverlay" class="scanner-overlay">
        <div class="scanner-modal">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="fas fa-camera"></i> Barcode Scanner</h5>
                <button type="button" class="btn btn-danger btn-sm" onclick="stopBarcodeScanner()">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
            <div id="scanner-container" style="height: 300px;"></div>
            <div class="text-center mt-2">
                <small class="text-muted">Position the barcode within the frame</small>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0">
                                <i class="fas fa-plus-circle"></i> Add New Meter
                            </h4>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a class="btn btn-light btn-sm" href="{{ route('meters.index') }}">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('meters.store') }}" method="POST" id="meterForm">
                        @csrf
                        
                        <!-- Meter Brand -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-select @error('meter_brand') is-invalid @enderror" 
                                            id="meter_brand" name="meter_brand" required>
                                        <option value="">Select Meter Brand</option>
                                        <option value="Schneider" {{ old('meter_brand') == 'Schneider' ? 'selected' : '' }}>Schneider</option>
                                        <option value="ABB" {{ old('meter_brand') == 'ABB' ? 'selected' : '' }}>ABB</option>
                                        <option value="Siemens" {{ old('meter_brand') == 'Siemens' ? 'selected' : '' }}>Siemens</option>
                                        <option value="General Electric" {{ old('meter_brand') == 'General Electric' ? 'selected' : '' }}>General Electric</option>
                                        <option value="Elster" {{ old('meter_brand') == 'Elster' ? 'selected' : '' }}>Elster</option>
                                        <option value="Landis+Gyr" {{ old('meter_brand') == 'Landis+Gyr' ? 'selected' : '' }}>Landis+Gyr</option>
                                        <option value="Other" {{ old('meter_brand') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <label for="meter_brand"><i class="fas fa-industry"></i> Meter Brand *</label>
                                    @error('meter_brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Serial Number with Barcode Scanner -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="fas fa-barcode"></i> Serial Number *
                                </label>
                                <div class="input-group">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                               id="serial_number" name="serial_number" 
                                               placeholder="Scan or enter serial number" 
                                               value="{{ old('serial_number') }}" required>
                                        <label for="serial_number">Serial Number</label>
                                        @error('serial_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="startBarcodeScanner('serial_number')">
                                        <i class="fas fa-camera"></i> Scan
                                    </button>
                                </div>
                                <div id="serial_validation" class="validation-message"></div>
                            </div>
                        </div>

                        <!-- ERC Seal Number with Barcode Scanner -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <label class="form-label">
                                    <i class="fas fa-certificate"></i> ERC Seal Number *
                                </label>
                                <div class="input-group">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('erc_seal_number') is-invalid @enderror" 
                                               id="erc_seal_number" name="erc_seal_number" 
                                               placeholder="Scan or enter ERC seal number" 
                                               value="{{ old('erc_seal_number') }}" required>
                                        <label for="erc_seal_number">ERC Seal Number</label>
                                        @error('erc_seal_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="button" class="btn btn-outline-primary" onclick="startBarcodeScanner('erc_seal_number')">
                                        <i class="fas fa-camera"></i> Scan
                                    </button>
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
                                           value="{{ old('leyeco_seal_number') }}" required>
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
                                        <option value="Single Phase" {{ old('control_type') == 'Single Phase' ? 'selected' : '' }}>Single Phase</option>
                                        <option value="Three Phase" {{ old('control_type') == 'Three Phase' ? 'selected' : '' }}>Three Phase</option>
                                        <option value="CT Metered" {{ old('control_type') == 'CT Metered' ? 'selected' : '' }}>CT Metered</option>
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
                                           value="{{ old('control_no') }}">
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
                                           value="{{ old('account_number') }}">
                                    <label for="account_number"><i class="fas fa-user"></i> Account Number</label>
                                    @error('account_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-end">
                                <a href="{{ route('meters.index') }}" class="btn btn-secondary me-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Meter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/@zxing/library@latest"></script>
<script>
let currentScannerId = null;
let codeReader = null;

$(document).ready(function() {
    initializeValidation();
});

function initializeValidation() {
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
}

function validateField(fieldType, value) {
    let endpoint = '';
    
    if (fieldType === 'serial_number') {
        endpoint = '/api/meters/validate-serial';
    } else if (fieldType === 'erc_seal_number') {
        endpoint = '/api/meters/validate-erc-seal';
    } else {
        return;
    }
    
    $.post(endpoint, {
        [fieldType]: value,
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        const messageElement = $(`#${fieldType.replace('_number', '')}_validation`);
        if (response.valid) {
            messageElement.text('✓ ' + response.message).removeClass('invalid').addClass('valid');
        } else {
            messageElement.text('✗ ' + response.message).removeClass('valid').addClass('invalid');
        }
    });
}

function startBarcodeScanner(fieldId) {
    currentScannerId = fieldId;
    $('#scannerOverlay').show();
    
    codeReader = new ZXing.BrowserMultiFormatReader();
    
    codeReader.getVideoInputDevices()
        .then((videoInputDevices) => {
            if (videoInputDevices.length > 0) {
                const selectedDeviceId = videoInputDevices[0].deviceId;
                
                codeReader.decodeFromVideoDevice(selectedDeviceId, 'scanner-container', (result, err) => {
                    if (result) {
                        $(`#${currentScannerId}`).val(result.text);
                        validateField(currentScannerId, result.text);
                        stopBarcodeScanner();
                        alert('Barcode scanned successfully!');
                    }
                });
            } else {
                alert('No camera devices found');
                stopBarcodeScanner();
            }
        })
        .catch((err) => {
            alert('Failed to access camera: ' + err.message);
            stopBarcodeScanner();
        });
}

function stopBarcodeScanner() {
    if (codeReader) {
        codeReader.reset();
        codeReader = null;
    }
    $('#scannerOverlay').hide();
    $('#scanner-container').empty();
    currentScannerId = null;
}
</script>
@endsection