<div class="container border border-dark rounded-3 pe-0" style="box-shadow: 10px 5px 10px gray;">
<div class="scrollbar" id= "scrollbar1">
<div class="row mt-2 me-1 p-2">
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Membership Fee</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Membership Fee'}, 2, '.', '') : '0.00'}}" name="membership" name="id" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Energy Conmp Deposit</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Energy Conmp Deposit'}, 2, '.', '') : '0.00'}}" name="energy_deposit" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Connection Fee</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Connection Fee'}, 2, '.', '') : '0.00'}}" name="conn_fee" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Xformer Rental</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Xformer Rental'}, 2, '.', '') : '0.00'}}" name="xformer_rental" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Xformer Test</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Xformer Test'}, 2, '.', '') : '0.00'}}" name="xformer_test" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Xformer Installation.</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Xformer Installation'}, 2, '.', '') : '0.00'}}" name="xformer_installation" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Xformer Removal.</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Xformer Removal'}, 2, '.', '') : '0.00'}}" name="xformer_removal" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Consumer Xfmr</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Cons Xformer'}, 2, '.', '') : '0.00'}}" name="consumer_xfmr" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Consumer Pole</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Cons XPole'}, 2, '.', '') : '0.00'}}" name="consumer_pole" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Grounding Clamp</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Grounding Clamp'}, 2, '.', '') : '0.00'}}" name="grounding_clamp" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Grounding Rod</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Grounding Rod'}, 2, '.', '') : '0.00'}}" name="grounding_rod" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Meter Seal</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->MeterSeal, 2, '.', '') : '0.00'}}" name="meter_seal" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Hotline Clamp</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Hotline Clamp'}, 2, '.', '') : '0.00'}}" name="hotline_clamp" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label class="form-label mb-1">Metering Accessories</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Kwhm Deposit'}, 2, '.', '') : '0.00'}}" name="meter_accessories" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Discredit Fee</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'RejectionFee'}, 2, '.', '') : '0.00'}}" name="discredit_fee" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Calibration Fee</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Calibration'}, 2, '.', '') : '0.00'}}" name="calibration_fee" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Others (ID Lamination)</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'Others IDLamination'}, 2, '.', '') : '0.00'}}" name="others" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Housewiring Kit</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'HousewringKit'}, 2, '.', '') : '0.00'}}" name="housewiring_kit" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Excess Conductor</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->{'ExcessWire'}, 2, '.', '') : '0.00'}}" name="excess_conductor" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Conductor Duplex #6</label>
      <input type="number" value="0.00" name="conductor_duplex" class="form-control form-control-sm">
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mb-2">
      <label  class="form-label mb-1">Circuit Breaker</label>
      <input type="number" value="{{isset($fees) ? number_format( $fees[0]->circuit_breaker, 2, '.', '') : '0.00'}}" name="circuit_breaker" class="form-control form-control-sm">
    </div>
  </div>
</div>
</div>
</div>