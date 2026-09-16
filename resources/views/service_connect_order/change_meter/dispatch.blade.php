<div class="modal fade" id="dispatchingModal" tabindex="-1" aria-labelledby="dispatchingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title ui-form-title" id="dispatchingModalLabel"><i class="fas fa-gauge-high me-2"></i>Dispatch</h5>
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
            <li class="ui-step is-active">
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

        <form action="{{ route('cmDispatching') }}" method="POST" id="assignMeterForm">
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
                  <label for="dispatching_sco" class="form-label ui-label">SCO No.</label>
                  <input type="text" value="" id="dispatching_sco" name="dispatching_sco" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="dispatching_name" class="form-label ui-label">Name</label>
                  <input type="text" value="" id="dispatching_name" name="dispatching_name" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="dispatching_address" class="form-label ui-label">Address</label>
                  <input type="text" value="" id="dispatching_address" name="dispatching_address" class="form-control" readonly>
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
                  <label for="dispatching_kwh_meter_request" class="form-label ui-label">kWh Meter Request</label>
                  <input type="text" value="" id="dispatching_kwh_meter_request" name="dispatching_kwh_meter_request" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="dispatching_type_of_meter" class="form-label ui-label">Type of Meter</label>
                  <input type="text" value="" id="dispatching_type_of_meter" name="dispatching_type_of_meter" class="form-control" readonly>
                </div>
                <div class="col-lg-4">
                  <label for="dispatching_serial_number" class="form-label ui-label">Serial Number</label>
                  <input type="text" value="" id="dispatching_serial_number" name="dispatching_serial_number" class="form-control" readonly>
                </div>
              </div>
            </div>
          </div>

          <!-- Crew -->
          <div class="card ui-card mb-3">
            <div class="card-header ui-card-header">
              <i class="fas fa-bolt me-2"></i>Crew
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-lg-6">
                  <label for="dispatching_kwh_meter_request" class="form-label ui-label">Crew Name</label>
                  <select id="crew_dispatched" class="form-control" name="crew_dispatched" required>
                    <option value="">--- Select Crew ---</option>
                    @foreach ($ref_employees as $employee)          
                      <option value="{{ $employee['id'] }}" id="">{{ $employee['full_name'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-6">
                  <label for="dispatching_date" class="form-label ui-label">Dispatch Date</label>
                  <input type="date" value="" id="dispatching_date" name="dispatching_date" class="form-control" readonly>
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