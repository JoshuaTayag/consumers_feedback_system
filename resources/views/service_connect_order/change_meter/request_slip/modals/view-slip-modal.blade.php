<!-- View Change Meter Request Slip Modal -->
<div class="modal fade" id="viewCmSlipModal" tabindex="-1" aria-labelledby="viewCmSlipModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewCmSlipModalLabel">
          <i class="fas fa-file-lines me-2"></i>Change Meter Request Slip
          <span id="view_status" class="ms-2"></span>
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <!-- ==================== SLIP DETAILS ==================== -->
        <div class="card mb-3">
          <div class="card-header bg-light fw-bold">
            <i class="fas fa-file-alt me-2"></i>Change Meter Request Slip Details
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-3"><small class="text-muted d-block">Control No.</small><strong data-slip="control_no">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Date Requested</small><strong data-slip="created_at" data-type="date">-</strong></div>
              <div class="col-lg-6"><small class="text-muted d-block">Reason for Change Meter</small><strong data-slip="reason_for_change_meter">-</strong></div>

              <div class="col-lg-3"><small class="text-muted d-block">Consumer Name</small><strong id="view_slip_full_name">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Contact No.</small><strong data-slip="contact_no">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Account No.</small><strong data-slip="account_number">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Old Meter No.</small><strong data-slip="meter_no">-</strong></div>

              <div class="col-lg-3"><small class="text-muted d-block">Municipality</small><strong id="view_slip_municipality">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Barangay</small><strong id="view_slip_barangay">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Sitio</small><strong data-slip="sitio">-</strong></div>
              <div class="col-lg-3"><small class="text-muted d-block">Landmark</small><strong data-slip="landmark">-</strong></div>
            </div>
          </div>
        </div>

        <!-- Rejection remarks (only when rejected) -->
        <div class="card mb-3 border-danger d-none" id="view_rejection_card">
          <div class="card-header bg-danger text-white fw-bold">
            <i class="fas fa-exclamation-circle me-2"></i>Rejection Remarks
          </div>
          <div class="card-body">
            <strong class="text-danger" id="view_rejection_remarks">-</strong>
          </div>
        </div>

        <!-- ==================== CHANGE METER REQUEST DETAILS ==================== -->
        <div class="card mb-3">
          <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span><i class="fas fa-bolt me-2"></i>Change Meter Request Details</span>
            <span id="view_cm_status"></span>
          </div>
          <div class="card-body">
            <p class="text-muted mb-0" id="view_cm_empty">No change meter request has been created from this slip yet.</p>

            <div id="view_cm_details" class="d-none">
              <div class="row g-3">
                <div class="col-lg-3"><small class="text-muted d-block">CM Control No.</small><strong data-cm="control_no">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Process Date</small><strong data-cm="process_date" data-type="date">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Date Dispatched</small><strong data-cm="dispatched_date" data-type="datetime">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Date Acted / Installed</small><strong data-cm="date_time_acted" data-type="datetime">-</strong></div>

                <div class="col-lg-3"><small class="text-muted d-block">Consumer Name</small><strong id="view_cm_full_name">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Care of</small><strong data-cm="care_of">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Contact No.</small><strong data-cm="contact_no">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Email</small><strong data-cm="email">-</strong></div>

                <div class="col-lg-3"><small class="text-muted d-block">Account No.</small><strong data-cm="account_number">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Consumer Type</small><strong data-cm="consumer_type">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Membership OR</small><strong data-cm="membership_or">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Meter OR No.</small><strong data-cm="meter_or_number">-</strong></div>

                <div class="col-lg-3"><small class="text-muted d-block">Old Meter No.</small><strong data-cm="old_meter_no">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">New Meter No.</small><strong data-cm="new_meter_no">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Last Reading</small><strong data-cm="last_reading">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Initial Reading</small><strong data-cm="initial_reading">-</strong></div>

                <div class="col-lg-3"><small class="text-muted d-block">Area</small><strong id="view_cm_area">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Feeder</small><strong id="view_cm_feeder">-</strong></div>
                <div class="col-lg-6"><small class="text-muted d-block">Address</small><strong id="view_cm_address">-</strong></div>

                <div class="col-lg-3"><small class="text-muted d-block">Landmark</small><strong data-cm="location">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Crew</small><strong data-cm="crew_full_name">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Remarks</small><strong data-cm="remarks">-</strong></div>
                <div class="col-lg-3"><small class="text-muted d-block">Crew Remarks</small><strong data-cm="crew_remarks">-</strong></div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
