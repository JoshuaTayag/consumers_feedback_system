@extends('layouts.app')

@section('styles')
<style>
.signature-container {
  position: relative;
}

.signature-box {
  border: 2px solid #dee2e6;
  border-radius: 8px;
  background-color: #ffffff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
}

.signature-box img {
  border-radius: 4px;
}

.coordinates-display {
  font-family: 'Courier New', monospace;
  background-color: #f8f9fa;
  padding: 2px 6px;
  border-radius: 4px;
  border: 1px solid #e9ecef;
}

.consumer-acknowledgment-section {
  margin-top: 2rem;
  border-top: 3px solid #17a2b8;
}

.changes-list .change-item {
  border-left: 3px solid #007bff;
  padding-left: 10px;
  margin-bottom: 8px;
}

.changes-list .change-item:last-child {
  margin-bottom: 0;
}

.audit-logs-section {
  margin-top: 2rem;
}

.audit-logs-section .table td {
  vertical-align: top;
  padding: 12px 8px;
}

.audit-logs-section .table th {
  background-color: #343a40;
  color: white;
  font-weight: 600;
  text-align: center;
}

.audit-logs-section code {
  font-size: 0.85em;
  padding: 2px 4px;
  border-radius: 3px;
}
</style>
@endsection

@section('content')
  @php
    $consumerTypes = collect(Config::get('constants.consumer_types'));
    $consumerType = $consumerTypes->firstWhere('id', $cm_request->consumer_type);
  @endphp
