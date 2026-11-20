<script>
    // ==================== REVIEW BUTTON HANDLER (fills the single Accept/Reject modal) ====================
    document.querySelectorAll('.review-cm-slip').forEach(button => {
      button.addEventListener('click', function() {
            const accountNumber = this.getAttribute('data-account-number');
            const firstName = this.getAttribute('data-first-name');
            const lastName = this.getAttribute('data-last-name');
            const contactNo = this.getAttribute('data-contact-no');
            const meterNo = this.getAttribute('data-meter-no');
            const municipalityId = this.getAttribute('data-municipality-id');
            const barangayId = this.getAttribute('data-barangay-id');
            const sitio = this.getAttribute('data-sitio');
            const landmark = this.getAttribute('data-landmark');
            const requestSlipId = this.getAttribute('data-slip-id');
            const damageCause = this.getAttribute('data-reason');
            const reading = this.getAttribute('data-reading');

            document.getElementById('slipActionType').value = '';
            document.getElementById('requestSlipId').value = requestSlipId;
            document.getElementById('slipActionForm').dataset.rejectAction = this.getAttribute('data-reject-action');

            // Slip summary for easy identification
            const slipDetails = {
              slip_control_no: 'data-control-no',
              slip_created_at: 'data-created-at',
              slip_requested_by: 'data-requested-by',
              slip_created_by: 'data-created-by',
              slip_reason: 'data-reason',
              slip_full_name: 'data-full-name',
              slip_contact_no: 'data-contact-no',
              slip_account_no: 'data-account-number',
              slip_meter_no: 'data-meter-no',
              slip_municipality: 'data-municipality',
              slip_barangay: 'data-barangay',
              slip_sitio: 'data-sitio',
              slip_landmark: 'data-landmark'
            };
            Object.entries(slipDetails).forEach(([id, attr]) => {
              document.getElementById(id).textContent = this.getAttribute(attr) || '-';
            });

            // Pre-fill form
            document.getElementById('modal_electric_service_detail').value = accountNumber;
            document.getElementById('modal_electric_service_detail').disabled = true;
            document.getElementById('modal_electric_service_detail_hidden').value = accountNumber;
            document.getElementById('modal_account_locked_msg').classList.remove('d-none');
            document.getElementById('modal_process_date').value = new Date().toISOString().split('T')[0];
            
            document.getElementById('modal_first_name').value = firstName;
            document.getElementById('modal_last_name').value = lastName;
            document.getElementById('modal_contact_no').value = contactNo;
            document.getElementById('modal_old_meter').value = meterNo;
            document.getElementById('modal_last_reading').value = reading || '';
            
            document.getElementById('modal_municipality').value = municipalityId || '';
            document.getElementById('modal_barangay').dataset.preselect = barangayId || '';
            document.getElementById('modal_sitio').value = sitio || '';
            document.getElementById('modal_location').value = landmark || '';
            document.getElementById('modal_remarks').value = damageCause || '';
            
            if (municipalityId) {
              document.getElementById('modal_municipality').dispatchEvent(new Event('change'));
            }
            
            fetch(`/api/areas`)
              .then(response => response.json())
              .then(data => {
                if (data.data && data.data.length > 0) {
                  document.getElementById('modal_area').value = data.data[0].id || '';
                  document.getElementById('modal_area').dispatchEvent(new Event('change'));
                }
              })
              .catch(error => console.error('Error loading areas:', error));

            // Modal is opened by the button's data-bs-toggle attribute
          });
        });

        // ==================== FOOTER BUTTONS (Accept / Reject) ====================
        const slipActionForm = document.getElementById('slipActionForm');

        // Enter key must not submit without choosing Accept or Reject
        slipActionForm.addEventListener('submit', function(e) {
          e.preventDefault();
        });

        document.getElementById('rejectButton').addEventListener('click', function() {
          if (!document.getElementById('rejection_reason').value.trim()) {
            Swal.fire('Error', 'Please enter a rejection reason', 'error');
            return;
          }

          document.getElementById('slipActionType').value = 'reject';
          slipActionForm.action = slipActionForm.dataset.rejectAction;
          slipActionForm.submit();
        });

        document.getElementById('acceptButton').addEventListener('click', function() {
          const accountNo = document.getElementById('modal_electric_service_detail').value.trim();
          const area = document.getElementById('modal_area').value;
          const feeder = document.getElementById('modal_feeder').value;
          const municipality = document.getElementById('modal_municipality').value;
          const barangay = document.getElementById('modal_barangay').value;
          const landmark = document.getElementById('modal_location').value.trim();
          const processDate = document.getElementById('modal_process_date').value;

          if (!accountNo) {
            Swal.fire('Error', 'Please select an account number', 'error');
            return;
          }
          if (!area) {
            Swal.fire('Error', 'Please select an area', 'error');
            return;
          }
          if (!feeder) {
            Swal.fire('Error', 'Please select a feeder', 'error');
            return;
          }
          if (!municipality) {
            Swal.fire('Error', 'Please select a municipality', 'error');
            return;
          }
          if (!barangay) {
            Swal.fire('Error', 'Please select a barangay', 'error');
            return;
          }
          if (!landmark) {
            Swal.fire('Error', 'Please enter a landmark', 'error');
            return;
          }
          if (!processDate) {
            Swal.fire('Error', 'Please select a process date', 'error');
            return;
          }
          // Remaining native `required` fields (e.g. first/last name)
          if (!slipActionForm.reportValidity()) {
            return;
          }

          document.getElementById('slipActionType').value = 'accept';
          slipActionForm.action = '{{ route("storeCM") }}';
          slipActionForm.submit();
        });

        // ==================== DELETE SLIP HANDLER ====================
        document.querySelectorAll('.delete-cm-slip').forEach(function(el) {
          el.addEventListener('click', function(e) {
            e.preventDefault();
            const href = this.getAttribute('href');
            Swal.fire({
              title: 'Are you sure?',
              text: "This action cannot be undone.",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = href;
                    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
                                    '<input type="hidden" name="_method" value="DELETE">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
          });
        });

        // ==================== VIEW SLIP HANDLER ====================
        const viewFormatters = {
          date: value => new Date(value).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
          datetime: value => new Date(value).toLocaleString('en-US', { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: '2-digit' })
        };

        function fillViewFields(selector, attr, source) {
          document.querySelectorAll(selector).forEach(function(el) {
            const value = source ? source[el.dataset[attr]] : null;
            const format = viewFormatters[el.dataset.type];
            el.textContent = (value !== null && value !== undefined && value !== '') ? (format ? format(value) : value) : '-';
          });
        }

        function fullName(person) {
          return [person.first_name, person.middle_name, person.last_name].filter(Boolean).join(' ') || '-';
        }

        function cmStatusBadge(status) {
          if (status === null || status === undefined) return '<span class="badge bg-primary p-2">UNACTED</span>';
          if (Number(status) === 1) return '<span class="badge bg-danger p-2">ACTED - NOT COMPLETED</span>';
          if (Number(status) === 2) return '<span class="badge bg-success p-2">ACTED - COMPLETED</span>';
          if (Number(status) === 3) return '<span class="badge bg-warning text-dark p-2">DISPATCHED</span>';
          return '';
        }

        document.querySelectorAll('.view-cm-slip').forEach(function(button) {
          button.addEventListener('click', function() {
            const slipId = this.getAttribute('data-slip-id');

            // Clear previous data while the new slip loads
            fillViewFields('[data-slip]', 'slip', null);
            fillViewFields('[data-cm]', 'cm', null);
            document.getElementById('view_status').innerHTML = '';
            document.getElementById('view_cm_status').innerHTML = '';
            document.getElementById('view_rejection_card').classList.add('d-none');
            document.getElementById('view_cm_details').classList.add('d-none');
            document.getElementById('view_cm_empty').classList.remove('d-none');

            fetch(`/api/slips/${slipId}`)
              .then(response => {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
              })
              .then(data => {
                const slip = data.slip;
                const cm = slip.change_meter_request;

                // Slip details
                fillViewFields('[data-slip]', 'slip', slip);
                document.getElementById('view_slip_full_name').textContent = fullName(slip);
                document.getElementById('view_slip_municipality').textContent = slip.municipality?.municipality_name || '-';
                document.getElementById('view_slip_barangay').textContent = slip.barangay?.barangay_name || '-';

                let statusText = '';
                if (Number(slip.status) === 1) statusText = '<span class="badge bg-warning text-dark">Pending</span>';
                else if (Number(slip.status) === 2) statusText = '<span class="badge bg-success">Accepted</span>';
                else if (Number(slip.status) === 3) statusText = '<span class="badge bg-danger">Rejected</span>';
                document.getElementById('view_status').innerHTML = statusText;

                if (Number(slip.status) === 3 && slip.rejection_remarks) {
                  document.getElementById('view_rejection_remarks').textContent = slip.rejection_remarks;
                  document.getElementById('view_rejection_card').classList.remove('d-none');
                }

                // Change meter request details
                if (cm) {
                  document.getElementById('view_cm_empty').classList.add('d-none');
                  document.getElementById('view_cm_details').classList.remove('d-none');
                  document.getElementById('view_cm_status').innerHTML = cmStatusBadge(cm.status);

                  fillViewFields('[data-cm]', 'cm', cm);
                  document.getElementById('view_cm_full_name').textContent = fullName(cm);
                  document.getElementById('view_cm_area').textContent = cm.area?.name || '-';
                  document.getElementById('view_cm_feeder').textContent = cm.feeder?.feeder_name || '-';
                  document.getElementById('view_cm_address').textContent = [
                    cm.sitio ? cm.sitio.toUpperCase() : null,
                    cm.barangay ? 'BRGY. ' + cm.barangay.barangay_name : null,
                    cm.municipality?.municipality_name
                  ].filter(Boolean).join(', ') || '-';
                }
              })
              .catch(error => {
                console.error('Error fetching slip:', error);
                Swal.fire('Error', 'Failed to load slip details', 'error');
              });
          });
        });

        // ==================== DROPDOWN INITIALIZATION ====================
        const areaSelect = document.getElementById('modal_area');
        const municipalitySelect = document.getElementById('modal_municipality');

        // Fetch Areas
        fetch('/api/areas')
          .then(response => response.json())
          .then(data => {
            areaSelect.innerHTML = '<option value="">Select Area</option>';
            if (data.data) {
              data.data.forEach(area => {
                const option = document.createElement('option');
                option.value = area.id;
                option.textContent = area.name;
                areaSelect.appendChild(option);
              });
            }
          })
          .catch(error => console.error('Error loading areas:', error));

        // Fetch Municipalities (existing address endpoint, no district filter)
        fetch('{{ url("api/fetch-municipalities") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: JSON.stringify({ all: true })
        })
          .then(response => response.json())
          .then(data => {
            municipalitySelect.innerHTML = '<option value="">Select Municipality</option>';
            if (data.municipalities) {
              data.municipalities.forEach(municipality => {
                const option = document.createElement('option');
                option.value = municipality.id;
                option.textContent = municipality.municipality_name;
                municipalitySelect.appendChild(option);
              });
            }
          })
          .catch(error => console.error('Error loading municipalities:', error));

        // ==================== AREA CHANGE HANDLER ====================
        document.getElementById('modal_area').addEventListener('change', function() {
          const areaId = this.value;
          const feederSelect = document.getElementById('modal_feeder');
          
          if (!areaId) {
            feederSelect.innerHTML = '<option value="">Select Feeder</option>';
            return;
          }

          fetch('/api/feeders?area_id=' + areaId)
            .then(response => response.json())
            .then(data => {
              feederSelect.innerHTML = '<option value="">Select Feeder</option>';
              if (data.data) {
                data.data.forEach(feeder => {
                  const option = document.createElement('option');
                  option.value = feeder.id;
                  option.textContent = feeder.feeder_name;
                  feederSelect.appendChild(option);
                });
              }
            })
            .catch(error => {
              console.error('Error fetching feeders:', error);
              feederSelect.innerHTML = '<option value="">Error loading feeders</option>';
            });
        });

        // ==================== MUNICIPALITY CHANGE HANDLER ====================
        document.getElementById('modal_municipality').addEventListener('change', function() {
          const municipalityId = this.value;
          const barangaySelect = document.getElementById('modal_barangay');
          
          if (!municipalityId) {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            return;
          }

          fetch('{{ route("fetchBarangays") }}', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ municipality_id: municipalityId })
          })
          .then(response => response.json())
          .then(data => {
            barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
            if (data.barangays) {
              data.barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay.id;
                option.textContent = barangay.barangay_name;
                barangaySelect.appendChild(option);
              });
              if (barangaySelect.dataset.preselect) {
                barangaySelect.value = barangaySelect.dataset.preselect;
                barangaySelect.dataset.preselect = '';
              }
            }
          })
          .catch(error => {
            console.error('Error fetching barangays:', error);
            barangaySelect.innerHTML = '<option value="">Error loading barangays</option>';
          });
        });

        // ==================== MODAL RESET HANDLER ====================
        document.getElementById('slipActionModal').addEventListener('hidden.bs.modal', function() {
          // Clear all form fields
          document.getElementById('slipActionForm').reset();
          
          // Clear hidden fields
          document.getElementById('slipActionType').value = '';
          document.getElementById('requestSlipId').value = '';
          
          // Reset dropdowns
          document.getElementById('modal_feeder').innerHTML = '<option value="">Select Feeder</option>';
          document.getElementById('modal_barangay').innerHTML = '<option value="">Select Barangay</option>';
          
          // Re-enable account field
          document.getElementById('modal_electric_service_detail').disabled = false;
          document.getElementById('modal_account_locked_msg').classList.add('d-none');
        });
</script>
