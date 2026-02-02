@extends('layouts.app')


@section('content')

<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
              <div class="col-lg-6">
                  <span class="mb-0 align-middle fs-3">Edit kWh Meter Request</span>
              </div>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('kwh-meter-request.update', $kwh_meter_request->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="user_id" class="form-label mb-1">Requested By:</label>
                    <select class="form-select" id="user_id" name="user_id" required>
                      <option value="">Select User</option>
                      @foreach($users as $id => $user)
                          <option value="{{ $id }}" @selected($kwh_meter_request->user_id == $id)>{{ $user }}</option>
                      @endforeach
                    </select>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="meter_code_id" class="form-label mb-1">Meter Type</label>
                    {{-- <select class="form-select" id="meter_code_id" name="meter_code_id" required>
                      <option value="">Select Meter Type</option>
                      @foreach($meters_types as $meter_type)
                          <option value="{{ $meter_type->id }}" @selected($kwh_meter_request->meter_code_id == $meter_type->id)>{{ $meter_type->meter_description }}</option>
                      @endforeach
                    </select> --}}
                    <select id="meter_code_id" class="form-control" name="meter_code_id" required>
                      <option value=""></option>
                      @foreach ($type_of_meters as $type_of_meter)          
                        <option value="{{ $type_of_meter->id }}" 
                                id="" 
                                {{ $kwh_meter_request->meter_code_id == $type_of_meter->id ? 'selected' : ''}}
                                {{ $type_of_meter->available_count <= 0 ? 'disabled' : '' }}
                                data-available-count="{{ $type_of_meter->available_count }}">
                          {{ $type_of_meter->meter_code }} - {{ $type_of_meter->meter_description }} 
                          (Available: {{ $type_of_meter->available_count }})
                        </option>
                      @endforeach 
                    </select>
                </div>
              </div>
              
              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="quantity" class="form-label mb-1">Quantity</label>
                    <input type="number" class="form-control" id="quantity" value="{{ $kwh_meter_request->quantity }}" max="10" name="quantity" old="quantity" required>
                </div>
              </div>

              <div class="col-lg-3">
                <div class="mb-2">
                  <label for="approved_by" class="form-label mb-1">Approved By:</label>
                    <select class="form-select" id="approved_by" name="approved_by" required>
                      <option value="">Select User</option>
                      @foreach($users as $id => $user)
                          <option value="{{ $id }}" @selected($kwh_meter_request->approved_by == $id)>{{ $user }}</option>
                      @endforeach
                    </select>
                </div>
              </div>

              <div class="col-lg-12">
                <div class="mb-2">
                  <label for="purpose" class="form-label mb-1">Purpose</label>
                    <textarea name="purpose" id="purpose" class="form-control" required>{{ $kwh_meter_request->purpose }}</textarea>
                </div>
              </div>
              
            </div>
            <div class="row">
              <div class="col-lg-12 text-end pt-2 gap-2">
                <a type="button" class="btn btn-sm btn-warning" href="{{ route('kwh-meter-request.index') }}"><i class="fa fa-times"></i> Cancel</a>
                <button type="submit" class="btn btn-sm btn-info"><i class="fa fa-floppy-disk"></i> Save</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('style')

<style>
  /* Style for disabled meter options */
  #meter_code_id option:disabled {
      color: #999;
      background-color: #f5f5f5;
      font-style: italic;
  }

  /* Style for available meter count display */
  .meter-availability-info {
      font-size: 12px;
      color: #666;
  }

  .meter-unavailable {
      color: #dc3545 !important;
  }

  .meter-available {
      color: #28a745 !important;
  }
</style>

@endsection
@section('script')
  <script>
    $(document).ready(function() {
        // add quantity validation on load
        var selectedOption = $('#meter_code_id').find('option:selected');
        var availableCount = selectedOption.data('available-count');
        $('#quantity').attr('max', availableCount > 10 ? 10 : availableCount);

        
        // Add event handler for meter type selection
        $('#meter_code_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            var availableCount = selectedOption.data('available-count');
            
            // Check if the selected meter type has available meters
            if (availableCount <= 0 && selectedOption.val() !== '') {
                alert('Warning: No meters available for the selected meter type. Please choose a different meter type.');
                $(this).val(''); // Clear the selection
                return false;
            }

            // Update max quantity based on availability
            $('#quantity').val(''); // Clear previous quantity
            $('#quantity').attr('max', availableCount > 10 ? 10 : availableCount);
            console.log('Max quantity set to: ' + $('#quantity').attr('max'));
        });
        
        // Style options based on availability when page loads
        $('#meter_code_id option').each(function() {
            var availableCount = $(this).data('available-count');
            if (availableCount <= 0 && $(this).val() !== '') {
                $(this).addClass('meter-unavailable');
                $(this).append(' - OUT OF STOCK');
            } else if ($(this).val() !== '') {
                $(this).addClass('meter-available');
            }
        });
    });
  </script>
@endsection