@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                    <span class="mb-0 align-middle fs-3">Pending Lifeline Applications</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  <th class="text-center" ><input type="checkbox" class="form-check-input" id="checkAll"></th>
                  <th>Control No</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>Last Name</th>
                  <th>Address</th>
                  <th>Date Of Birth</th>
                  <th>Account No</th>
                  <th width="280px">Action</th>
                </tr>
                <tbody id="show_data">
                  @foreach ($lifeline_data as $index => $data)                        
                      <tr>
                          <th class="text-center"><input type="checkbox" id="{{$data->account_no}}" class="form-check-input" name="checked" value="{{$data->id}}">  </th>
                          <th>{{ $data->control_no }}</th>
                          <th>{{ $data->first_name }}</th>
                          <th>{{ $data->middle_name }}</th>
                          <th>{{ $data->last_name }}</th>
                          <th>address</th>
                          <th>{{ $data->date_of_birth }}</th>
                          <th>{{ $data->account_no }}</th>
                          <th>
                              <div class="row">
                                  <div class="col-lg-6 p-1 text-center">
                                    {{-- <a href="{{route('LifelineUpdate', $data->id)}}" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a> --}}
                                    <form method="POST" action="{{ route('LifelineUpdate', $data->id) }}">
                                      @method('PUT')
                                      @csrf
                                      {!! Form::hidden('account_no', $data->account_no )!!}
                                      <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i></button>
                                    </form>
                                  </div>
                                  <div class="col-lg-6 p-1 text-center">
                                    <form method="POST" action="{{ route('LifelineUpdate', $data->id) }}">
                                      @method('PUT')
                                      @csrf
                                      {!! Form::hidden('disapproved', true )!!}
                                      <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-times"></i></button>
                                    </form>
                                  </div>
                              </div>
                          </th>
                      </tr>
                  @endforeach
                </tbody>
               </table>
               <div class="row">
                <div class="col-lg-12">
                  <a href="#" class="btn btn-success btn-sm" id="approvedAll"><i class="fa fa-check"></i>Approved</a>
                  <a href="#" class="btn btn-danger btn-sm" id="disApprovedAll"><i class="fa fa-multiply"></i>Disapproved</a>
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
$('#checkAll').click(function () {    
  $('input:checkbox').prop('checked', this.checked);    
});

// Pass the checkbox name to the function
function getCheckedBoxesValue(chkboxName) {
  var checkboxes = document.getElementsByName(chkboxName);
  var checkboxesChecked = [];
  // loop over them all
  for (var i=0; i<checkboxes.length; i++) {
     // And stick the checked ones onto an array...
     if (checkboxes[i].checked) {
        checkboxesChecked.push(checkboxes[i].value);
     }
  }
  // Return the array if it is non-empty, or null
  return checkboxesChecked.length > 0 ? checkboxesChecked : null;
}


// Pass the checkbox name to the function
function getCheckedBoxesId(chkboxName) {
  var checkboxes = document.getElementsByName(chkboxName);
  var checkboxesChecked = [];
  // loop over them all
  for (var i=0; i<checkboxes.length; i++) {
     // And stick the checked ones onto an array...
     if (checkboxes[i].checked) {
        checkboxesChecked.push(checkboxes[i].id);
     }
  }
  // Return the array if it is non-empty, or null
  return checkboxesChecked.length > 0 ? checkboxesChecked : null;
}

$('#approvedAll').click(function () {    
  // Call as
  var checkedBoxesId = getCheckedBoxesValue("checked");
  var checkedBoxesAccount = getCheckedBoxesId("checked");

  $.ajax({
      url: "{{ route('LifelineMassUpdate') }}",
      type: "POST",
      data: {
          _method:'PUT',
          ids: checkedBoxesId,
          accounts: checkedBoxesAccount,
          _token: '{{csrf_token()}}'
      },
      dataType: 'json',
      success: function (result) {
        if(result.success){
          swal.fire("Done!", result.message, "success");
          // refresh page after 2 seconds
          setTimeout(function(){
              location.reload();
          },2000);
        }
        else{
          swal.fire("Error!", result.message, "error");
          // refresh page after 2 seconds
          // setTimeout(function(){
          //     location.reload();
          // },2000);
        }
        
      }
  });
});

$('#disApprovedAll').click(function () {    
  // Call as
  var checkedBoxesId = getCheckedBoxesValue("checked");
  var checkedBoxesAccount = getCheckedBoxesId("checked");

  $.ajax({
      url: "{{ route('LifelineMassUpdate') }}",
      type: "POST",
      data: {
          _method:'PUT',
          ids: checkedBoxesId,
          accounts: checkedBoxesAccount,
          disapproved: true,
          _token: '{{csrf_token()}}'
      },
      dataType: 'json',
      success: function (result) {
        if(result.success){
          swal.fire("Done!", result.message, "success");
          // refresh page after 2 seconds
          setTimeout(function(){
              location.reload();
          },2000);
        }
        else{
          swal.fire("Error!", result.message, "error");
          // refresh page after 2 seconds
          // setTimeout(function(){
          //     location.reload();
          // },2000);
        }
        
      }
  });
});
</script>
@endsection