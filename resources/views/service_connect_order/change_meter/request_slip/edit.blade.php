@extends('layouts.app')

@section('content')
<div class="container ui-form-page">
  <!-- Page Header -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
      <h4 class="ui-form-title mb-0"><i class="fas fa-plug me-2"></i>e-Change Meter Request Slip</h4>
      <small class="text-muted">Fill in the account and consumer details to file a change meter request</small>
    </div>
    <a class="btn btn-sm btn-outline-secondary" href="{{ route('change-meter-request-slip.index') }}">
      <i class="fas fa-arrow-left me-1"></i> Back to List
    </a>
  </div>

  <form action="{{ route('change-meter-request-slip.update', $change_meter_request_slip->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="alert ui-alert-soft d-flex align-items-start mb-4">
      <i class="fas fa-circle-info me-2 mt-1"></i>
      <div>If the account number does not appear in the lookup, it already has a pending change meter request.</div>
    </div>

    <div class="row g-3">
      <div class="col-lg-12">

        <!-- Account Lookup -->
        <div class="card ui-card mb-3">
          <div class="card-header ui-card-header">
            <i class="fas fa-magnifying-glass me-2"></i>Account Lookup
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-3">
                <label for="account_number" class="form-label ui-label">Account Number <span class="text-danger">*</span></label>
                <input type="text" id="account_number" name="account_number" class="form-control" disabled value="{{old('account_number', $change_meter_request_slip->account_number)}}">
              </div>
              <div class="col-lg-3">
                <label for="last_name" class="form-label ui-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" id="last_name" name="last_name" class="form-control" required value="{{old('last_name', $change_meter_request_slip->last_name)}}">
              </div>
              <div class="col-lg-3">
                <label for="first_name" class="form-label ui-label">First Name <span class="text-danger">*</span></label>
                <input type="text" id="first_name" name="first_name" class="form-control" required value="{{old('first_name', $change_meter_request_slip->first_name)}}">
              </div>
              <div class="col-lg-3">
                <label for="contact_no" class="form-label ui-label">Contact No.</label>
                <input type="text" id="contact_no" name="contact_no" class="form-control" value="{{old('contact_no', $change_meter_request_slip->contact_no)}}">
              </div>
            </div>
          </div>
        </div>

        <!-- Location Details -->
        <div class="card ui-card mb-3">
          <div class="card-header ui-card-header">
            <i class="fas fa-location-dot me-2"></i>Location Details
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-lg-2">
                <label for="municipality_id" class="form-label ui-label">Municipality <span class="text-danger">*</span></label>
                <select id="municipality_id" class="form-control" name="municipality_id" required>
                  <option value=""></option>
                  @foreach ($municipalities as $municipality)
                    <option value="{{ $municipality->id }}" id="{{ $municipality->id }}" {{ old('municipality_id', $change_meter_request_slip->municipality_id) == $municipality->id ? 'selected' : '' }}>{{ $municipality->municipality_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2">
                <label for="barangay_id" class="form-label ui-label">Barangay <span class="text-danger">*</span></label>
                <select id="barangay_id" class="form-control" name="barangay_id" required>
                    <option value="{{ $change_meter_request_slip->barangay->id }}" id="{{ $change_meter_request_slip->barangay->id }}">{{$change_meter_request_slip->barangay->barangay_name }}</option>
                </select>
              </div>
              <div class="col-lg-2">
                <label for="sitio" class="form-label ui-label">Sitio <span class="text-danger">*</span></label>
                <input type="text" id="sitio" name="sitio" class="form-control" value="{{old('sitio', $change_meter_request_slip->sitio)}}" required>
              </div>
              <div class="col-lg-6">
                <label for="landmark" class="form-label ui-label">Landmark <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="landmark" id="landmark" required value="{{old('landmark', $change_meter_request_slip->landmark)}}">
              </div>
            </div>
          </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card ui-card mb-3 h-100">
                    <div class="card-header ui-card-header">
                        <i class="fas fa-note-sticky me-2"></i>Other Details
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-2">
                                <label for="meter_no" class="form-label ui-label">Meter No. <span class="text-danger">*</span></label>
                                <input type="text" id="meter_no" name="meter_no" class="form-control" required value="{{old('meter_no', $change_meter_request_slip->meter_no)}}">
                            </div>
                            <div class="col-lg-2">
                                <label for="reading" class="form-label ui-label">Reading</label>
                                <input type="text" id="reading" name="reading" class="form-control" value="{{old('reading', $change_meter_request_slip->reading)}}">
                            </div>
                            <div class="col-lg-3">
                              <label for="requested_by" class="form-label ui-label">Requested By <span class="text-danger">*</span></label>
                              <select id="requested_by" class="form-control" name="requested_by" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                  <option value="{{ $employee->fullname }}" id="{{ $employee->fullname }}"
                                    {{ old('requested_by', $change_meter_request_slip->requested_by) == $employee->fullname ? 'selected' : '' }}>
                                    {{ $employee->fullname }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="col-lg-3">
                              <label for="damage_cause" class="form-label ui-label">Damage Cause <span class="text-danger">*</span></label>
                              <select id="damage_cause" class="form-control" name="damage_cause" required>
                                <option value="">Select Damage Cause</option>
                                @foreach ($damageCauses as $damageCause)
                                  <option value="{{ $damageCause->name }}" id="{{ $damageCause->id }}"
                                    {{ old('damage_cause', $change_meter_request_slip->reason_for_change_meter) == $damageCause->name ? 'selected' : '' }}>
                                    {{ $damageCause->name }}</option>
                                @endforeach
                              </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

      </div>
    </div>
    <!-- Actions -->
    <div class="ui-form-actions text-end mt-3">
      <a class="btn btn-outline-secondary" href="{{ route('change-meter-request-slip.index') }}">
        <i class="fas fa-xmark me-1"></i>Cancel
      </a>
      <button type="submit" id="submit_btn" class="btn ui-btn-cta">
        <i class="fas fa-check me-1"></i>Submit Request
      </button>
    </div>
  </form>
</div>
@endsection

@section('script')
<script>
  // Removed select2 initialization for account_number as it is now a text input

  $('#municipality_id').on('change', function () {
      var id = $(this).children(":selected").attr("id");
      $("#barangay_id").html('');
      $.ajax({
          url: "{{url('api/fetch-barangays')}}",
          type: "POST",
          data: {
              municipality_id: id,
              _token: '{{csrf_token()}}'
          },
          dataType: 'json',
          success: function (res) {
              $('#barangay_id').html('<option value="">-- Select Barangay --</option>');
              $.each(res.barangays, function (key, value) {
                    $("#barangay_id").append('<option value="' + value
                        .id + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
              });
          }
      });
  });
</script>
@endsection

@section('style')
<link rel="stylesheet" href="{{ asset('css/ui-form-design.css') }}">
@endsection
