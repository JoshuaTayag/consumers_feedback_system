{{-- This view is specifically designed for modal content - no layout wrapper --}}

@if(isset($error))
    {{-- Error State --}}
    <div class="alert alert-danger">
        <h5><i class="fas fa-exclamation-triangle me-2"></i>Error Loading Details</h5>
        <p>{{ $error }}</p>
    </div>
@elseif($type === 'kwh_meter_request' && $data)
    {{-- KWH Meter Request Details --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2 text-warning"></i>KWH Meter Request Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Control No.:</strong></td>
                                            <td class="text-danger fw-bold">{{ $data->control_no ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Requested By:</strong></td>
                                            <td>{{ $data->user->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Meter Type:</strong></td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $data->meterType->meter_code ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Quantity:</strong></td>
                                            <td>
                                                <span class="badge bg-info">{{ $data->quantity ?? 0 }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Request Date:</strong></td>
                                            <td>{{ $data->created_at ? $data->created_at->format('M d, Y H:i A') : 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($data->is_liquidated)
                                                    <span class="badge bg-success">Liquidated</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Not Liquidated</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Approved By:</strong></td>
                                            <td>{{ $data->approvedBy->name ?? 'Pending' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created By:</strong></td>
                                            <td>{{ $data->created_by ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td>{{ $data->updated_at ? $data->updated_at->format('M d, Y H:i A') : 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if($data->purpose)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><i class="fas fa-clipboard me-2"></i>Purpose:</h6>
                                <textarea name="purpose" id="purpose" class="form-control" rows="3" readonly>{{ $data->purpose }}</textarea>
                            </div>
                        </div>
                        @endif

                        {{-- Serial Numbers Section --}}
                        @if($data->kwhMeterRequestSerialNumbers && $data->kwhMeterRequestSerialNumbers->count() > 0)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6><i class="fas fa-list me-2"></i>Assigned Meters:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No.</th>
                                                <th>CM Control No.</th>
                                                <th>Old Meter No.</th>
                                                <th>New Meter No.</th>
                                                {{-- <th>Leyeco Seal No.</th>
                                                <th>ERC Seal No.</th> --}}
                                                <th>Date Installed</th>
                                                <th>Status</th>
                                                <th>Acknowledged by</th>
                                                <th>Signature</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data->kwhMeterRequestSerialNumbers as $assignment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $assignment->changeMeterRequest->control_no ?? 'N/A' }}</td>
                                                <td class="text-danger fw-bold">{{ $assignment->changeMeterRequest->old_meter_no ?? 'N/A' }}</td>
                                                <td>{{ $assignment->meter->serial_number ?? 'N/A' }}</td>
                                                {{-- <td>{{ $assignment->meter->leyeco_seal_number ?? 'N/A' }}</td>
                                                <td>{{ $assignment->meter->erc_seal_number ?? 'N/A' }}</td> --}}
                                                <td>{{ $assignment->changeMeterRequest->date_time_acted ? $assignment->changeMeterRequest->date_time_acted->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    <span class="badge {{ $assignment->status == 0 ? 'bg-warning' : ($assignment->changeMeterRequest->status == 2 ? 'bg-success' : ($assignment->changeMeterRequest->status == 1 ? 'bg-danger' : 'bg-warning')) }}">{{ $assignment->status == 0 &&  !$assignment->change_meter_request_id ? 'Unassigned' : ($assignment->changeMeterRequest->status == 2 ? 'Installed' : ($assignment->changeMeterRequest->status == 1 ? 'Acted-Not Completed' : 'Unacted')) }}</span>
                                                </td>
                                                <td>{{ $assignment->changeMeterRequest->customerSignature->signatory_name ?? 'N/A' }}</td>
                                                <td class="signature-cell">
                                                    @php
                                                        $customerSignature = $assignment->changeMeterRequest->customerSignature;
                                                    @endphp

                                                    @if($customerSignature?->signature_image_url)
                                                        <img src="{{ $customerSignature->signature_image_url }}"
                                                            alt="{{ $customerSignature->signatory_name ?? 'Signature' }}"
                                                            class="signature-image"
                                                            style="max-height: 40px; max-width: 50px; display: block; margin: 0 auto;">
                                                    @else
                                                        <small>No Signature</small>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif($type === 'generic' && $pending)
    {{-- Generic Pending Transaction Details --}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-file-alt me-2"></i>Transaction Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Transaction:</strong></td>
                                            <td>{{ $pending->transaction }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Table Name:</strong></td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $pending->table_name ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Table ID:</strong></td>
                                            <td>{{ $pending->table_id ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                @if($pending->status == 1)
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($pending->status == 2)
                                                    <span class="badge bg-danger">Disapproved</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Created:</strong></td>
                                            <td>{{ $pending->created_at ? $pending->created_at->format('M d, Y H:i A') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Updated:</strong></td>
                                            <td>{{ $pending->updated_at ? $pending->updated_at->format('M d, Y H:i A') : 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Original URL:</strong> 
                            <a href="{{ $pending->url }}" target="_blank" class="text-decoration-none">
                                {{ $pending->url }}
                                <i class="fas fa-external-link-alt ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@else
    {{-- No Data State --}}
    <div class="alert alert-warning">
        <h5><i class="fas fa-exclamation-circle me-2"></i>No Details Available</h5>
        <p>No transaction details could be loaded for this item.</p>
    </div>
@endif