<div class="container">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">View Change Meter Order</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}"> Back </a>
              </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header bg-warning text-center">
                    <h4 class="fw-bold">Transaction Details</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Control #</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="text-danger">{{$cm_request->control_no}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Consumer Name</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->last_name.', '.$cm_request->first_name}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Account #</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{ substr($cm_request->account_number, 0, 2) }}-{{ substr($cm_request->account_number, 2, 4) }}-{{ substr($cm_request->account_number, 6, 4) }}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Care of</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->care_of ? $cm_request->care_of : 'N/A'}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Area</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">A{{$cm_request->area}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Feeder</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->feeder}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Address</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->sitio.', '.$cm_request->barangay->barangay_name.', '. $cm_request->municipality->municipality_name}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Consumer Type</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{ $consumerType['name'] ?? 'Unknown Type'}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Application Status</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="badge my-1 rounded-pill bg-{{$cm_request->status == 1 ? 'danger' : ($cm_request->status == 2 ? 'success' : 'warning text-dark') }} p-2 fs-6" >{{$cm_request->status == 1 ? 'ACTED - NOT COMPLETED' : ($cm_request->status == 2 ? 'ACTED - COMPLETED' : ($cm_request->status == 3 ? 'DISPATCHED' : 'UNACTED')) }}</span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Process Date</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{ date('F d, Y', strtotime($cm_request->created_at)) }}</span></span>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Damage Cause</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->damage_cause}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Landmark</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->location}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Transaction Remarks.</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->remarks}}</span></span>
                    </div>
                  </div>
                </div>
              </div>

              

            </div>


            <div class="col-lg-6">
              <div class="card">
                <div class="card-header bg-warning text-center">
                    <h4 class="fw-bold">Meter Posting Details</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Old Meter No.</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->old_meter_no}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">New Meter No.</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->new_meter_no}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Type of Meter</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->type_of_meter}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">L5 Seal</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->postedMeterHistory ? $cm_request->postedMeterHistory->leyeco_seal_no : null}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">ERC Seal</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->postedMeterHistory ? $cm_request->postedMeterHistory->erc_seal_no : null}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Date and Time Acted</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{ date('m/d/Y h:i A', strtotime($cm_request->date_time_acted)) }}</span></span>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Last Meter Reading</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->last_reading}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Initial Meter Reading</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->initial_reading}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Crew</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->changeMeterRequestCrew ? $cm_request->changeMeterRequestCrew->last_name.', '.$cm_request->changeMeterRequestCrew->first_name : null}}</span></span>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 mb-1">
                      <div class="d-flex justify-content-between">
                          <span class="fs-5 fw-bold">Crew Remarks</span><span class="text-end fs-5 fw-bold">:</span>
                      </div>
                    </div>
                    <div class="col-lg-6 mb-1">
                      <span class="fs-5"><span class="">{{$cm_request->crew_remarks}}</span></span>
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
              <div class="card">
                <div class="card-header bg-info text-white text-center">
                  <h4 class="mb-0">Consumer Acknowledgment</h4>
                </div>
                <div class="card-body">
                  @foreach($signatures as $signature)
                  <div class="row mb-4 border-bottom pb-3">
                    <div class="col-lg-8">
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Consumer Name:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5">{{ $signature->signatory_name }}</span>
                        </div>
                      </div>
                      
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Position:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5">{{ $signature->signatory_position }}</span>
                        </div>
                      </div>
                      
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Date Signed:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5">{{ date('F d, Y h:i A', strtotime($signature->created_at)) }}</span>
                        </div>
                      </div>
                      
                      @if($signature->latitude && $signature->longitude)
                      <div class="row mb-2">
                        <div class="col-lg-4">
                          <span class="fs-5 fw-bold">Location:</span>
                        </div>
                        <div class="col-lg-8">
                          <span class="fs-5 coordinates-display">
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
                      <div class="signature-container">
                        <h6 class="fw-bold mb-3">Digital Signature</h6>
                        <div class="signature-box border rounded p-2" style="background-color: #f8f9fa; min-height: 150px;">
                          @if($signature->signature_image_url)
                            <img src="{{ $signature->signature_image_url }}" 
                                 alt="Consumer Signature" 
                                 class="img-fluid" 
                                 style="max-height: 120px; max-width: 100%;">
                          @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                              <span class="text-muted">No signature available</span>
                            </div>
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
              <div class="card">
                <div class="card-header bg-warning text-dark text-center">
                  <h5 class="mb-0">No Consumer Acknowledgment Found</h5>
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
              <div class="card audit-logs-section">
                <div class="card-header bg-secondary text-white">
                  <div class="row align-items-center">
                    <div class="col-lg-8">
                      <h4 class="mb-0"><i class="fas fa-history"></i> Change History (Audit Logs)</h4>
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
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead class="table-dark">
                        <tr>
                          <th style="width: 15%">Date & Time</th>
                          <th style="width: 12%">User</th>
                          <th style="width: 10%">Event</th>
                          <th style="width: 63%">Changes Made</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($audits as $audit)
                        <tr>
                          <td>
                            <small class="text-muted">{{ $audit->created_at->format('M d, Y h:i A') }}</small>
                          </td>
                          <td>
                            <span class="badge bg-info">
                              {{ $audit->user ? $audit->user->name : 'System' }}
                            </span>
                          </td>
                          <td>
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
                          <td>
                            @if($audit->event == 'created')
                              <div class="mb-2">
                                <small class="text-success fw-bold">
                                  <i class="fas fa-plus-circle"></i> Record Created with Initial Data
                                </small>
                              </div>
                              @if($audit->new_values && count($audit->new_values) > 0)
                                <div class="changes-list">
                                  @php
                                    $importantFields = ['control_no', 'first_name', 'last_name', 'account_number', 'area', 'municipality_id', 'barangay_id', 'old_meter_no', 'type_of_meter', 'created_by'];
                                    $displayFields = array_intersect_key($audit->new_values, array_flip($importantFields));
                                    if (empty($displayFields)) {
                                      $displayFields = array_slice($audit->new_values, 0, 8); // Show first 8 fields if no important fields
                                    }
                                  @endphp
                                  @foreach($displayFields as $key => $value)
                                    <div class="change-item mb-1">
                                      <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                      <br>
                                      <small>
                                        <span class="text-muted">Initial Value:</span> 
                                        <code class="text-primary">{{ is_null($value) ? 'NULL' : (is_array($value) ? json_encode($value) : $value) }}</code>
                                      </small>
                                    </div>
                                  @endforeach
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
                              <div class="changes-list">
                                @foreach($audit->new_values as $key => $newValue)
                                  @if(array_key_exists($key, $audit->old_values) && $audit->old_values[$key] != $newValue)
                                    <div class="change-item mb-1">
                                      <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                      <br>
                                      <small>
                                        <span class="text-muted">From:</span> 
                                        <code class="text-danger">{{ is_null($audit->old_values[$key]) ? 'NULL' : (is_array($audit->old_values[$key]) ? json_encode($audit->old_values[$key]) : $audit->old_values[$key]) }}</code>
                                        <br>
                                        <span class="text-muted">To:</span> 
                                        <code class="text-success">{{ is_null($newValue) ? 'NULL' : (is_array($newValue) ? json_encode($newValue) : $newValue) }}</code>
                                      </small>
                                    </div>
                                  @endif
                                @endforeach
                              </div>
                            @elseif($audit->event == 'deleted')
                              <div class="mb-2">
                                <small class="text-danger fw-bold">
                                  <i class="fas fa-trash"></i> Record Deleted/Archived
                                </small>
                              </div>
                              @if($audit->old_values && count($audit->old_values) > 0)
                                <div class="changes-list">
                                  @php
                                    $keyFields = ['control_no', 'first_name', 'last_name', 'account_number', 'status'];
                                    $displayFields = array_intersect_key($audit->old_values, array_flip($keyFields));
                                    if (empty($displayFields)) {
                                      $displayFields = array_slice($audit->old_values, 0, 5);
                                    }
                                  @endphp
                                  @foreach($displayFields as $key => $value)
                                    <div class="change-item mb-1">
                                      <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                      <br>
                                      <small>
                                        <span class="text-muted">Last Value:</span> 
                                        <code class="text-muted">{{ is_null($value) ? 'NULL' : (is_array($value) ? json_encode($value) : $value) }}</code>
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
              <div class="card">
                <div class="card-header bg-light text-dark text-center">
                  <h5 class="mb-0"><i class="fas fa-info-circle"></i> No Audit Logs Found</h5>
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
@endsection

