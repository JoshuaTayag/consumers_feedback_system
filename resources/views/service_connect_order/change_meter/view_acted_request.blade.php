@extends('layouts.app')

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
                  <span class="fs-4 fw-bold"><span class="badge rounded-pill bg-{{$cm_request->status == 1 ? 'warning' : ($cm_request->status == 2 ? 'success' : 'danger') }}">{{$cm_request->status == 1 ? 'NOT COMPLETED' : ($cm_request->status == 2 ? 'INSTALLED' : 'REJECTED') }}</span></span>
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
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->postedMeterHistory->leyeco_seal_no}}</span></span>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 mb-1">
                  <div class="d-flex justify-content-between">
                      <span class="fs-4 fw-bold">ERC Seal</span><span class="text-end fs-4 fw-bold">:</span>
                  </div>
                </div>
                <div class="col-lg-6 mb-1">
                  <span class="fs-4 fw-bold"><span class="">{{$cm_request->postedMeterHistory->erc_seal_no}}</span></span>
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
        </div>
      </div>
    </div>
  </div>
</div>
@endsection