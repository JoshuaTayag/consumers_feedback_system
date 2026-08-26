@extends('layouts.app')

@section('content')
  @php
    $consumerTypes = collect(Config::get('constants.consumer_types'));
    $consumerType = $consumerTypes->firstWhere('id', $cm_request->consumer_type);
  @endphp
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <h3 class="mb-0">View Change Meter Order</h3>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}">
                  <i class="fas fa-arrow-left"></i> Back
                </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <!-- Transaction Details Card -->
            <div class="col-lg-6">
              <div class="card h-100 border shadow-sm">
                <div class="card-header bg-warning text-center py-3">
                    <h4 class="fw-bold mb-0">Transaction Details</h4>
                </div>
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Control #:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5 text-danger">{{$cm_request->control_no}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Consumer Name:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->last_name.', '.$cm_request->first_name}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Account #:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{ substr($cm_request->account_number, 0, 2) }}-{{ substr($cm_request->account_number, 2, 4) }}-{{ substr($cm_request->account_number, 6, 4) }}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Care of:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->care_of ?? 'N/A'}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Area:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">A{{$cm_request->area}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Feeder:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->feeder}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Address:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->sitio.', '.$cm_request->barangay->barangay_name.', '. $cm_request->municipality->municipality_name}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Consumer Type:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{ $consumerType['name'] ?? 'Unknown Type'}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Application Status:</span>
                    </div>
                    <div class="col-6">
                      <span class="badge rounded-pill bg-{{$cm_request->status == 1 ? 'danger' : ($cm_request->status == 2 ? 'success' : 'warning text-dark') }} p-2 fs-6">
                        {{$cm_request->status == 1 ? 'ACTED - NOT COMPLETED' : ($cm_request->status == 2 ? 'ACTED - COMPLETED' : ($cm_request->status == 3 ? 'DISPATCHED' : 'UNACTED')) }}
                      </span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Process Date:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{ date('F d, Y', strtotime($cm_request->created_at)) }}</span>
                    </div>
                  </div>
                  
                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Damage Cause:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->damageCause->name ?? 'N/A' }}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Landmark:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->location}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Transaction Remarks:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->remarks}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Meter Posting Details Card -->
            <div class="col-lg-6">
              <div class="card h-100 border shadow-sm">
                <div class="card-header bg-warning text-center py-3">
                    <h4 class="fw-bold mb-0">Meter Posting Details</h4>
                </div>
                <div class="card-body">
                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Old Meter No.:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->old_meter_no}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">New Meter No.:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->new_meter_no}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Type of Meter:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->type_of_meter}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">L5 Seal:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->postedMeterHistory ? $cm_request->postedMeterHistory->leyeco_seal_no : null}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">ERC Seal:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->postedMeterHistory ? $cm_request->postedMeterHistory->erc_seal_no : null}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Date and Time Acted:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{ date('m/d/Y h:i A', strtotime($cm_request->date_time_acted)) }}</span>
                    </div>
                  </div>
                  
                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Last Meter Reading:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->last_reading}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Initial Meter Reading:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->initial_reading}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Crew:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->changeMeterRequestCrew ? $cm_request->changeMeterRequestCrew->last_name.', '.$cm_request->changeMeterRequestCrew->first_name : null}}</span>
                    </div>
                  </div>

                  <div class="row mb-2">
                    <div class="col-6">
                      <span class="fs-5 fw-bold">Crew Remarks:</span>
                    </div>
                    <div class="col-6">
                      <span class="fs-5">{{$cm_request->crew_remarks}}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Consumer Acknowledgment Section -->
          @if($signatures && $signatures->isNotEmpty())
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card border shadow-sm">
                <div class="card-header bg-info text-white text-center py-3">
                  <h4 class="mb-0 fw-bold">Consumer Acknowledgment</h4>
                </div>
                <div class="card-body">
                  @foreach($signatures as $signature)
                  <div class="row mb-4 pb-3 border-bottom">
                    <div class="col-lg-8">
                      <div class="row mb-3">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Consumer Name:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5">{{ $signature->signatory_name }}</span>
                        </div>
                      </div>
                      
                      <div class="row mb-3">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Position:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5">{{ $signature->signatory_position }}</span>
                        </div>
                      </div>
                      
                      <div class="row mb-3">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Date Signed:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5">{{ date('F d, Y h:i A', strtotime($signature->created_at)) }}</span>
                        </div>
                      </div>
                      
                      @if($signature->latitude && $signature->longitude)
                      <div class="row mb-3">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Location:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5 font-monospace bg-light px-2 py-1 rounded border">
                            {{ $signature->location_formatted }}
                          </span>
                          <a href="https://maps.google.com/?q={{ $signature->latitude }},{{ $signature->longitude }}" 
                             target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fas fa-map-marker-alt"></i> View on Map
                          </a>
                        </div>
                      </div>
                      @endif
                    </div>
                    
                    <div class="col-lg-4 text-center">
                      <div class="mb-3">
                        <h6 class="fw-bold">Digital Signature</h6>
                        <div class="border rounded p-3 bg-light d-flex align-items-center justify-content-center" style="min-height: 150px;">
                          @if($signature->signature_image_url)
                            <img src="{{ $signature->signature_image_url }}" 
                                 alt="Consumer Signature" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-height: 120px; max-width: 100%;">
                          @else
                            <span class="text-muted">No signature available</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card border-warning shadow-sm">
                <div class="card-header bg-warning text-dark text-center py-3">
                  <h5 class="mb-0 fw-bold">No Consumer Acknowledgment Found</h5>
                </div>
                <div class="card-body text-center">
                  <p class="mb-0">This change meter request has not been acknowledged by the consumer yet.</p>
                </div>
              </div>
            </div>
          </div>
          @endif

          <!-- Audit Logs Section -->
          @if($audits && $audits->isNotEmpty())
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card border shadow-sm">
                <div class="card-header bg-secondary text-white py-3">
                  <div class="row align-items-center">
                    <div class="col-lg-8">
                      <h4 class="mb-0">
                        <i class="fas fa-history"></i> Change History (Audit Logs)
                      </h4>
                    </div>
                    <div class="col-lg-4 text-end">
                      <a href="{{ route('cmExportAuditLogs', $cm_request->id) }}" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-download"></i> Export CSV
                      </a>
                      <button class="btn btn-outline-light btn-sm" onclick="refreshAuditLogs({{ $cm_request->id }})">
                        <i class="fas fa-sync-alt"></i> Refresh
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                      <thead class="table-dark">
                        <tr>
                          <th class="text-center" style="width: 10%">Date & Time</th>
                          <th class="text-center" style="width: 10%">User</th>
                          <th class="text-center" style="width: 10%">Event</th>
                          <th style="width: 70%">Changes Made</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($audits as $audit)
                        <tr>
                          <td class="align-top text-center">
                            <small class="text-muted">{{ $audit->created_at->format('M d, Y') }}</small>
                            <br>
                            <small class="text-muted">{{ $audit->created_at->format('h:i A') }}</small>
                          </td>
                          <td class="align-top text-center">
                            <span class="badge bg-info">
                              {{ $audit->user ? $audit->user->name : 'System' }}
                            </span>
                          </td>
                          <td class="align-top text-center">
                            @switch($audit->event)
                              @case('created')
                                <span class="badge bg-success">Created</span>
                                @break
                              @case('updated')
                                <span class="badge bg-warning text-dark">Updated</span>
                                @break
                              @case('deleted')
                                <span class="badge bg-danger">Deleted</span>
                                @break
                              @default
                                <span class="badge bg-secondary">{{ ucfirst($audit->event) }}</span>
                            @endswitch
                          </td>
                          <td class="align-top">
                            @if($audit->event == 'created')
                              
                                <div class="mb-2">
                                  <small class="text-success fw-bold">
                                    <i class="fas fa-plus-circle"></i> Record Created with Initial Data
                                  </small>
                                </div>
                                @if($audit->new_values && count($audit->new_values) > 0)
                                  <div class="ms-3">
                                    @php
                                      $importantFields = ['control_no', 'first_name', 'last_name', 'account_number', 'area', 'municipality_id', 'barangay_id', 'old_meter_no', 'type_of_meter', 'created_by'];
                                      $displayFields = array_intersect_key($audit->new_values, array_flip($importantFields));
                                      if (empty($displayFields)) {
                                        $displayFields = array_slice($audit->new_values, 0, 8);
                                      }
                                    @endphp
                                    <div class="row">
                                      @foreach($displayFields as $key => $value)
                                        <div class="col-lg-3">
                                          <div class="border-start border-primary border-3 ps-2 mb-2">
                                            <strong class="text-dark">{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                            <br>
                                            <small class="ms-2">
                                              <span class="text-muted">Initial Value:</span> 
                                              <code class="text-primary bg-light px-1 rounded">{{ is_null($value) ? 'NULL' : (is_array($value) ? json_encode($value) : $value) }}</code>
                                            </small>
                                          </div>
                                        </div>
                                      @endforeach
                                    </div>
                                    @if(count($audit->new_values) > count($displayFields))
                                      <div class="mt-2">
                                        <small class="text-muted">
                                          <i class="fas fa-info-circle"></i> 
                                          {{ count($audit->new_values) - count($displayFields) }} more fields were set during creation
                                        </small>
                                      </div>
                                    @endif
                                  </div>
                              @endif
                            @elseif($audit->event == 'updated' && $audit->old_values && $audit->new_values)
                              <div class="ms-3">
                                <div class="row">
                                  @foreach($audit->new_values as $key => $newValue)
                                    @if(array_key_exists($key, $audit->old_values) && $audit->old_values[$key] != $newValue)
                                      <div class="col-lg-3">
                                        <div class="border-start border-primary border-3 ps-2 mb-2">
                                          <strong class="text-dark">{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                          <br>
                                          <small>
                                            <span class="text-muted">From:</span> 
                                            <code class="text-danger bg-light px-1 rounded">{{ is_null($audit->old_values[$key]) ? 'NULL' : (is_array($audit->old_values[$key]) ? json_encode($audit->old_values[$key]) : $audit->old_values[$key]) }}</code>
                                            <i class="fas fa-arrow-right mx-1"></i>
                                            <span class="text-muted">To:</span> 
                                            <code class="text-success bg-light px-1 rounded">{{ is_null($newValue) ? 'NULL' : (is_array($newValue) ? json_encode($newValue) : $newValue) }}</code>
                                          </small>
                                        </div>
                                      </div>
                                    @endif
                                  @endforeach
                                </div>
                                
                              </div>
                            @elseif($audit->event == 'deleted')
                              <div class="mb-2">
                                <small class="text-danger fw-bold">
                                  <i class="fas fa-trash"></i> Record Deleted/Archived
                                </small>
                              </div>
                              @if($audit->old_values && count($audit->old_values) > 0)
                                <div class="ms-3">
                                  @php
                                    $keyFields = ['control_no', 'first_name', 'last_name', 'account_number', 'status'];
                                    $displayFields = array_intersect_key($audit->old_values, array_flip($keyFields));
                                    if (empty($displayFields)) {
                                      $displayFields = array_slice($audit->old_values, 0, 5);
                                    }
                                  @endphp
                                  @foreach($displayFields as $key => $value)
                                    <div class="border-start border-danger border-3 ps-2 mb-2">
                                      <strong class="text-dark">{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                      <br>
                                      <small class="ms-2">
                                        <span class="text-muted">Last Value:</span> 
                                        <code class="text-muted bg-light px-1 rounded">{{ is_null($value) ? 'NULL' : (is_array($value) ? json_encode($value) : $value) }}</code>
                                      </small>
                                    </div>
                                  @endforeach
                                </div>
                              @endif
                            @elseif($audit->event == 'restored')
                              <small class="text-info">
                                <i class="fas fa-undo"></i> Record was restored from deletion
                              </small>
                            @else
                              <small class="text-muted">{{ ucfirst($audit->event) }} event - No detailed information available</small>
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @else
          <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card border shadow-sm">
                <div class="card-header bg-light text-dark text-center py-3">
                  <h5 class="mb-0">
                    <i class="fas fa-info-circle"></i> No Audit Logs Found
                  </h5>
                </div>
                <div class="card-body text-center">
                  <p class="mb-0 text-muted">No change history is available for this request.</p>
                </div>
              </div>
            </div>
          </div>
          @endif

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toast Container -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
  <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto" id="toastTitle">Notification</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body" id="toastMessage">
      Message here
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function refreshAuditLogs(cmRequestId) {
    const refreshBtn = document.querySelector('[onclick="refreshAuditLogs(' + cmRequestId + ')"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    refreshBtn.disabled = true;
    
    fetch(`{{ route('cmAuditLogs', ['id' => '__ID__']) }}`.replace('__ID__', cmRequestId), {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateAuditLogsSection(data.data, data.total);
            showToast('Audit logs refreshed successfully!', 'success');
        } else {
            showToast(data.message || 'Failed to refresh audit logs', 'error');
        }
    })
    .catch(error => {
        console.error('Error refreshing audit logs:', error);
        showToast('An error occurred while refreshing audit logs', 'error');
    })
    .finally(() => {
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
    });
}

function updateAuditLogsSection(audits, total) {
    if (audits && audits.length > 0) {
        let auditRowsHtml = '';
        
        audits.forEach(audit => {
            let badgeClass = 'bg-secondary';
            switch(audit.event) {
                case 'created':
                    badgeClass = 'bg-success';
                    break;
                case 'updated':
                    badgeClass = 'bg-warning text-dark';
                    break;
                case 'deleted':
                    badgeClass = 'bg-danger';
                    break;
            }
            
            let changesHtml = '';
            if (audit.event === 'created') {
                changesHtml = '<div class="mb-2"><small class="text-success fw-bold"><i class="fas fa-plus-circle"></i> Record Created with Initial Data</small></div>';
                if (audit.created_data && audit.created_data.length > 0) {
                    changesHtml += '<div class="ms-3">';
                    audit.created_data.forEach(field => {
                        changesHtml += `
                            <div class="border-start border-primary border-3 ps-2 mb-2">
                                <strong class="text-dark">${field.field}:</strong>
                                <br>
                                <small class="ms-2">
                                    <span class="text-muted">Initial Value:</span> 
                                    <code class="text-primary bg-light px-1 rounded">${field.value || 'NULL'}</code>
                                </small>
                            </div>
                        `;
                    });
                    changesHtml += '</div>';
                }
            } else if (audit.event === 'updated' && audit.changes && audit.changes.length > 0) {
                changesHtml = '<div class="ms-3">';
                audit.changes.forEach(change => {
                    changesHtml += `
                        <div class="border-start border-primary border-3 ps-2 mb-2">
                            <strong class="text-dark">${change.field}:</strong>
                            <br>
                            <small class="ms-2">
                                <span class="text-muted">From:</span> 
                                <code class="text-danger bg-light px-1 rounded">${change.old_value || 'NULL'}</code>
                                <br>
                                <span class="text-muted">To:</span> 
                                <code class="text-success bg-light px-1 rounded">${change.new_value || 'NULL'}</code>
                            </small>
                        </div>
                    `;
                });
                changesHtml += '</div>';
            } else if (audit.event === 'deleted') {
                changesHtml = '<small class="text-danger fw-bold"><i class="fas fa-trash"></i> Record was deleted/archived</small>';
            } else {
                changesHtml = '<small class="text-muted">No specific changes recorded</small>';
            }
            
            auditRowsHtml += `
                <tr>
                    <td class="align-top text-center">
                        <small class="text-muted">${audit.created_at}</small>
                    </td>
                    <td class="align-top text-center">
                        <span class="badge bg-info">${audit.user_name}</span>
                    </td>
                    <td class="align-top text-center">
                        <span class="badge ${badgeClass}">${audit.event.charAt(0).toUpperCase() + audit.event.slice(1)}</span>
                    </td>
                    <td class="align-top">${changesHtml}</td>
                </tr>
            `;
        });
        
        const tableBody = document.querySelector('.table-responsive tbody');
        if (tableBody) {
            tableBody.innerHTML = auditRowsHtml;
        }
    }
}

function showToast(message, type) {
    const toastElement = document.getElementById('liveToast');
    const toastBody = document.getElementById('toastMessage');
    const toastHeader = toastElement.querySelector('.toast-header');
    
    // Set message
    toastBody.textContent = message;
    
    // Update styling based on type
    toastHeader.className = type === 'success' ? 'toast-header bg-success text-white' : 'toast-header bg-danger text-white';
    
    // Show toast
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
}
</script>
@endsection