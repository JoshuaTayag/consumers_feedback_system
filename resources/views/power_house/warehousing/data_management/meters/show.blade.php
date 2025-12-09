@extends('layouts.app')

@section('styles')
<style>
/* Main Container Styles */
.meter-details-container {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 2rem 0;
}

/* Header Card */
.hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    overflow: hidden;
    position: relative;
}

.hero-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="%23ffffff" opacity="0.05"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
}

.hero-content {
    position: relative;
    z-index: 1;
    color: white;
}

.meter-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.meter-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    font-weight: 400;
}

/* Action Buttons */
.action-buttons .btn {
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Modern Cards */
.modern-card {
    border: none;
    border-radius: 24px;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
}

.modern-card-header {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    border: none;
    padding: 1.5rem 2rem;
    color: white;
}

.modern-card-title {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modern-card-body {
    padding: 2rem;
}

/* Detail Items */
.detail-grid {
    display: grid;
    gap: 1.5rem;
}

.detail-item-modern {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border-radius: 16px;
    padding: 1.5rem;
    border-left: 4px solid #4facfe;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.detail-item-modern:hover {
    transform: translateX(5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    border-left-color: #667eea;
}

.detail-label-modern {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    color: #2d3748;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-label-modern i {
    font-size: 1.2rem;
    color: #4facfe;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.detail-value-modern {
    font-size: 1.1rem;
    font-weight: 500;
    color: #1a202c;
    word-break: break-word;
}

/* Audit Timeline Modern */
.audit-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.audit-timeline-modern {
    position: relative;
    padding: 0;
}

.audit-timeline-modern::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #4facfe 0%, #00f2fe 100%);
    border-radius: 2px;
}

.audit-item-modern {
    position: relative;
    margin-bottom: 2rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 1.5rem 1.5rem 1.5rem 3rem;
    margin-left: 2.5rem;
    color: #2d3748;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.audit-item-modern:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.audit-item-modern::before {
    content: '';
    position: absolute;
    left: -47px;
    top: 20px;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    border: 4px solid #ffffff;
    box-shadow: 0 4px 12px rgba(79, 172, 254, 0.4);
    z-index: 2;
}

.audit-item-modern.created::before { 
    background: linear-gradient(135deg, #48bb78, #38a169);
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
}

.audit-item-modern.updated::before { 
    background: linear-gradient(135deg, #ed8936, #dd6b20);
    box-shadow: 0 4px 12px rgba(237, 137, 54, 0.4);
}

.audit-item-modern.deleted::before { 
    background: linear-gradient(135deg, #f56565, #e53e3e);
    box-shadow: 0 4px 12px rgba(245, 101, 101, 0.4);
}

/* Status Badges */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.status-badge.created {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.status-badge.updated {
    background: linear-gradient(135deg, #ed8936, #dd6b20);
    color: white;
    box-shadow: 0 4px 15px rgba(237, 137, 54, 0.3);
}

.status-badge.deleted {
    background: linear-gradient(135deg, #f56565, #e53e3e);
    color: white;
    box-shadow: 0 4px 15px rgba(245, 101, 101, 0.3);
}

/* Changes Table */
.changes-table-modern {
    background: #f7fafc;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 1rem;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.1);
}

.changes-table-modern td {
    padding: 0.75rem 1rem;
    border: none;
    font-size: 0.9rem;
}

.changes-table-modern .field-name {
    font-weight: 600;
    color: #4a5568;
    background: #edf2f7;
    width: 30%;
}

.old-value-modern {
    background: linear-gradient(135deg, #fed7d7, #feb2b2);
    color: #c53030;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 500;
    display: inline-block;
    margin-right: 0.5rem;
}

.new-value-modern {
    background: linear-gradient(135deg, #c6f6d5, #9ae6b4);
    color: #22543d;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-weight: 500;
    display: inline-block;
}

.change-arrow {
    color: #718096;
    margin: 0 0.5rem;
    font-weight: bold;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: rgba(255, 255, 255, 0.8);
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.6;
}

.empty-state p {
    font-size: 1.1rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .meter-title {
        font-size: 2rem;
    }
    
    .modern-card-body {
        padding: 1.5rem;
    }
    
    .detail-item-modern {
        padding: 1rem;
    }
    
    .action-buttons .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}</style>
</style>
@endsection

@section('content')
<div class="meter-details-container">
    <div class="container">
        <div class="row">
            <!-- Modern Header -->
            <div class="col-12 mb-4">
                <div class="card hero-card">
                    <div class="card-body p-4 p-lg-5">
                        <div class="hero-content">
                            <div class="row align-items-center">
                                <div class="col-lg-8">
                                    <h1 class="meter-title">
                                        <i class="fas fa-tachometer-alt me-3"></i>Meter Details
                                    </h1>
                                    <p class="meter-subtitle mb-0">
                                        <i class="fas fa-barcode me-2"></i>{{ $meter->serial_number }} • {{ $meter->meter_brand }}
                                    </p>
                                </div>
                                <div class="col-lg-4 mt-3 mt-lg-0">
                                    <div class="action-buttons d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                                        <a href="{{ route('meters.index') }}" class="btn btn-light">
                                            <i class="fas fa-arrow-left me-2"></i>Back to List
                                        </a>
                                        <a href="{{ route('meters.edit', $meter->id) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>Edit Meter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meter Information -->
            <div class="col-lg-8 mb-4">
                <div class="card modern-card">
                    <div class="card-header modern-card-header">
                        <h2 class="modern-card-title">
                            <i class="fas fa-info-circle"></i>
                            Meter Information
                        </h2>
                    </div>
                    <div class="card-body modern-card-body">
                        <div class="detail-grid">
                            <!-- Essential Information -->
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-industry"></i>
                                    Meter Brand
                                </div>
                                <div class="detail-value-modern">{{ $meter->meter_brand }}</div>
                            </div>
                            
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-barcode"></i>
                                    Serial Number
                                </div>
                                <div class="detail-value-modern">{{ $meter->serial_number }}</div>
                            </div>
                            
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-certificate"></i>
                                    ERC Seal Number
                                </div>
                                <div class="detail-value-modern">{{ $meter->erc_seal_number }}</div>
                            </div>
                            
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-shield-alt"></i>
                                    LEYECO Seal Number
                                </div>
                                <div class="detail-value-modern">{{ $meter->leyeco_seal_number }}</div>
                            </div>
                            
                            <!-- Optional Information -->
                            @if($meter->control_type)
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-cogs"></i>
                                    Control Type
                                </div>
                                <div class="detail-value-modern">{{ $meter->control_type }}</div>
                            </div>
                            @endif
                            
                            @if($meter->control_no)
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-hashtag"></i>
                                    Control Number
                                </div>
                                <div class="detail-value-modern">{{ $meter->control_no }}</div>
                            </div>
                            @endif
                            
                            @if($meter->account_number)
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-user-circle"></i>
                                    Account Number
                                </div>
                                <div class="detail-value-modern">{{ $meter->account_number }}</div>
                            </div>
                            @endif
                            
                            <!-- Timestamps -->
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-calendar-plus"></i>
                                    Date Created
                                </div>
                                <div class="detail-value-modern">{{ $meter->created_at->format('M d, Y h:i A') }}</div>
                            </div>
                            
                            <div class="detail-item-modern">
                                <div class="detail-label-modern">
                                    <i class="fas fa-calendar-edit"></i>
                                    Last Updated
                                </div>
                                <div class="detail-value-modern">{{ $meter->updated_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit History -->
            <div class="col-lg-4">
                <div class="card modern-card">
                    <div class="card-header modern-card-header audit-card">
                        <h2 class="modern-card-title">
                            <i class="fas fa-history"></i>
                            Audit History
                        </h2>
                    </div>
                    <div class="card-body modern-card-body">
                        @if($audits->count() > 0)
                            <div class="audit-timeline-modern">
                                @foreach($audits as $audit)
                                    <div class="audit-item-modern {{ $audit->event }}">
                                        <!-- Header with Badge and Date -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="status-badge {{ $audit->event }}">
                                                <i class="fas fa-{{ $audit->event === 'created' ? 'plus' : ($audit->event === 'updated' ? 'edit' : 'trash') }} me-1"></i>
                                                {{ ucfirst($audit->event) }}
                                            </span>
                                            <small class="text-muted fw-bold">
                                                {{ $audit->created_at->format('M d, h:i A') }}
                                            </small>
                                        </div>
                                        
                                        <!-- User Information -->
                                        <div class="mb-3 p-3 bg-light rounded-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-primary me-2 fs-5"></i>
                                                <div>
                                                    <strong class="d-block">{{ $audit->user->name ?? 'System' }}</strong>
                                                    @if($audit->user_type)
                                                        <small class="text-muted">{{ class_basename($audit->user_type) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Changes or Creation Notice -->
                                        @if($audit->event !== 'created' && !empty($audit->getModified()))
                                            <div class="changes-section">
                                                <h6 class="fw-bold text-primary mb-2">
                                                    <i class="fas fa-exchange-alt me-1"></i>Changes Made:
                                                </h6>
                                                <div class="changes-table-modern">
                                                    <table class="table table-sm mb-0">
                                                        @foreach($audit->getModified() as $field => $values)
                                                            <tr>
                                                                <td class="field-name">{{ ucwords(str_replace('_', ' ', $field)) }}</td>
                                                                <td>
                                                                    @if(isset($values['old']))
                                                                        <span class="old-value-modern">{{ $values['old'] ?? 'null' }}</span>
                                                                        <span class="change-arrow">→</span>
                                                                    @endif
                                                                    <span class="new-value-modern">{{ $values['new'] ?? 'null' }}</span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        @elseif($audit->event === 'created')
                                            <div class="alert alert-success border-0 mb-0" style="background: linear-gradient(135deg, #d4edda, #c3e6cb);">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                <strong>Meter record successfully created</strong>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-history"></i>
                                <p class="mb-0">No audit history available</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection