@extends('layouts.app')


@section('content')
<div class="container">
  <!-- Dashboard for slip status -->
  <div class="row cmd-stats">
      <!-- Pending Slips Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--unacted">
              <div class="cmd-stat-icon"><i class="fas fa-hourglass-half"></i></div>
              <div class="flex-grow-1 text-center">
                  <div class="cmd-stat-label">Pending Slips</div>
                  <div class="cmd-stat-value">{{ $slip_status_count['total']['pending'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown justify-content-between">
                      <span>Today<strong>{{ $slip_status_count['today']['pending'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $slip_status_count['yesterday']['pending'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $slip_status_count['old_transactions']['pending'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>

      <!-- Accepted Slips Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--completed">
              <div class="cmd-stat-icon"><i class="fas fa-check-circle"></i></div>
              <div class="flex-grow-1 text-center">
                  <div class="cmd-stat-label">Accepted Slips</div>
                  <div class="cmd-stat-value">{{ $slip_status_count['total']['accepted'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown justify-content-between">
                      <span>Today<strong>{{ $slip_status_count['today']['accepted'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $slip_status_count['yesterday']['accepted'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $slip_status_count['old_transactions']['accepted'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>

      <!-- Rejected Slips Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--progress">
              <div class="cmd-stat-icon"><i class="fas fa-times-circle"></i></div>
              <div class="flex-grow-1 text-center">
                  <div class="cmd-stat-label">Rejected Slips</div>
                  <div class="cmd-stat-value">{{ $slip_status_count['total']['rejected'] ?? 0 }}</div>
                  <div class="cmd-stat-breakdown justify-content-between">
                      <span>Today<strong>{{ $slip_status_count['today']['rejected'] ?? 0 }}</strong></span>
                      <span>Yesterday<strong>{{ $slip_status_count['yesterday']['rejected'] ?? 0 }}</strong></span>
                      <span>Older<strong>{{ $slip_status_count['old_transactions']['rejected'] ?? 0 }}</strong></span>
                  </div>
              </div>
          </div>
      </div>

      <!-- Total Slips Card -->
      <div class="col-xl-3 col-md-6 mb-3">
          <div class="cmd-stat-card cmd-stat-card--dispatched">
              <div class="cmd-stat-icon"><i class="fas fa-list"></i></div>
              <div class="flex-grow-1 text-center">
                  <div class="cmd-stat-label">Total Slips</div>
                  <div class="cmd-stat-value">{{ ($slip_status_count['total']['pending'] ?? 0) + ($slip_status_count['total']['accepted'] ?? 0) + ($slip_status_count['total']['rejected'] ?? 0) }}</div>
                  <div class="cmd-stat-breakdown justify-content-between">
                      <span>Today<strong>{{ ($slip_status_count['today']['pending'] ?? 0) + ($slip_status_count['today']['accepted'] ?? 0) + ($slip_status_count['today']['rejected'] ?? 0) }}</strong></span>
                      <span>Yesterday<strong>{{ ($slip_status_count['yesterday']['pending'] ?? 0) + ($slip_status_count['yesterday']['accepted'] ?? 0) + ($slip_status_count['yesterday']['rejected'] ?? 0) }}</strong></span>
                      <span>Older<strong>{{ ($slip_status_count['old_transactions']['pending'] ?? 0) + ($slip_status_count['old_transactions']['accepted'] ?? 0) + ($slip_status_count['old_transactions']['rejected'] ?? 0) }}</strong></span>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Toolbar: title, actions, search + filter -->
  <div class="cmd-toolbar">
    <div class="cmd-toolbar-top">
        <h1 class="cmd-toolbar-title">Change Meter Request Slips</h1>
        <div class="cmd-toolbar-actions">
          <a class="btn btn-sm btn-success" href="{{ route('change-meter-request.index') }}" target="_blank"><i class="fa fa-file-alt"></i> CM Requests</a>
          @can('change-meter-request-slip-create')
            <a class="btn btn-sm ui-btn-cta" href="{{ route('change-meter-request-slip.create') }}"><i class="fa fa-save"></i> Create New Slip</a>
          @endcan
        </div>
    </div>
    <div class="cmd-toolbar-filters">
      <div class="cmd-search">
          <input type="text" placeholder="Search by Control No. / Name / Account No. / Contact No." id="search" name="search" class="form-control">
      </div>
    </div>
  </div>

  <!-- Data Table -->
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Request Slip Records</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-striped table-bordered border-primary">
                <thead>
                  <tr>
                    <th>Control No.</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contact No.</th>
                    <th>Account No.</th>
                    <th>Meter No.</th>
                    <th>Reason for Change Meter</th>
                    <th>Status</th>
                    <th>Rejection Remarks</th>
                    <th>CM Control No.</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="show_data">
                  @foreach ($change_meter_request_slips as $request_slip)
                    <tr>
                      <td>{{ $request_slip->control_no }}</td>
                      <td>{{ $request_slip->complete_name }}</td>
                      <td>{{ $request_slip->address }}</td>
                      <td>{{ $request_slip->contact_no }}</td>
                      <td>{{ $request_slip->account_number }}</td>
                      <td>{{ $request_slip->meter_no }}</td>
                      <td>{{ $request_slip->reason_for_change_meter }}</td>
                      <th>
                        @if($request_slip->status == 2)
                          <span class="badge bg-success p-2">Accepted</span>
                        @elseif($request_slip->status == 3)
                          <span class="badge bg-danger p-2">Rejected</span>
                        @else
                          <span class="badge bg-warning p-2">Pending</span>
                        @endif
                      </th>
                      <td>{{ $request_slip->status == 3 ? $request_slip->rejection_remarks ?? 'N/A' : 'N/A' }}</td>
                      <td>{{ $request_slip->change_meter_request_id ? $request_slip->changeMeterRequest->control_no : 'N/A' }}</td>
                      <td>
                        <div class="dropdown">
                          <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Actions
                          </button>
                          <div class="dropdown-menu dropdown-menu-end p-2">
                            <div class="d-grid gap-2">
                              @if($request_slip->status == 1 && $request_slip->change_meter_request_id == null)
                                @canany(['change-meter-request-edit', 'change-meter-request-delete'])
                                  <button type="button" class="btn btn-sm btn-info text-white review-cm-slip" data-bs-toggle="modal" data-bs-target="#slipActionModal"
                                    data-slip-id="{{ $request_slip->id }}"
                                    data-reject-action="{{ route('change-meter-request-slip.reject', $request_slip->id) }}"
                                    data-control-no="{{ $request_slip->control_no }}"
                                    data-created-at="{{ optional($request_slip->created_at)->format('M d, Y') }}"
                                    data-reason="{{ $request_slip->reason_for_change_meter }}"
                                    data-requested-by="{{ $request_slip->requested_by }}"
                                    data-created-by="{{ $request_slip->created_name }}"
                                    data-full-name="{{ $request_slip->complete_name }}"
                                    data-account-number="{{ $request_slip->account_number }}"
                                    data-first-name="{{ $request_slip->first_name }}"
                                    data-last-name="{{ $request_slip->last_name }}"
                                    data-contact-no="{{ $request_slip->contact_no }}"
                                    data-meter-no="{{ $request_slip->meter_no }}"
                                    data-municipality-id="{{ $request_slip->municipality_id }}"
                                    data-municipality="{{ optional($request_slip->municipality)->municipality_name }}"
                                    data-barangay-id="{{ $request_slip->barangay_id }}"
                                    data-barangay="{{ optional($request_slip->barangay)->barangay_name }}"
                                    data-sitio="{{ $request_slip->sitio }}"
                                    data-reading="{{ $request_slip->reading }}"
                                    data-landmark="{{ $request_slip->landmark }}">
                                    <i class="fas fa-clipboard-check"></i> Review Request
                                  </button>
                                @endcanany
                                @can('change-meter-request-edit')
                                  <a href="{{ route('change-meter-request-slip.edit', $request_slip->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                @endcan
                                @can('change-meter-request-delete')
                                  <a class="btn btn-sm btn-danger delete-cm-slip" href="{{ route('change-meter-request-slip.destroy', $request_slip->id) }}"><i class="fas fa-trash"></i> Delete</a>
                                @endcan
                              @endif
                              <a class="btn btn-sm btn-secondary view-cm-slip" data-slip-id="{{ $request_slip->id }}" href="#" data-bs-toggle="modal" data-bs-target="#viewCmSlipModal"><i class="fas fa-eye"></i> View</a>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
               </table>
               <div id="pagination">{{ $change_meter_request_slips->links() }}</div>
              @include('service_connect_order.change_meter.request_slip.modals.slip-action-modal')
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('service_connect_order.change_meter.request_slip.modals.view-slip-modal')
  @endsection
  @section('style')
  <link rel="stylesheet" href="{{ asset('css/ui-form-design.css') }}">
  <link rel="stylesheet" href="{{ asset('css/change-meter-dashboard.css') }}">
  <style>
    .badge{
      line-height: 0.3;
    }
    /* a#disabled {
      pointer-events: none;
    } */
    a#disabled:hover {
      cursor: not-allowed !important;
    }
  </style>
  @endsection
  @section('script')
  @include('service_connect_order.change_meter.request_slip.modals.cms-index-script')
  @endsection