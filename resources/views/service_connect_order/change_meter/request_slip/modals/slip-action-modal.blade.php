<!-- Review Slip Modal: one form, footer buttons choose Accept (create CM) or Reject -->
<div class="modal fade" id="slipActionModal" tabindex="-1" aria-labelledby="slipActionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="slipActionModalLabel">Review Change Meter Request Slip</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- Single Form that handles both Accept and Reject -->
      <form id="slipActionForm" method="POST" action="">
        @csrf
        <input type="hidden" id="slipActionType" name="action_type" value=""> <!-- accept or reject -->
        <input type="hidden" id="requestSlipId" name="request_slip_id" value="">

        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          
          <!-- REQUEST SLIP SUMMARY -->
          <div class="card mb-3">
            <div class="card-header bg-light">
              <i class="fas fa-file-lines me-2"></i>Request Slip Details
            </div>
            <div class="card-body">
              <div class="row g-3">
                <div class="col-lg-4"><small class="text-muted d-block">Control No.</small><strong id="slip_control_no">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Date Requested</small><strong id="slip_created_at">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Requested By</small><strong id="slip_requested_by">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Created By</small><strong id="slip_created_by">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Reason for Change Meter</small><strong id="slip_reason">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Consumer Name</small><strong id="slip_full_name">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Contact No.</small><strong id="slip_contact_no">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Account No.</small><strong id="slip_account_no">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Meter No.</small><strong id="slip_meter_no">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Municipality</small><strong id="slip_municipality">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Barangay</small><strong id="slip_barangay">-</strong></div>
                <div class="col-lg-4"><small class="text-muted d-block">Sitio</small><strong id="slip_sitio">-</strong></div>
                <div class="col-lg-8"><small class="text-muted d-block">Landmark</small><strong id="slip_landmark">-</strong></div>
              </div>
            </div>
          </div>

          <!-- CREATE CM FORM (required only when accepting) -->
          <div id="cmFormSection">
            <!-- Account Lookup -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <i class="fas fa-magnifying-glass me-2"></i>Account Lookup
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-lg-4">
                    <label for="modal_electric_service_detail" class="form-label">Account Number <span class="text-danger">*</span></label>
                    <input type="text" id="modal_electric_service_detail" name="account_no" class="form-control" required>
                    <input type="hidden" id="modal_electric_service_detail_hidden" name="electric_service_detail" class="form-control" required>
                    <small class="form-text text-muted d-none" id="modal_account_locked_msg">This account is locked from the request slip</small>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_old_meter" class="form-label">Old Meter</label>
                    <input type="text" id="modal_old_meter" name="old_meter" class="form-control" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_consumer_type" class="form-label">Type</label>
                    <input type="text" id="modal_consumer_type" name="consumer_type" class="form-control" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_last_reading" class="form-label">Last Reading</label>
                    <input type="number" id="modal_last_reading" name="last_reading" class="form-control" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_reading_initial" class="form-label">Initial Reading</label>
                    <input type="number" id="modal_reading_initial" name="reading_initial" class="form-control">
                  </div>
                </div>
              </div>
            </div>

            <!-- Consumer Details -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <i class="fas fa-user me-2"></i>Consumer Details
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-lg-3">
                    <label for="modal_last_name" class="form-label">Last Name</label>
                    <input type="text" id="modal_last_name" name="last_name" class="form-control" required>
                  </div>
                  <div class="col-lg-3">
                    <label for="modal_first_name" class="form-label">First Name</label>
                    <input type="text" id="modal_first_name" name="first_name" class="form-control" required>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_membership_or" class="form-label">Membership OR</label>
                    <input type="text" id="modal_membership_or" name="membership_or" class="form-control" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_membership_date" class="form-label">Membership Date</label>
                    <input type="date" id="modal_membership_date" name="membership_date" class="form-control" readonly>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_meter_or_no" class="form-label">Meter OR #</label>
                    <input type="text" id="modal_meter_or_no" name="meter_or_no" class="form-control">
                  </div>
                </div>
                <div class="row g-3 mt-2">
                  <div class="col-lg-3">
                    <label for="modal_care_of" class="form-label">Care of</label>
                    <input type="text" id="modal_care_of" name="care_of" class="form-control">
                  </div>
                  <div class="col-lg-3">
                    <label for="modal_contact_no" class="form-label">Contact No.</label>
                    <input type="text" id="modal_contact_no" name="contact_no" class="form-control">
                  </div>
                  <div class="col-lg-3">
                    <label for="modal_email" class="form-label">Email Address</label>
                    <input type="email" id="modal_email" name="email" class="form-control">
                  </div>
                  <div class="col-lg-3">
                    <label for="modal_process_date" class="form-label">Process Date <span class="text-danger">*</span></label>
                    <input type="date" id="modal_process_date" name="process_date" class="form-control" required>
                  </div>
                </div>
              </div>
            </div>

            <!-- Location Details -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <i class="fas fa-location-dot me-2"></i>Location Details
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-lg-2">
                    <label for="modal_area" class="form-label">Area <span class="text-danger">*</span></label>
                    <select id="modal_area" class="form-control" name="area_id" required></select>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_feeder" class="form-label">Feeder <span class="text-danger">*</span></label>
                    <select id="modal_feeder" class="form-control" name="feeder_id" required></select>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_municipality" class="form-label">Municipality <span class="text-danger">*</span></label>
                    <select id="modal_municipality" class="form-control" name="municipality" required></select>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_barangay" class="form-label">Barangay <span class="text-danger">*</span></label>
                    <select id="modal_barangay" class="form-control" name="barangay" required></select>
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_sitio" class="form-label">Sitio</label>
                    <input type="text" id="modal_sitio" name="sitio" class="form-control">
                  </div>
                  <div class="col-lg-2">
                    <label for="modal_location" class="form-label">Landmark <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="location" id="modal_location" required>
                  </div>
                </div>
              </div>
            </div>

            <!-- Remarks -->
            <div class="card mb-3">
              <div class="card-header bg-light">
                <i class="fas fa-note-sticky me-2"></i>Remarks
              </div>
              <div class="card-body">
                <textarea class="form-control" name="remarks" id="modal_remarks" rows="3"></textarea>
              </div>
            </div>
          </div>

          <!-- REJECTION REASON (required only when rejecting) -->
          <div class="card">
            <div class="card-header bg-light">
              <i class="fas fa-times-circle me-2"></i>Rejection Reason
            </div>
            <div class="card-body">
              <label for="rejection_reason" class="form-label">Why are you rejecting this request? <small class="text-muted">(required only when rejecting)</small></label>
              <textarea id="rejection_reason" name="rejection_reason" class="form-control" rows="3"></textarea>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="rejectButton" class="btn btn-warning">
            <i class="fas fa-times me-1"></i>Reject Request
          </button>
          <button type="button" id="acceptButton" class="btn btn-success">
            <i class="fas fa-check me-1"></i>Accept
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
