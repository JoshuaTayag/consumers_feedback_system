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
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Membership Fees</label>
          <input type="number"  name="membership" name="id" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Energy Conmp Deposit</label>
          <input type="number"  name="energy_deposit" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Connection Fee <text class="text-danger">(V)</text></label>
          <input type="number"  name="conn_fee" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Rental <text class="text-danger">(V)</text></label>
          <input type="number"  name="xformer_rental" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Test <text class="text-danger">(V)</text></label>
          <input type="number"  name="xformer_test" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Installation <text class="text-danger">(V)</text></label>
          <input type="number"  name="xformer_installation" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Xformer Removal <text class="text-danger">(V)</text></label>
          <input type="number"  name="xformer_removal" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Consumer Xfmr <text class="text-danger">(V)</text></label>
          <input type="number"  name="consumer_xfmr" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Consumer Pole <text class="text-danger">(V)</text></label>
          <input type="number"  name="consumer_pole" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Grounding Clamp <text class="text-danger">(V)</text></label>
          <input type="number"  name="grounding_clamp" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Grounding Rod <text class="text-danger">(V)</text></label>
          <input type="number"  name="grounding_rod" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Meter Seal <text class="text-danger">(V)</text></label>
          <input type="number"  name="meter_seal" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Hotline Clamp <text class="text-danger">(V)</text></label>
          <input type="number"  name="hotline_clamp" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-block' : 'd-none'}}">
        <div class="mb-2">
          <label class="form-label mb-1">Metering Accessories <text class="text-danger">(V)</text></label>
          <input type="number"  name="meter_accessories" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute || $isPaymentTransactRoute ? '' : 'disabled' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Discredit Fee</label>
          <input type="number"  name="discredit_fee" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-block' : 'd-none'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Calibration Fee <text class="text-danger">(V)</text></label>
          <input type="number"  name="calibration_fee" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute || $isPaymentTransactRoute ? '' : 'disabled' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Others (ID Lamination) <text class="text-danger">(V)</text></label>
          <input type="number"  name="others" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Housewiring Kit <text class="text-danger">(V)</text></label>
          <input type="number"  name="housewiring_kit" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Excess Conductor <text class="text-danger">(V)</text></label>
          <input type="number"  name="excess_conductor" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Conductor Duplex #6 <text class="text-danger">(V)</label>
          <input type="number" name="conductor_duplex" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6 {{$isChangeMeterRoute ? 'd-none' : 'd-block'}}">
        <div class="mb-2">
          <label  class="form-label mb-1">Circuit Breaker <text class="text-danger">(V)</text></label>
          <input type="number"  name="circuit_breaker" oninput="addTotal()" class="form-control form-control-sm" step="0.01" {{ $isChangeMeterRoute ? 'disabled' : '' }}>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mb-2">
          <!-- <label  class="form-label mb-1">Total <text class="text-danger"></text></label> -->
          <input type="hidden"  name="total_charge" class="form-control form-control-sm" step="0.01">
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function addTotal() {
    // Select all number inputs except 'total_charge' and 'total'
    const inputs = document.querySelectorAll('input[type="number"]:not([name="total_charge"]):not([name="total"]):not([name="amount_tendered"])');
    
    let total = 0;

    let vatables

    // Loop through each input and sum the values
    inputs.forEach(function(input) {
        let value = parseFloat(input.value) || 0; // Convert to number, or default to 0

        if (input.name === 'membership' || input.name === 'energy_deposit' || input.name === 'discredit_fee') {
          value = value
        } else {
          value = value * 1.12; // Adding 12%
        }

        total += value;
    });

    // Set the total in the 'total_charge' input field
    document.querySelector('input[name="total_charge"]').value = total.toFixed(4); // Format to 2 decimal places
    document.querySelector('input[name="total"]').value = total.toFixed(4);

    this.calculateChange();
  }
</script>