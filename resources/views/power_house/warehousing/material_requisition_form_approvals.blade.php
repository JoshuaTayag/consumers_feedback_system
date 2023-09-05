@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                    <span class="mb-0 align-middle fs-3">Pending Material/Equipment Requisition Form</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  {{-- <th class="text-center" ><input type="checkbox" class="form-check-input" id="checkAll"></th> --}}
                  <th>Project Name</th>
                  <th>Address</th>
                  <th>Requested By</th>
                  <th>Date Requested</th>
                  <th>Action</th>
                </tr>
                <tbody id="show_data">
                  @foreach ($mrfs as $index => $data)                        
                      <tr>
                          {{-- <th class="text-center"><input type="checkbox" id="{{$data->account_no}}" class="form-check-input" name="checked" value="{{$data->id}}">  </th> --}}
                          <th>{{ $data->project_name }}</th>
                          <th>{{ $data->district->district_name }}, {{ $data->barangay->barangay_name }}, {{ $data->municipality->municipality_name }}</th>
                          <th>{{ $data->requested_name }}</th>
                          <th>{{ date('F d, Y', strtotime($data->requested_by)) }}</th>
                          <th>
                              <div class="row">
                                  <div class="col-lg-4 p-1 text-center">
                                    {{-- <a href="{{route('LifelineUpdate', $data->id)}}" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a> --}}
                                    <form method="POST" action="{{ route('mrfApprovalUpdate', $data->id) }}">
                                      @method('PUT')
                                      @csrf
                                      <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i></button>
                                    </form>
                                  </div>
                                  <div class="col-lg-4 py-1 text-center">
                                    
                                    {{-- <button type="button" class="btn btn-sm btn-primary" onclick="showModal('test','test')">
                                      <i class="fa fa-eye"></i>
                                    </button> --}}
                                    @php
                                      $ii = [];
                                      foreach ($data->items as $index => $mrf_item) {
                                        array_push($ii, $mrf_item->item->Description);
                                      }
                                    @endphp
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#viewModal" data-bs-name="{{$data}}"
                                    data-bs-items="{{json_encode($ii)}}" data-bs-address="{{ $data->district->district_name }}, {{ $data->barangay->barangay_name }}, {{ $data->municipality->municipality_name }}, {{ $data->municipality->sitio }}" data-bs-requested="{{$data->requested_name}}" data-bs-requested-date="{{ date('F d, Y', strtotime($data->requested_by)) }}"><i class="fa fa-eye"></i></button>
                                  </div>
                                  <div class="col-lg-4 p-1 text-center">
                                    <form method="POST" action="{{ route('mrfApprovalUpdate', $data->id) }}">
                                      @method('PUT')
                                      @csrf
                                      {!! Form::hidden('disapproved', true )!!}
                                      <button class="btn btn-danger btn-sm confirm-button" type="submit"><i class="fa fa-times"></i></button>
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
                  {{-- <a href="#" class="btn btn-success btn-sm" id="approvedAll"><i class="fa fa-check"></i>Approved</a> --}}
                  {{-- <a href="#" class="btn btn-danger btn-sm" id="disApprovedAll"><i class="fa fa-multiply"></i>Disapproved</a> --}}
                </div>
               </div>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  ...
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary">Understood</button>
                </div>
              </div>
            </div>
          </div>


          {{-- Appliance Calculator modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="applianceCalculator" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="applianceCalculator">Material/Equipment Requisition Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Project Name
                      </div>
                      <div class="card-body text-center">
                        <span id="project_name" class="fs-5"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Requested By
                      </div>
                      <div class="card-body text-center">
                        <span id="requested_by" class="fs-5"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row pt-4">
                  <div class="col-lg-6">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Address
                      </div>
                      <div class="card-body text-center">
                        <span id="address" class="fs-5"></span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Requested Date
                      </div>
                      <div class="card-body text-center">
                        <span id="date_requested" class="fs-5"></span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row pt-4">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Items
                      </div>
                      <div class="card-body">
                        <span id="items">

                        </span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row pt-4">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-header bg-info fs-5">
                        Remarks
                      </div>
                      <div class="card-body">
                        <textarea class="form-control fs-5" name="remarks" id="remarks"></textarea>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-lg-5">
                    <span id="data">

                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
  var viewModal = document.getElementById('viewModal')
    viewModal.addEventListener('show.bs.modal', function (event) {
    // Button that triggered the modal
    var button = event.relatedTarget
    // Extract info from data-bs-* attributes
    var data = button.getAttribute('data-bs-name')
    var data_address = button.getAttribute('data-bs-address')
    var data_requested_by = button.getAttribute('data-bs-requested')
    var data_requested_date = button.getAttribute('data-bs-requested-date')
    var new_items = button.getAttribute('data-bs-items')
    // var wattage = button.getAttribute('data-bs-wattage')
    // var rate = button.getAttribute('data-bs-rate')
      var encoded_data = JSON.parse(data);
      var encoded_items = JSON.parse(new_items);
      // console.log(encoded_data.items)
      document.getElementById("project_name").innerHTML = encoded_data.project_name;
      document.getElementById("requested_by").innerHTML = data_requested_by;
      document.getElementById("date_requested").innerHTML = data_requested_date;
      document.getElementById("address").innerHTML = data_address;
      document.getElementById("remarks").value += encoded_data.remarks;

      var items = encoded_data.items;
      var all_item = [];
      items.forEach(function (value, i) {
        all_item.push(
          ` <tr>
              <th>${value.nea_code}</th>
              <th>${encoded_items[i]}</th>
              <th>${value.quantity}</th>
              <th>${value.unit_cost}</th>
            </tr>
            `)
      });
        // console.log(encoded_items)
        document.getElementById("items").innerHTML = 
        `<table class="table table-bordered">
          <tr>
            <th>NEA Code</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Cost</th>
          </tr>
          <tbody>
            `+all_item.join("")+`
          </tbody>
        </table>`;

      
      // document.getElementById("appliance_wattage").innerHTML = wattage + "&nbsp;Watts";
      // document.getElementById("wattage").value = wattage;
      // document.getElementById("rate").value = rate;
      // document.getElementById("current_rate").innerHTML = rate;
  })

  $(document).ready(function () {
    $('.confirm-button').click(function(event) {
      var form =  $(this).closest("form");
      event.preventDefault();
      swal.fire({
          title: `Are you sure you want to disapprove this request?`,
          icon: "warning",
          showCancelButton: true,
      }).then((result) => {
          if (result.isConfirmed){
              form.submit();
          }
          else{

          }
              
      });
    });
  });
// $('#checkAll').click(function () {    
//   $('input:checkbox').prop('checked', this.checked);    
// });

// Pass the checkbox name to the function
// function getCheckedBoxesValue(chkboxName) {
//   var checkboxes = document.getElementsByName(chkboxName);
//   var checkboxesChecked = [];
//   // loop over them all
//   for (var i=0; i<checkboxes.length; i++) {
//      // And stick the checked ones onto an array...
//      if (checkboxes[i].checked) {
//         checkboxesChecked.push(checkboxes[i].value);
//      }
//   }
//   // Return the array if it is non-empty, or null
//   return checkboxesChecked.length > 0 ? checkboxesChecked : null;
// }


// Pass the checkbox name to the function
// function getCheckedBoxesId(chkboxName) {
//   var checkboxes = document.getElementsByName(chkboxName);
//   var checkboxesChecked = [];
//   // loop over them all
//   for (var i=0; i<checkboxes.length; i++) {
//      // And stick the checked ones onto an array...
//      if (checkboxes[i].checked) {
//         checkboxesChecked.push(checkboxes[i].id);
//      }
//   }
//   // Return the array if it is non-empty, or null
//   return checkboxesChecked.length > 0 ? checkboxesChecked : null;
// }

// $('#approvedAll').click(function () {    
//   // Call as
//   var checkedBoxesId = getCheckedBoxesValue("checked");
//   var checkedBoxesAccount = getCheckedBoxesId("checked");

//   $.ajax({
//       url: "{{ route('LifelineMassUpdate') }}",
//       type: "POST",
//       data: {
//           _method:'PUT',
//           ids: checkedBoxesId,
//           accounts: checkedBoxesAccount,
//           _token: '{{csrf_token()}}'
//       },
//       dataType: 'json',
//       success: function (result) {
//         if(result.success){
//           swal.fire("Done!", result.message, "success");
//           // refresh page after 2 seconds
//           setTimeout(function(){
//               location.reload();
//           },2000);
//         }
//         else{
//           swal.fire("Error!", result.message, "error");
//           // refresh page after 2 seconds
//           // setTimeout(function(){
//           //     location.reload();
//           // },2000);
//         }
        
//       }
//   });
// });

// $('#disApprovedAll').click(function () {    
//   // Call as
//   var checkedBoxesId = getCheckedBoxesValue("checked");
//   var checkedBoxesAccount = getCheckedBoxesId("checked");

//   $.ajax({
//       url: "{{ route('LifelineMassUpdate') }}",
//       type: "POST",
//       data: {
//           _method:'PUT',
//           ids: checkedBoxesId,
//           accounts: checkedBoxesAccount,
//           disapproved: true,
//           _token: '{{csrf_token()}}'
//       },
//       dataType: 'json',
//       success: function (result) {
//         if(result.success){
//           swal.fire("Done!", result.message, "success");
//           // refresh page after 2 seconds
//           setTimeout(function(){
//               location.reload();
//           },2000);
//         }
//         else{
//           swal.fire("Error!", result.message, "error");
//           // refresh page after 2 seconds
//           // setTimeout(function(){
//           //     location.reload();
//           // },2000);
//         }
        
//       }
//   });
// });
</script>
@endsection