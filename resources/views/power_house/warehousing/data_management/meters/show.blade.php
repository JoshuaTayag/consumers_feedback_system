@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Header Section -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-0">
                                <i class="fas fa-tachometer-alt me-2"></i>Meter Details
                            </h4>
                            <small class="opacity-75">
                                <i class="fas fa-barcode me-1"></i>{{ $meter->serial_number }} • {{ $meter->meterType->meter_brand ?? 'N/A' }}
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('meters.index') }}" class="btn btn-light btn-sm me-2">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                            @if($meter->status == 0)
                                <a href="{{ route('meters.edit', $meter->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit Meter
                                </a>
                            @else
                                <button class="btn btn-secondary btn-sm" disabled title="Cannot edit assigned meter">
                                    <i class="fas fa-edit me-1"></i>Edit Meter
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meter Status Card -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-1">
                                <i class="fas fa-info-circle me-2"></i>Meter Status
                            </h5>
                            @if($meter->status == 1)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>Assigned
                                </span>
                                <small class="text-muted ms-2">This meter is currently assigned to a transaction</small>
                            @else
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-clock me-1"></i>Available
                                </span>
                                <small class="text-muted ms-2">This meter is ready for assignment</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="col-lg-8 mb-4">
            <!-- Basic Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-tachometer-alt me-2"></i>Basic Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-industry me-1"></i>Meter Brand
                            </label>
                            <p class="fs-5 mb-0">{{ $meter->meterType->meter_brand ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-code me-1"></i>Meter Code
                            </label>
                            <p class="fs-5 mb-0">{{ $meter->meterType->meter_code ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-barcode me-1"></i>Serial Number
                            </label>
                            <p class="fs-5 mb-0"><code class="bg-light p-2 rounded">{{ $meter->serial_number }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-info-circle me-1"></i>Description
                            </label>
                            <p class="fs-6 mb-0">{{ $meter->meterType->meter_description ?? 'No description available' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seal Information Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-shield-alt me-2"></i>Seal Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-certificate me-1"></i>ERC Seal Number
                            </label>
                            <p class="fs-5 mb-0"><code class="bg-light p-2 rounded">{{ $meter->erc_seal_number }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-seal me-1"></i>LEYECO Seal Number
                            </label>
                            <p class="fs-5 mb-0"><code class="bg-light p-2 rounded">{{ $meter->leyeco_seal_number }}</code></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignment Information Card (Only if assigned) -->
            @if($meter->status == 1)
            <div class="card shadow-sm border-success mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-link me-2"></i>Assignment Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @if($meter->control_type)
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-cogs me-1"></i>Transaction Type
                            </label>
                            <p class="fs-5 mb-0">
                                <span class="badge bg-primary">{{ $meter->control_type }}</span>
                            </p>
                        </div>
                        @endif
                        
                        @if($meter->control_no)
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-hashtag me-1"></i>Control Number
                            </label>
                            <p class="fs-5 mb-0"><code class="bg-light p-2 rounded">{{ $meter->control_no }}</code></p>
                        </div>
                        @endif
                        
                        @if($meter->account_number)
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-user me-1"></i>Account Number
                            </label>
                            <p class="fs-5 mb-0"><code class="bg-light p-2 rounded">{{ $meter->account_number }}</code></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Timestamps Card -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        <i class="fas fa-clock me-2"></i>Timestamp Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-calendar-plus me-1"></i>Created Date
                            </label>
                            <p class="fs-6 mb-0">{{ $meter->created_at->format('F d, Y') }}</p>
                            <small class="text-muted">{{ $meter->created_at->format('h:i A') }}</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">
                                <i class="fas fa-calendar-edit me-1"></i>Last Updated
                            </label>
                            <p class="fs-6 mb-0">{{ $meter->updated_at->format('F d, Y') }}</p>
                            <small class="text-muted">{{ $meter->updated_at->format('h:i A') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Audit History Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 1rem;">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-history me-2"></i>Audit History
                    </h5>
                </div>
                <div class="card-body" style="max-height: 70vh; overflow-y: auto;">
                    @if($audits->count() > 0)
                        <div class="timeline">
                            @foreach($audits as $audit)
                                <div class="timeline-item mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        @php
                                            $badgeClass = $audit->event === 'created' ? 'bg-success' : 
                                                         ($audit->event === 'updated' ? 'bg-warning' : 'bg-danger');
                                            $icon = $audit->event === 'created' ? 'plus' : 
                                                   ($audit->event === 'updated' ? 'edit' : 'trash');
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            <i class="fas fa-{{ $icon }} me-1"></i>
                                            {{ ucfirst($audit->event) }}
                                        </span>
                                        <small class="text-muted fw-bold">
                                            {{ $audit->created_at->format('M d, h:i A') }}
                                        </small>
                                    </div>
                                    
                                    <!-- User Information -->
                                    <div class="bg-light rounded p-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-primary me-2"></i>
                                            <div>
                                                <strong class="small">{{ $audit->user->name ?? 'System' }}</strong>
                                                @if($audit->user_type)
                                                    <br><small class="text-muted">{{ class_basename($audit->user_type) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Changes Information -->
                                    @if($audit->event !== 'created' && !empty($audit->getModified()))
                                        <div class="changes-section">
                                            <small class="fw-bold text-primary d-block mb-1">
                                                <i class="fas fa-exchange-alt me-1"></i>Changes:
                                            </small>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-borderless mb-0">
                                                    @foreach($audit->getModified() as $field => $values)
                                                        <tr>
                                                            <td class="text-muted small py-1" style="width: 40%;">
                                                                {{ ucwords(str_replace('_', ' ', $field)) }}
                                                            </td>
                                                            <td class="py-1">
                                                                @if(isset($values['old']))
                                                                    <span class="badge bg-light text-dark small">{{ $values['old'] ?? 'null' }}</span>
                                                                    <i class="fas fa-arrow-right text-muted mx-1"></i>
                                                                @endif
                                                                <span class="badge bg-primary small">{{ $values['new'] ?? 'null' }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            </div>
                                        </div>
                                    @elseif($audit->event === 'created')
                                        <div class="alert alert-success py-2 mb-0">
                                            <i class="fas fa-plus-circle me-1"></i>
                                            <small><strong>Meter record created</strong></small>
                                        </div>
                                    @endif
                                    
                                    @if(!$loop->last)
                                        <hr class="my-3">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-history text-muted fa-2x mb-2"></i>
                            <p class="text-muted mb-0">No audit history available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection