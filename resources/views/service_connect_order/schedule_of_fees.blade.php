@php
    $isChangeMeterRoute = Route::currentRouteName() === 'createCM' || Route::currentRouteName() === 'editCM';
    $isPaymentTransactRoute = Route::currentRouteName() === 'payment-transact.create';
@endphp
<div class="container border border-dark rounded-3 pe-0" style="box-shadow: 10px 5px 10px gray;">
  <div class="scrollbar" id= "scrollbar1">
    <div class="row mt-2 me-1 p-2">
      <div class="div mb-3">
        <div class="col alpha">
          <span class="p-1 fw-bold">Current Fees:</span>
              <ol class="list-group list-group-numbered">
              @if(isset($change_meter_request->cmr_fees) && $change_meter_request->cmr_fees->isNotEmpty())
                  @foreach($change_meter_request->cmr_fees as $cm_fees)
                      <li class="list-group-item bg-secondary text-white text-capitalize fw-bold">
                          {{ str_replace('_', ' ', $cm_fees->fees) }} - ₱{{ number_format($cm_fees->amount, 2, '.', '') }}
                      </li>
                  @endforeach
              @else
                  <!-- <li class="list-group-item bg-secondary text-white text-capitalize fw-bold">No fees available</li> -->
              @endif
            </ol>
        </div>
        <hr>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Membership Fees</label>
          <input type="number"  name="membership" name="id" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Energy Conmp Deposit</label>
          <input type="number"  name="energy_deposit" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Connection Fee</label>
          <input type="number"  name="conn_fee" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Rental</label>
          <input type="number"  name="xformer_rental" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Test</label>
          <input type="number"  name="xformer_test" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Installation.</label>
          <input type="number"  name="xformer_installation" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Removal.</label>
          <input type="number"  name="xformer_removal" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Consumer Xfmr</label>
          <input type="number"  name="consumer_xfmr" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Consumer Pole</label>
          <input type="number"  name="consumer_pole" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Grounding Clamp</label>
          <input type="number"  name="grounding_clamp" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Grounding Rod</label>
          <input type="number"  name="grounding_rod" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Meter Seal</label>
          <input type="number"  name="meter_seal" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute || $isPaymentTransactRoute ? '' : 'disabled' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Hotline Clamp</label>
          <input type="number"  name="hotline_clamp" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label class="form-label mb-1">Metering Accessories <text class="text-danger">(V)</text></label>
          <input type="number"  name="meter_accessories" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute || $isPaymentTransactRoute ? '' : 'disabled' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Discredit Fee</label>
          <input type="number"  name="discredit_fee" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Calibration Fee</label>
          <input type="number"  name="calibration_fee" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute || $isPaymentTransactRoute ? '' : 'disabled' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Others (ID Lamination)</label>
          <input type="number"  name="others" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Housewiring Kit</label>
          <input type="number"  name="housewiring_kit" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Excess Conductor</label>
          <input type="number"  name="excess_conductor" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Conductor Duplex #6</label>
          <input type="number" name="conductor_duplex" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <label  class="form-label mb-1">Circuit Breaker</label>
          <input type="number"  name="circuit_breaker" class="form-control form-control-sm" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
    </div>
  </div>
</div>