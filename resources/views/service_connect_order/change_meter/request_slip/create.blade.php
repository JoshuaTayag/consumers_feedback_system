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

    <form action="{{ route('change-meter-request-slip.store') }}" method="POST">
      @csrf

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
                  <label for="account_number" class="form-label ui-label">Account Number <span
                      class="text-danger">*</span></label>
                  <select class="form-control" id="account_number" name="account_number" style="width: 100%"
                    required></select>
                  <div id="account_number_error" class="invalid-feedback d-block" style="display:none !important;"></div>
                </div>
                <div class="col-lg-3">
                  <label for="last_name" class="form-label ui-label">Last Name <span class="text-danger">*</span></label>
                  <input type="text" id="last_name" name="last_name" class="form-control" required
                    value="{{ old('last_name') }}">
                </div>
                <div class="col-lg-3">
                  <label for="first_name" class="form-label ui-label">First Name <span
                      class="text-danger">*</span></label>
                  <input type="text" id="first_name" name="first_name" class="form-control" required
                    value="{{ old('first_name') }}">
                </div>
                <div class="col-lg-3">
                  <label for="contact_no" class="form-label ui-label">Contact No.</label>
                  <input type="text" id="contact_no" name="contact_no" class="form-control"
                    value="{{ old('contact_no') }}">
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
                  <label for="municipality_id" class="form-label ui-label">Municipality <span
                      class="text-danger">*</span></label>
                  <select id="municipality_id" class="form-control" name="municipality_id" required>
                    <option value=""></option>
                    @foreach ($municipalities as $municipality)
                      <option value="{{ $municipality->id }}" id="{{ $municipality->id }}"
                        {{ old('municipality_id') == $municipality->id ? 'selected' : '' }}>
                        {{ $municipality->municipality_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-lg-2">
                  <label for="barangay_id" class="form-label ui-label">Barangay <span class="text-danger">*</span></label>
                  <select id="barangay_id" class="form-control" name="barangay_id" required></select>
                </div>
                <div class="col-lg-2">
                  <label for="sitio" class="form-label ui-label">Sitio <span class="text-danger">*</span></label>
                  <input type="text" id="sitio" name="sitio" class="form-control" value="{{ old('sitio') }}" required>
                </div>
                <div class="col-lg-6">
                  <label for="landmark" class="form-label ui-label">Landmark <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" name="landmark" id="landmark" required
                    value="{{ old('landmark') }}">
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
                      <label for="meter_no" class="form-label ui-label">Meter No. <span
                          class="text-danger">*</span></label>
                      <input type="text" id="meter_no" name="meter_no" class="form-control" required
                        value="{{ old('meter_no') }}" readonly>
                    </div>
                    <div class="col-lg-2">
                      <label for="reading" class="form-label ui-label">Reading</label>
                      <input type="text" id="reading" name="reading" class="form-control"
                        value="{{ old('reading') }}">
                    </div>
                    <div class="col-lg-3">
                      <label for="requested_by" class="form-label ui-label">Requested By <span class="text-danger">*</span></label>
                      <select id="requested_by" class="form-control" name="requested_by" required>
                        <option value="">Select Employee</option>
                        @foreach ($employees as $employee)
                          <option value="{{ $employee->fullname }}" id="{{ $employee->fullname }}"
                            {{ old('requested_by') == $employee->fullname ? 'selected' : '' }}>
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
                            {{ old('damage_cause') == $damageCause->name ? 'selected' : '' }}>
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
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $("#account_number").select2({
      ajax: {
        url: "{{ route('cmFetchAccounts') }}",
        type: "get",
        dataType: 'json',
        data: function(params) {
          return {
            search: params.term, // search term
            page: params.page
          };
        },
        processResults: function(results, params) {
          params.page = params.page || 1;

          return {
            results: results.data,
            pagination: {
              more: results.last_page != params.page
            },
          }
        },
        cache: true
      },
      templateResult: templateResult,
      templateSelection: templateSelection,
    });

    $('#account_number').on('change', function() {
      const accountNo = $(this).val();
      const $error = $('#account_number_error');
      const $container = $(this).next('.select2-container').find('.select2-selection');

      $error.hide().text('');
      $container.css('border-color', '');
      $('#submit_btn').prop('disabled', false);

      if (!accountNo) return;

      $.get("{{ route('change-meter-request-slip.check-account') }}", {
          account_number: accountNo
        })
        .done(function(res) {
          if (res.exists) {
            $error.text(res.message).show();
            $container.css('border-color', '#dc3545');
            $('#submit_btn').prop('disabled', true);
          }
        });
    });

    function templateResult(data) {
      if (data.loading) {
        return data.text
      }
      return data.id + " | " + data.Name
    }

    function templateSelection(data) {
      // Handle splitting the name
      var fullName = data.Name || "";
      var parts = fullName.split(',');

      var l_name = "";
      var f_name = "";

      if (parts.length > 1) {
        // With comma → Last, First
        l_name = parts[0].trim();
        f_name = parts[1].trim();
      } else {
        // No comma → put entire text in FIRST NAME
        f_name = fullName.trim();
        l_name = "N/A";
      }


      document.getElementById('last_name').value = l_name;
      document.getElementById('first_name').value = f_name;


      document.getElementById('meter_no').value = (data['Serial No'] || "").trim();

      return data.id + " | " + data.Name;
    }

    $('#municipality_id').on('change', function() {
      var id = $(this).children(":selected").attr("id");
      $("#barangay_id").html('');
      $.ajax({
        url: "{{ url('api/fetch-barangays') }}",
        type: "POST",
        data: {
          municipality_id: id,
          _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function(res) {
          $('#barangay_id').html('<option value="">-- Select Barangay --</option>');
          $.each(res.barangays, function(key, value) {
            $("#barangay_id").append('<option value="' + value
              .id + '" id="' + value.id + '">' + value.barangay_name + '</option>');
          });
        }
      });
    });
  </script>
@endsection

@section('style')
  <link rel="stylesheet" href="{{ asset('css/ui-form-design.css') }}">
@endsection
