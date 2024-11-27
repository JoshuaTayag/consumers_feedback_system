<div class="modal fade" id="dispatchingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Dispatching</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        {!! Form::open(array('route' => 'cmDispatching','method'=>'POST', 'id' => 'myForm')) !!}
          <div class="row">
            <input type="hidden" value="" id="cm_id" name="cm_id" class="form-control border border-warning" readonly>
            <div class="col">
              <label for="sco_dispatched">SCO No:</label>
              <input type="text" value="" id="sco_dispatched" name="sco_dispatched" class="form-control border border-warning" readonly>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-lg-12">
              <label for="crew_dispatched">Crew *:</label>
              <select id="crew_dispatched" class="form-control" name="crew_dispatched" required>
                <option value=""></option>
                @foreach ($ref_employees as $employee)          
                  <option value="{{ $employee['full_name'] }}" id="">{{ $employee['full_name'] }}</option>
                @endforeach 
                <option value="OTHERS">OTHERS</option>
              </select>
            </div>
            <div class="col-lg-12">
              <label for="date_dispatched">Dispatch Date:</label>
              <input type="date" value="" id="date_dispatched" name="date_dispatched" class="form-control" readonly>
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