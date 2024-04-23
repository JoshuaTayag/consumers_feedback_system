@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">Ledger</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row" id="show_data">
                <form action="{{ route('ledger.search') }}" method="GET">
                  <div class="row p-3">
                    <div class="col-lg-3">
                        <input type="text" placeholder="Search by Account No." id="search_account_no" name="account_no" class="form-control" value="{{ request('account_no') }}">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" placeholder="Search by Name" id="search_name" name="account_name" class="form-control" value="{{ request('account_name') }}">
                    </div>
                    <div class="col-lg-3">
                        <input type="text" placeholder="Search by Meter No" id="search_serial_no" name="serial_no" class="form-control" value="{{ request('serial_no') }}">
                    </div>
                    <div class="col-lg-3">
                      <button type="submit" class="btn btn-info"><i class="fa fa-search"></i> Search</button>
                      <button type="button" class="btn btn-info" onclick="clearSearch()"> <i class="fa fa-eraser"></i> Clear</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <div class="card mt-4">
            <div class="card-header fs-5">
              Account Details
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Account No.  </label>
                    <input class="form-control form-control-sm" name="account_name" id="account_name" value="{{ isset($account) && $account !== null ? $account->{'Accnt No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="mb-2">
                    <label class="form-label mb-1">Name </label>
                    <input class="form-control form-control-sm" name="account_name" id="account_name" value="{{ isset($account) && $account !== null ? $account->{'Name'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="mb-2">
                    <label class="form-label mb-1">Address </label>
                    <input class="form-control form-control-sm" name="address" id="address" value="{{ isset($account) && $account !== null ? $account->{'Address'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Type </label>
                    <input class="form-control form-control-sm" name="type" id="type" value="{{ isset($account) && $account !== null ? $account->{'Cons Type'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Present SR# </label>
                    <input class="form-control form-control-sm" name="serial_no" id="serial_no" value="{{ isset($account) && $account !== null ? $account->{'Serial No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">Brand </label>
                    <input class="form-control form-control-sm" name="brand" id="brand" value="{{ isset($account) && $account !== null ? $account->{'Brand'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="mb-2">
                    <label class="form-label mb-1">NOMA </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Noma'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Latest Reading Date </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'LatestDateRdng'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Sequence No. </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Seq-No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Book No. </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Book No'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Date Of Entry </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account !== null ? $account->{'Date'} : ''}}" readonly>
                  </div>
                </div>
                <div class="col">
                  <div class="mb-2">
                    <label class="form-label mb-1">Active </label>
                    <input class="form-control form-control-sm" name="name" id="name" value="{{ isset($account) && $account->{'Acct Stat'} !== null ? 'Yes' : 'No'}}" readonly>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card mt-4">
            <div class="card-header fs-5">
              Account Details
            </div>
            <div class="card-body">
              <table class="table table-striped table-bordered border-primary">
                <thead>
                  <tr>
                    <td>Date</td>
                    <!-- <td>Account No</td> -->
                    <td>Previous Reading</td>
                    <td>Present Reading</td>
                    <td>KHW Used</td>
                    <td>Amount</td>
                    <td>Billed By</td>
                    <td>Due Date</td>
                    <td>Collection Outlet</td>
                  </tr>
                </thead>
                <tbody>
                  @if(isset($ledger_history))
                    @foreach ($ledger_history as $key => $history)
                      <tr>
                        <td>{{ date('F j, Y', strtotime($history->BillDate)) }}</td>
                        <!-- <td>{{ $history->{'Account No'} }}</td> -->
                        <td>{{ $history->HisPrev }}</td>
                        <td>{{ $history->{'Present Reading'} }}</td>
                        <td>{{ $history->{'KWH Used'} }}</td>
                        <td>{{ $history->{'BillAmt'} }}</td>
                        <td>{{ $history->{'Billed'} }}</td>
                        <td>{{ date('F j, Y', strtotime($history->DueDate)) }}</td>
                        <td></td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
      </div>
  </div>
</div>
@endsection
@section('script')
<script>
  function clearSearch() {
    $('#search_account_no').val('');
    $('#search_name').val('');
    $('#search_serial_no').val('');
  }
</script>
@endsection