@section('scripts')
<script>
function refreshAuditLogs(cmRequestId) {
    // Show loading state
    const refreshBtn = document.querySelector('[onclick="refreshAuditLogs(' + cmRequestId + ')"]');
    const originalContent = refreshBtn.innerHTML;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    refreshBtn.disabled = true;
    
    // Make AJAX request to fetch fresh audit logs
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
            // Update the audit logs section with new data
            updateAuditLogsSection(data.data, data.total);
            
            // Show success message
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
        // Reset button state
        refreshBtn.innerHTML = originalContent;
        refreshBtn.disabled = false;
    });
}

function updateAuditLogsSection(audits, total) {
    if (audits && audits.length > 0) {
        let auditRowsHtml = '';
        
        audits.forEach(audit => {
            // Determine badge class for event type
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
                    changesHtml += '<div class="changes-list">';
                    audit.created_data.forEach(field => {
                        changesHtml += `
                            <div class="change-item mb-1">
                                <strong>${field.field}:</strong>
                                <br>
                                <small>
                                    <span class="text-muted">Initial Value:</span> 
                                    <code class="text-primary">${field.value || 'NULL'}</code>
                                </small>
                            </div>
                        `;
                    });
                    changesHtml += '</div>';
                }
            } else if (audit.event === 'updated' && audit.changes && audit.changes.length > 0) {
                changesHtml = '<div class="changes-list">';
                audit.changes.forEach(change => {
                    changesHtml += `
                        <div class="change-item mb-1">
                            <strong>${change.field}:</strong>
                            <br>
                            <small>
                                <span class="text-muted">From:</span> 
                                <code class="text-danger">${change.old_value || 'NULL'}</code>
                                <br>
                                <span class="text-muted">To:</span> 
                                <code class="text-success">${change.new_value || 'NULL'}</code>
                            </small>
                        </div>
                    `;
                });
                changesHtml += '</div>';
            } else if (audit.event === 'deleted') {
                changesHtml = '<small class="text-danger"><i class="fas fa-trash"></i> Record was deleted/archived</small>';
            } else {
                changesHtml = '<small class="text-muted">No specific changes recorded</small>';
            }
            
            auditRowsHtml += `
                <tr>
                    <td><small class="text-muted">${audit.created_at}</small></td>
                    <td><span class="badge bg-info">${audit.user_name}</span></td>
                    <td><span class="badge ${badgeClass}">${audit.event.charAt(0).toUpperCase() + audit.event.slice(1)}</span></td>
                    <td>${changesHtml}</td>
                </tr>
            `;
        });
        
        // Update the table body
        const tableBody = document.querySelector('.audit-logs-section tbody');
        if (tableBody) {
            tableBody.innerHTML = auditRowsHtml;
        }
    }
}

function showToast(message, type) {
    // Simple toast notification (you can customize this based on your existing notification system)
    const toastClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const toast = document.createElement('div');
    toast.className = `alert ${toastClass} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto dismiss after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}
</script>
@endsection