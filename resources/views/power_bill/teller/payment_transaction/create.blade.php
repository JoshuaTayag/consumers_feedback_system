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
                  <span class="mb-0 align-middle fs-3">Payment Transaction</span>
              </div>
              <div class="col-lg-6 text-end">
                <a class="btn btn-sm btn-primary" href="{{ route('indexCM') }}">MANUAL</a>
              </div>
          </div>
        </div>
        <div class="card-body" style="background-color: #fafafa">
          <form action="{{ route('payment-transact.store') }}" method="POST">
          @csrf
            <div class="row">
              <div class="col-lg-7">
                <div class="card mt-1">
                  <div class="card-header">
                    <h4>Transaction Details</h4>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="mb-2">
                          {{ Form::label('or_no', 'OR No.') }}
                          {{ Form::text('or_no', null, ['class' => 'form-control', 'required']) }}
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="mb-2">
                          {{ Form::label('first_name', 'First Name') }}
                          {{ Form::text('first_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="mb-2">
                          {{ Form::label('last_name', 'Last Name') }}
                          {{ Form::text('last_name', null, ['class' => 'form-control', 'required']) }}
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="mb-2">
                          <label for="municipality" class="form-label mb-1">Municipality *</label>
                          <select id="municipality" class="form-control" name="municipality" required>
                            <option value="" id=""></option>
                            @foreach ($municipalities as $municipality)                        
                                <option value="{{ $municipality->id }}" id="{{ $municipality->id }}">{{$municipality->municipality_name}}</option>
                            @endforeach 
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="mb-2">
                          <label for="barangay" class="form-label mb-1">Barangay *</label>
                          <select id="barangay" class="form-control" name="barangay" required></select>
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="mb-2">
                          {{ Form::label('sitio', 'Sitio') }}
                          {{ Form::text('sitio', null, array('class' => 'form-control', 'required')) }}
                        </div>
                      </div>
                      <div class="col-lg-4">
                        <div class="mb-2">
                          {{ Form::label('process_date', 'Process Date') }}
                          {{ Form::date('process_date', \Carbon\Carbon::now()->format('Y-m-d'), ['class' => 'form-control', 'readonly' => true, 'required']) }}
                        </div>
                      </div>
                      <div class="col-lg-5">
                        <div class="mb-2">
                            {{ Form::label('consumer_type', 'Consumer Type *') }}
                            <select id="consumer_type" class="form-control" name="consumer_type" value="{{ old('consumer_type')}}" required>
                              <option value=""></option>
                              @foreach (Config::get('constants.consumer_types') as $consumer_type)          
                                <option value="{{ $consumer_type['id'] }}" id="">{{ $consumer_type['name'] }}</option>
                              @endforeach 
                            </select>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="mb-2">
                          {{ Form::label('cheque_no', 'Cheque No.') }}
                          {{ Form::text('cheque_no', null, ['class' => 'form-control', 'readonly' => false]) }}
                        </div>
                      </div>
                      <div class="col-lg-5">
                        <div class="mb-2">
                          {{ Form::label('cheque_date', 'Cheque Date') }}
                          {{ Form::date('cheque_date', null, ['class' => 'form-control', 'readonly' => false]) }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-5">
                <div class="card mt-1">
                  <div class="card-header">
                    <h4>Fees</h4>
                  </div>
                  <div class="card-body" id="schedule_of_fees">
                    @include('service_connect_order.schedule_of_fees')
                  </div>
                </div>
              </div>
              <div class="col-lg-12 mt-3 text-end">
                <button type="submit" class="btn btn-sm btn-primary">Submit</button>
              </div>
            </div>
          </form>
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

  $('#municipality').on('change', function () {
    var id = $(this).children(":selected").attr("id");
    $("#barangay").html('');
    $.ajax({
        url: "{{url('api/fetch-barangays')}}",
        type: "POST",
        data: {
            municipality_id: id,
            _token: '{{csrf_token()}}'
        },
        dataType: 'json',
        success: function (res) {
            $('#barangay').html('<option value="">-- Select Barangay --</option>');
            $.each(res.barangays, function (key, value) {
                  $("#barangay").append('<option value="' + value
                      .id + '" id="'+ value.id +'">' + value.barangay_name + '</option>');
            });
        }
    });
  });
</script>
@endsection
@section('style')
<style>
  #schedule_of_fees{
    border-radius: 10px;
    border: 1px gray;
    background: transparent;
    backdrop-filter: blur(8px);
  }

  .container {
      display: flex;
     
  }
  
  .scrollbar {
    max-height: 450px; overflow-y: auto;
  }
  /*       ScrollBar 1        */
  
  #scrollbar1::-webkit-scrollbar {
      width: 10px;
  }
  
  #scrollbar1::-webkit-scrollbar-track {
      border-radius: 8px;
      background-color: #e7e7e7;
      border: 1px solid #cacaca;
  }
  
  #scrollbar1::-webkit-scrollbar-thumb {
      border-radius: 8px;
      background-color: #e19a00;
  }
</style>
@endsection