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
              <div class="div text-center fs-3 bg-secondary rounded mb-3">
                Transaction Details
              </div>
              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Control #</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="text-danger">{{$cm_request->control_no}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Consumer Name</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->last_name.', '.$cm_request->first_name}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Account #</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{ substr($cm_request->account_number, 0, 2) }}-{{ substr($cm_request->account_number, 2, 4) }}-{{ substr($cm_request->account_number, 6, 4) }}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Care of</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->care_of ? $cm_request->care_of : 'N/A'}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Area</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">A{{$cm_request->area}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Feeder</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->feeder}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Address</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->sitio.', '.$cm_request->barangay->barangay_name.', '. $cm_request->municipality->municipality_name}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Consumer Type</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{ $consumerType['name'] ?? 'Unknown Type'}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Application Status</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="badge my-1 rounded-pill bg-{{$cm_request->status == 1 ? 'danger' : ($cm_request->status == 2 ? 'success' : 'warning text-dark') }} p-2 fs-6" >{{$cm_request->status == 1 ? 'ACTED - NOT COMPLETED' : ($cm_request->status == 2 ? 'ACTED - COMPLETED' : ($cm_request->status == 3 ? 'DISPATCHED' : 'UNACTED')) }}</span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Process Date</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{ date('F d, Y', strtotime($cm_request->created_at)) }}</span></span>
                </div>
              </div>
              
              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Damage Cause</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->damage_cause}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Landmark</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->location}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Transaction Remarks.</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->remarks}}</span></span>
                </div>
              </div>

            </div>


            <div class="col-lg-6">
              <div class="div text-center fs-3 bg-secondary rounded mb-3">
                Meter Posting Details
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Old Meter No.</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->old_meter_no}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">New Meter No.</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->new_meter_no}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Type of Meter</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->type_of_meter}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">L5 Seal</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->postedMeterHistory ? $cm_request->postedMeterHistory->leyeco_seal_no : null}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">ERC Seal</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->postedMeterHistory ? $cm_request->postedMeterHistory->erc_seal_no : null}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Date and Time Acted</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{ date('m/d/Y h:i A', strtotime($cm_request->date_time_acted)) }}</span></span>
                </div>
              </div>
              
              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Last Meter Reading</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->last_reading}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Initial Meter Reading</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->initial_reading}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Crew</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->changeMeterRequestCrew ? $cm_request->changeMeterRequestCrew->last_name.', '.$cm_request->changeMeterRequestCrew->first_name : null}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">Crew Remarks</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->crew_remarks}}</span></span>
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

        </div>
      </div>
    </div>
  </div>
</div>
@endsection