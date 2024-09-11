@extends('layouts.app')

@section('content')
@php
  if(isset($cm_request)){
    $consumerTypes = collect(Config::get('constants.consumer_types'));
    $consumerType = $consumerTypes->firstWhere('id', $cm_request->consumer_type);
    $total_fees = $total_fees ?? 0;
  }
@endphp
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Change Meter Request</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}">MANUAL</a>
              </div>
          </div>
        </div>
        <div class="card-body" style="background-color: #fafafa">
          <form action="{{ route('cmTransactionSearch') }}" method="GET">
            <div class="row">
              <div class="col-lg-3">
                  <div class="mb-2">
                      {{ Form::label('or_no', 'OR No.') }}
                      {{ Form::text('or_no', request('or_no'), ['class' => 'form-control', 'required']) }}
                  </div>
              </div>
              <div class="col-lg-3">
                  <div class="mb-2">
                      {{ Form::label('control_no', 'Control Number') }}
                      {{ Form::select('control_no', ['' => 'Please select Control Number'] + $control_numbers->toArray(), request('control_no'), ['class' => 'form-control', 'required']) }}
                  </div>
              </div>
              <div class="col-lg-6 d-flex align-items-end">
                  <div class="mb-2">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
                  </div>
              </div>
            </div>
          </form>

          <div class="row">
            <div class="col-lg-6">
              <div class="card mt-4">
                <div class="card-header">
                  <h4>Transaction Details</h4>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="mb-2">
                        {{ Form::label('account_number', 'Account No.') }}
                        {{ Form::text('account_number', isset($cm_request) ? substr($cm_request->account_number, 0, 2) . '-' . substr($cm_request->account_number, 2, 4) . '-' . substr($cm_request->account_number, 6) : '', ['class' => 'form-control', 'readonly' => true]) }}
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="mb-2">
                        {{ Form::label('consumer_name', 'Consumer Name') }}
                        {{ Form::text('consumer_name', isset($cm_request) ? $cm_request->last_name.' '.$cm_request->first_name : '', ['class' => 'form-control', 'readonly' => true]) }}
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="mb-2">
                        {{ Form::label('application_type', 'Application Type') }}
                        {{ Form::text('address', isset($cm_request) ? $consumerType['name'] ?? 'Unknown Type' : '', ['class' => 'form-control', 'readonly' => true]) }}
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="mb-2">
                        {{ Form::label('process_date', 'Process Date') }}
                        {{ Form::date('process_date', isset($cm_request) ? $cm_request->created_at : '', ['class' => 'form-control', 'readonly' => true]) }}
                      </div>
                    </div>
                    <div class="col-lg-8">
                      <div class="mb-2">
                        {{ Form::label('address', 'Consumer Address') }}
                        {{ Form::text('address', isset($cm_request) ? $cm_request->sitio.', '.$cm_request->barangay->barangay_name.', '. $cm_request->municipality->municipality_name : '', ['class' => 'form-control', 'readonly' => true]) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="card mt-4">
                <div class="card-header">
                  <h4>Fees</h4>
                </div>
                <div class="card-body">
                  @if(isset($cm_request->cmr_fees) && $cm_request->cmr_fees->isNotEmpty())
                    @foreach($cm_request->cmr_fees as $cm_fees)
                      <div class="row">
                        <div class="col-lg-6 mb-1">
                          <div class="d-flex justify-content-between text-capitalize">
                              <span class="">{{ str_replace('_', ' ', $cm_fees->fees) }}</span><span class="text-end">:</span>
                          </div>
                        </div>
                        <div class="col-lg-6 mb-1">
                          <span class=""><span class="">{{ number_format($cm_fees->amount, 2, '.', ',') }}</span></span>
                        </div>
                      </div>
                      @php
                        $total_fees = $total_fees+$cm_fees->amount;
                      @endphp
                    @endforeach
                    <hr>
                    <form action="{{ route('change-meter-request-transact.store') }}" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-lg-6 mb-1">
                        </div>
                        <div class="col-lg-3 mb-1">
                          <!-- <span class=""><span class="">{{ number_format($total_fees, 2, '.', ',') }}</span></span> -->
                          {{ Form::hidden('control_no', request('control_no'), ['class' => 'form-control form-control-sm', 'readonly', 'id' => 'control_no']) }}
                          {{ Form::hidden('or_no', request('or_no'), ['class' => 'form-control form-control-sm', 'readonly', 'id' => 'or_no']) }}
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-6 mb-1">
                          <div class="d-flex justify-content-between text-capitalize">
                              <span class="">Total</span><span class="text-end">:</span>
                          </div>
                        </div>
                        <div class="col-lg-3 mb-1">
                          <!-- <span class=""><span class="">{{ number_format($total_fees, 2, '.', ',') }}</span></span> -->
                          {{ Form::text('total_fees', number_format($total_fees, 2, '.', ','), ['class' => 'form-control form-control-sm', 'readonly', 'id' => 'total_fees']) }}
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-6 mb-1">
                          <div class="d-flex justify-content-between text-capitalize">
                              <span class="">Amount Tendered</span><span class="text-end">:</span>
                          </div>
                        </div>
                        <div class="col-lg-3 mb-1">
                          <!-- {{ Form::text('amount_tendered', null, ['class' => 'form-control form-control-sm']) }} -->
                          <input type="number" id="amount_tendered" name="amount_tendered" class="form-control form-control-sm" oninput="calculateChange()" required>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-lg-6 mb-1">
                          <div class="d-flex justify-content-between text-capitalize">
                              <span class="">Change</span><span class="text-end">:</span>
                          </div>
                        </div>
                        <div class="col-lg-3 mb-1">
                          {{ Form::text('change', null, ['class' => 'form-control form-control-sm', 'readonly', 'id' => 'change']) }}
                        </div>
                      </div>
                      <hr>
                      <div class="row">
                        <div class="col-lg-12 mb-1 text-end">
                          <button type="submit" class="btn btn-warning btn-sm" id="submit_change_meter" disabled>Save</button>
                        </div>
                      </div>

                    </form>
                  @endif
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script>
  function calculateChange() {
    // Get the total_fees value, removing commas for calculation
    var total = parseFloat(document.getElementById('total_fees').value.replace(/,/g, '')) || 0;
    // Get the amount_tendered value, removing commas for calculation
    var amount = parseFloat(document.getElementById('amount_tendered').value.replace(/,/g, '')) || 0;

    var change = amount - total;
    
    // If change is negative, set it to 0
    if (change < 0) {
        change = 0;
    }

    // Format the change value with commas and two decimal places
    document.getElementById('change').value = change.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Enable the button if change is greater than or equal to total
    var button = document.getElementById('submit_change_meter');
    button.disabled = !(total <= amount);  
  }
</script>
@endsection