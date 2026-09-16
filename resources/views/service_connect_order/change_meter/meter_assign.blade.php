<div class="modal fade" id="assigningModal" tabindex="-1" aria-labelledby="assigningModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ui-form-title" id="assigningModalLabel"><i class="fas fa-gauge-high me-2"></i>Assign Meter</h5>
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
            <li class="ui-step is-active">
              <span class="ui-step-icon"><i class="fas fa-gauge-high"></i></span>
              <span class="ui-step-label">Assign Meter</span>
            </li>
            <li class="ui-step-connector"></li>
            <li class="ui-step">
              <span class="ui-step-icon"><i class="fas fa-truck"></i></span>
              <span class="ui-step-label">Dispatch</span>
            </li>
            <li class="ui-step-connector"></li>
            <li class="ui-step">
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

        <form action="{{ route('cmAssignMeter') }}" method="POST" id="assignMeterForm">
          @csrf
          <input type="hidden" value="" id="cm_id" name="cm_id">

          <!-- Change Meter Request Reference -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-file-invoice me-2"></i>Change Meter Request
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-lg-4">
                  <label for="assigning_sco" class="form-label ui-label">SCO No.</label>
                  <input type="text" value="" id="assigning_sco" name="assigning_sco" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="assigning_name" class="form-label ui-label">Name</label>
                  <input type="text" value="" id="assigning_name" name="assigning_name" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="assigning_address" class="form-label ui-label">Address</label>
                  <input type="text" value="" id="assigning_address" name="assigning_address" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Meter Assignment -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-bolt me-2"></i>Meter Assignment
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-lg-4">
                  <label for="assigning_kwh_meter_request" class="form-label ui-label">kWh Meter Request</label>
                  <input type="text" value="" id="assigning_kwh_meter_request" name="assigning_kwh_meter_request" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="assigning_type_of_meter" class="form-label ui-label">Type of Meter</label>
                  <input type="text" value="" id="assigning_type_of_meter" name="assigning_type_of_meter" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="assigning_serial_number" class="form-label ui-label">Serial Number <span class="text-danger">*</span></label>
                  <select id="assigning_serial_number" class="form-control" name="new_meter_serial_no" required>
                    <option value="">Select Serial Number</option>
                  </select>
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
        </form>
      </div>
    </div>
  </div>
</div>