<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Meter Posting</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! Form::open(array('route' => 'meterPostingCM','method'=>'POST', 'id' => 'myForm')) !!}
          <div class="row">
            <div class="col">
              <label for="sco">SCO No:</label>
              <input type="text" value="" id="sco" name="sco" class="form-control border border-warning" readonly>
            </div>
            <div class="col">
              <label for="full_name">Fullname:</label>
              <input type="text" value="" id="full_name" name="full_name" class="form-control border border-warning" readonly>
            </div>
            <div class="col">
              <label for="process_date">Process Date:</label>
              <input type="text" value="" id="process_date" name="process_date" class="form-control border border-warning" readonly>
            </div>
          </div>
          <hr>
          <div class="row" id="meter_details">
            <div class="col-lg-4">
              <label for="meter_no">Meter No:</label>
              <input type="text" value="" id="meter_no" name="meter_no" class="form-control">
              <span id="error_meter"></span>
            </div>
            <div class="col">
              <label for="date_installed">Date Installed:</label>
              <input type="date" value="" id="date_installed" name="date_installed" class="form-control">
            </div>
            <div class="col">
              <label for="seal_no">L5 Seal No. :</label>
              <input type="text" value="" id="seal_no" name="seal_no" class="form-control">
              <span id="error_seal"></span>
            </div>
            <!-- <div class="col">
              <label for="serial_no">Serial No. :</label>
              <input type="text" value="" id="serial_no" name="serial_no" class="form-control">
            </div> -->
            <div class="col">
              <label for="erc_seal">ERC Seal No. :</label>
              <input type="text" value="" id="erc_seal" name="erc_seal" class="form-control">
              <span id="error_erc_seal"></span>
            </div>
          </div>
          <div class="row" id="address_details">
            <div class="col-lg-4">
              <label for="care_of">C/O:</label>
              <input type="text" value="" id="care_of" name="care_of" class="form-control">
            </div>
            <div class="col-lg-1">
              <div class="mb-2">
                  {{ Form::label('area', 'Area *') }}
                  <select id="area" class="form-control" name="area" value="{{ old('area')}}" required>
                    <option value=""></option>
                    <option value="A1" {{ old('area') == "A1" ? 'selected' : ''}} >A1</option>
                    <option value="A2" {{ old('area') == "A2" ? 'selected' : ''}} >A2</option>
                    <option value="A3" {{ old('area') == "A3" ? 'selected' : ''}} >A3</option>
                    <option value="A4" {{ old('area') == "A4" ? 'selected' : ''}} >A4</option>
                    <option value="A5" {{ old('area') == "A5" ? 'selected' : ''}} >A5</option>
                  </select>
              </div>
            </div>
            <div class="col-lg-3">
              <label for="feeder">Feeder:</label>
              <!-- <input type="text" value="" id="care_of" name="care_of" class="form-control" readonly> -->
              <select id="feeder" class="form-control" name="feeder">
                <option value=""></option>
                @foreach (Config::get('constants.feeders') as $feeder)          
                  <option value="{{ $feeder['name'] }}" id="">{{ $feeder['name'] }}</option>
                @endforeach 
              </select>
            </div>
            <div class="col-lg-2">
              <label for="last_reading">Last Reading:</label>
              <input type="text" value="" id="last_reading" name="last_reading" class="form-control" >
            </div>
            <div class="col-lg-2">
              <label for="reading_initial">Reading Initial:</label>
              <input type="text" value="" id="reading_initial" name="reading_initial" class="form-control" >
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-lg-3">
              <label for="crew">Crew:</label>
              <select id="crew" class="form-control" name="crew" >
                <option value=""></option>
                @foreach ($ref_employees as $employee)          
                  <option value="{{ $employee->full_name }}" id="">{{ $employee->full_name }}</option>
                @endforeach 
                <option value="OTHERS">OTHERS</option>
              </select>
            </div>
            <div class="col-lg-3">
              <label for="status">Action Status:</label>
              <select id="status" class="form-control" name="status" required>
                <option value=""></option>
                @foreach (Config::get('constants.action_status_change_meter') as $status)          
                  <option value="{{ $status['name'] }}" id="">{{ $status['name'] }}</option>
                @endforeach 
              </select>
            </div>
            <div class="col-lg-3">
              <label for="time">Time:</label>
              <input type="time" value="" id="time" name="time" class="form-control" >
            </div>
            <div class="col-lg-6">
              <label for="damage_cause">Damage Cause:</label>
              <textarea name="damage_cause" id="damage_cause" class="form-control" ></textarea>
            </div>
            <div class="col-lg-6">
              <label for="crew_remarks">Crew Remarks:</label>
              <textarea name="crew_remarks" id="crew_remarks" class="form-control" ></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="submit_meter_posting" >Save changes</button>
        </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>