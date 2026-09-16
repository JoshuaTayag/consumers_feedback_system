<div class="modal fade" id="meterPostingModal" tabindex="-1" aria-labelledby="meterPostingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ui-form-title" id="meterPostingModalLabel"><i class="fas fa-paper-plane me-2"></i>Meter Posting</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- Stage Breadcrumb / Stepper -->
        <div class="ui-stepper-wrap mb-4">
          <ol class="ui-stepper">
            <li class="ui-step">
              <span class="ui-step-icon"><i class="fas fa-file-signature"></i></span>
              <span class="ui-step-label">Create</span>
            </li>
            <li class="ui-step-connector"></li>
            <li class="ui-step">
              <span class="ui-step-icon"><i class="fas fa-gauge-high"></i></span>
              <span class="ui-step-label">Assign Meter</span>
            </li>
            <li class="ui-step-connector"></li>
            <li class="ui-step">
              <span class="ui-step-icon"><i class="fas fa-truck"></i></span>
              <span class="ui-step-label">Dispatch</span>
            </li>
            <li class="ui-step-connector"></li>
            <li class="ui-step is-active">
              <span class="ui-step-icon"><i class="fas fa-paper-plane"></i></span>
              <span class="ui-step-label">Post</span>
            </li>
            <li class="ui-step-connector"></li>
            <li class="ui-step">
              <span class="ui-step-icon"><i class="fas fa-circle-check"></i></span>
              <span class="ui-step-label">Completed</span>
            </li>
          </ol>
        </div>

        {!! Form::open(array('route' => 'meterPostingCM','method'=>'POST', 'id' => 'myForm')) !!}
          <input type="hidden" value="" id="cm_id" name="cm_id">

          <!-- Change Meter Request Reference -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-file-invoice me-2"></i>Change Meter Request
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-lg-4">
                  <label for="sco" class="form-label ui-label">SCO No.</label>
                  <input type="text" value="" id="sco" name="sco" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="full_name" class="form-label ui-label">Fullname</label>
                  <input type="text" value="" id="full_name" name="full_name" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="process_date" class="form-label ui-label">Process Date</label>
                  <input type="text" value="" id="process_date" name="process_date" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Meter Details -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-bolt me-2"></i>Meter Details
            </div>
            <div class="card-body">
              <div class="row g-3" id="meter_details">
                <div class="col-lg-4">
                  <label for="meter_no" class="form-label ui-label">Meter No <span class="text-danger">*</span></label>
                  <input type="text" value="" id="meter_no" name="meter_no" class="form-control" required>
                  <span id="error_meter"></span>
                </div>
                <div class="col-lg-4">
                  <label for="seal_no" class="form-label ui-label">L5 Seal No. <span class="text-danger">*</span></label>
                  <input type="text" value="" id="seal_no" name="seal_no" class="form-control" required>
                  <span id="error_seal"></span>
                </div>
                <div class="col-lg-4">
                  <label for="erc_seal" class="form-label ui-label">ERC Seal No. <span class="text-danger">*</span></label>
                  <input type="text" value="" id="erc_seal" name="erc_seal" class="form-control" required>
                  <span id="error_erc_seal"></span>
                </div>
              </div>
            </div>
          </div>

          <!-- Address & Reading -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-location-dot me-2"></i>Address &amp; Reading
            </div>
            <div class="card-body">
              <div class="row g-3" id="address_details">
                <div class="col-lg-4">
                  <label for="care_of" class="form-label ui-label">C/O</label>
                  <input type="text" value="" id="care_of" name="care_of" class="form-control">
                </div>
                <div class="col-lg-4">
                  <label for="last_reading" class="form-label ui-label">Last Reading <span class="text-danger">*</span></label>
                  <input type="number" value="" id="last_reading" name="last_reading" class="form-control">
                </div>
                <div class="col-lg-4">
                  <label for="reading_initial" class="form-label ui-label">Reading Initial</label>
                  <input type="number" value="" id="reading_initial" name="reading_initial" class="form-control">
                </div>
              </div>
            </div>
          </div>

          <!-- Action Details -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-clipboard-check me-2"></i>Action Details
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="status" class="form-label ui-label">Action Status</label>
                  <select id="status" class="form-control" name="status" required>
                    <option value=""></option>
                    @foreach (Config::get('constants.action_status_change_meter') as $status)
                      <option value="{{ $status['id'] }}" id="">{{ $status['name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-3">
                  <label for="date_acted" class="form-label ui-label">Date Acted</label>
                  <input type="date" value="" id="date_acted" name="date_acted" class="form-control" required>
                </div>
                <div class="col-lg-2">
                  <label for="time" class="form-label ui-label">Time</label>
                  <input type="time" value="" id="time" name="time" class="form-control" required>
                </div>
                <div class="col-lg-4">
                  <label for="damage_cause" class="form-label ui-label">Damage Cause <span class="text-danger">*</span></label>
                  <select id="damage_cause" class="form-control" name="damage_cause" required>
                    <option value=""></option>
                    @foreach ($meter_damage_causes as $id => $name)
                      <option value="{{ $id }}" id="">{{ $name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-12">
                  <label for="crew_remarks" class="form-label ui-label">Crew Remarks</label>
                  <textarea name="crew_remarks" id="crew_remarks" class="form-control"></textarea>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer px-0 pb-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              <i class="fas fa-xmark me-1"></i>Close
            </button>
            <button type="submit" class="btn ui-btn-cta" id="submit_meter_posting">
              <i class="fas fa-check me-1"></i>Save Changes
            </button>
          </div>
        {!! Form::close() !!}
      </div>
    </div>
  </div>
</div>