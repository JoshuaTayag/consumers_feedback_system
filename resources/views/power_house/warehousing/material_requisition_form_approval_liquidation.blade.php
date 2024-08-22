@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                    <span class="mb-0 align-middle fs-3">MER Liquidation</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-bordered">
                <tr>
                  {{-- <th class="text-center" ><input type="checkbox" class="form-check-input" id="checkAll"></th> --}}
                  <th>MER #</th>
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
                          <th style="white-space: nowrap;">{{  date('y', strtotime($data->created_at)). "-". str_pad($data->id,5,'0',STR_PAD_LEFT) }}</th>
                          <th>{{ $data->project_name }}</th>
                          <th>{{Config::get('constants.area_id.'.($data->area_id-1).'.name')}}, {{ $data->sitio }}, {{ $data->barangay ? $data->barangay->barangay_name : 'None' }}, {{ $data->municipality->municipality_name }}</th>
                          <th>{{ $data->requested_name }}</th>
                          <th>{{ date('F d, Y', strtotime($data->requested_by)) }}</th>
                          <th>
                            <a href="{{route('mrfLiquidationApprovalView', $data->id)}}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-eye"></i></a>
                              <!-- <div class="row">
                                  <div class="col-lg-6 p-1 text-center">
                                    {{-- <a href="{{route('LifelineUpdate', $data->id)}}" class="btn btn-success btn-sm"><i class="fa fa-check"></i></a> --}}
                                    <form method="POST" action="{{ route('mrfLiquidationApprovalUpdate', $data->id) }}">
                                      @method('PUT')
                                      @csrf
                                      <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i></button>
                                    </form>
                                  </div>
                                  <div class="col-lg-6 py-1 text-center">
                                    
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
                                    data-bs-items="{{json_encode($ii)}}" 
                                    data-bs-area="{{Config::get('constants.area_id.'.($data->area_id-1).'.name')}}" 
                                    data-bs-sitio="{{ $data->sitio }}" 
                                    data-bs-barangay="{{ $data->barangay ? $data->barangay->barangay_name : 'None' }}"
                                    data-bs-municipality="{{ $data->municipality->municipality_name }}"
                                    data-bs-requested="{{$data->requested_name}}" 
                                    data-bs-liquidation="{{$data->mrf_liquidations}}" 
                                    data-bs-requested-date="{{ date('F d, Y', strtotime($data->requested_by)) }}"
                                    data-bs-mcrt-details="{{ $data->MCRTNo.','.$data->MCRTDate.','.$data->ReturnedBy.','.$data->Note }}"
                                    data-bs-requested-date="{{ date('F d, Y', strtotime($data->requested_by)) }}">
                                    <i class="fa fa-eye"></i></button>

                                  </div>
                              </div> -->
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

    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="applianceCalculator" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="applianceCalculator">Material/Equipment Requisition Form</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="card">
              <div class="card-body"  >
                <div class="row">
                  <div class="col-lg-6">
                    <div class="card h-100">
                      <div class="card-header bg-info fs-5">
                        Project Details
                      </div>
                      <div class="card-body text-center">
                        <div class="row fs-5 text-start">
                          <div class="col">
                            Name: 
                          </div>
                          <div class="col text-start">
                            <span id="project_name"></span>
                          </div>
                        </div>

                        <div class="row fs-5 text-start">
                          <div class="col">
                            Area: 
                          </div>
                          <div class="col text-start">
                            <span id="area"></span>
                          </div>
                        </div>

                        <div class="row fs-5">
                          <div class="col text-start">
                            Sitio: 
                          </div>
                          <div class="col text-start">
                            <span id="sitio"></span>
                          </div>
                        </div>

                        <div class="row fs-5">
                          <div class="col text-start">
                            Barangay: 
                          </div>
                          <div class="col text-start">
                            <span id="barangay"></span>
                          </div>
                        </div>

                        <div class="row fs-5">
                          <div class="col text-start">
                            Municiaplity: 
                          </div>
                          <div class="col text-start">
                            <span id="municipality"></span>
                          </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="card h-100">
                      <div class="card-header bg-info fs-5">
                        Requested By
                      </div>
                      <div class="card-body d-flex align-items-center justify-content-center">
                        <div class="text-center">
                          <span id="requested_by" class="fs-4 fw-bold d-block"></span>
                          <span id="date_requested" class="fs-4 fw-bold d-block"></span>
                        </div>
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
                  <div class="col-lg-4">
                    <div class="card h-100">
                      <div class="card-header bg-info fs-5">
                        References
                      </div>
                      <div class="card-body">
                        <span id="liquidation_data">

                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="card h-100">
                      <div class="card-header bg-info fs-5">
                        Remarks
                      </div>
                      <div class="card-body">
                        <textarea class="form-control fs-5" name="remarks" id="remarks"></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row pt-4" id="mcrt_details">
                  <div class="col-lg-12">
                    <div class="card h-100">
                      <div class="card-header bg-info fs-5">
                        MCRT Details
                      </div>
                      <div class="card-body">
                        <div class="row pb-4">
                          <div class="col-lg-2">
                            <label for="mcrt_no" class="fw-bold" >MCRT #</label>
                            <input type="text" name="mcrt_no" class="form-control" id="mcrt_no" readonly>
                          </div>
                          <div class="col-lg-3">
                            <label for="mcrt_date" class="fw-bold">MCRT DATE</label>
                            <input type="text" name="mcrt_date" class="form-control" id="mcrt_date" readonly>
                          </div>
                          <div class="col-lg-3">
                            <label for="mcrt_returned" class="fw-bold" >RETURNED BY</label>
                            <input type="text" name="mcrt_returned" class="form-control" id="mcrt_returned" readonly>
                          </div>
                          <div class="col-lg-4">
                            <label for="mcrt_note" class="fw-bold" >NOTE</label>
                            <input type="text" name="mcrt_note" class="form-control" id="mcrt_note" readonly>
                          </div>
                        </div>
                        <span id="mcrt_items"></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row pt-4" id="mst_details">
                  <div class="col-lg-12">
                    <div class="card h-100">
                      <div class="card-header bg-info fs-5">
                        MST Details
                      </div>
                      <div class="card-body">
                        <div class="row pb-4">
                          <div class="col-lg-2">
                            <label for="mst_no" class="fw-bold" >MST #</label>
                            <input type="text" name="mst_no" class="form-control" id="mst_no" readonly>
                          </div>
                          <div class="col-lg-3">
                            <label for="mst_date" class="fw-bold">MST DATE</label>
                            <input type="text" name="mst_date" class="form-control" id="mst_date" readonly>
                          </div>
                          <div class="col-lg-3">
                            <label for="mst_returned" class="fw-bold" >RETURNED BY</label>
                            <input type="text" name="mst_returned" class="form-control" id="mst_returned" readonly>
                          </div>
                          <div class="col-lg-4">
                            <label for="mst_note" class="fw-bold" >NOTE</label>
                            <input type="text" name="mst_note" class="form-control" id="mst_note" readonly>
                          </div>
                        </div>
                        <span id="mst_items"></span>
                      </div>
                    </div>
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
    
    var data_area = button.getAttribute('data-bs-area')
    var data_requested_by = button.getAttribute('data-bs-requested')
    var data_requested_date = button.getAttribute('data-bs-requested-date')
    var new_items = button.getAttribute('data-bs-items')
      
    var sitio = button.getAttribute('data-bs-sitio')
    var barangay = button.getAttribute('data-bs-barangay')
    var municipality = button.getAttribute('data-bs-municipality')
    var liquidation_details = button.getAttribute('data-bs-liquidation')
    
    // var wattage = button.getAttribute('data-bs-wattage')
    // var rate = button.getAttribute('data-bs-rate')
      var encoded_data = JSON.parse(data);
      var encoded_items = JSON.parse(new_items);
      var encoded_liquidation_details = JSON.parse(liquidation_details);
      // populate new value
      document.getElementById("project_name").innerHTML = encoded_data.project_name;
      document.getElementById("requested_by").innerHTML = data_requested_by;
      document.getElementById("date_requested").innerHTML = data_requested_date;
      document.getElementById("sitio").innerHTML = sitio;
      document.getElementById("barangay").innerHTML = barangay;
      document.getElementById("municipality").innerHTML = municipality;
      document.getElementById("area").innerHTML = data_area;
      document.getElementById("remarks").value = encoded_data.remarks;
      // document.getElementById("liquidation_data").innerHTML = encoded_liquidation_details;
      
      // var liquidation_data = encoded_liquidation_details.map(function(value) {
      //   if(value.type != "MCRT" && value.type != "MST"){
      //     return `<strong>${value.type}# ${value.type_number}</strong>`;
      //   }
      // }).join('<br>');

      var liquidation_data = encoded_liquidation_details
        .filter(function(value) {
          return value.type != "MCRT" && value.type != "MST";
        })
        .map(function(value) {
          return `<strong>${value.type}# ${value.type_number}</strong>`;
        })
        .join('<br>');

      // hide the container if the mcrt is null
      if(encoded_data.MCRTNo == null){
        document.getElementById("mcrt_details").classList.add('d-none');
      } else {
        document.getElementById("mcrt_details").classList.remove('d-none');
      }

      // hide the container if the mst is null
      if(encoded_data.MSTNo == null){
        document.getElementById("mst_details").classList.add('d-none');
      } else {
        document.getElementById("mst_details").classList.remove('d-none');
      }

      var dateStr = encoded_data.MCRTDate; // "2024-07-05 00:00:00"
      var dateObj = new Date(dateStr);

      var dateStr1 = encoded_data.MSTDate; // "2024-07-05 00:00:00"
      var dateObj1 = new Date(dateStr1);

      // Extract the month, day, and year
      var mcrt_month = (dateObj.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based, so add 1
      var mcrt_day = dateObj.getDate().toString().padStart(2, '0');
      var mcrt_year = dateObj.getFullYear();

      // Extract the month, day, and year
      var mst_month = (dateObj1.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based, so add 1
      var mst_day = dateObj1.getDate().toString().padStart(2, '0');
      var mst_year = dateObj1.getFullYear();

      var mcrtFormattedDate = mcrt_month + '/' + mcrt_day + '/' + mcrt_year; // "07/05/2024"
      var mstFormattedDate = mst_month + '/' + mst_day + '/' + mst_year; // "07/05/2024"

      document.getElementById("liquidation_data").innerHTML = liquidation_data;

      // append mcrt details
      document.getElementById("mcrt_no").value = encoded_data.MCRTNo;
      document.getElementById("mcrt_date").value = mcrtFormattedDate;
      document.getElementById("mcrt_returned").value = encoded_data.ReturnedBy;
      document.getElementById("mcrt_note").value = encoded_data.Note;

      // append mst details
      document.getElementById("mst_no").value = encoded_data.MSTNo;
      document.getElementById("mst_date").value = mstFormattedDate;
      document.getElementById("mst_returned").value = encoded_data.mst_returned;
      document.getElementById("mst_note").value = encoded_data.mst_note;

      var items = encoded_data.items;
      var mcrt_items = encoded_data.mcrt_items;
      var mst_items = encoded_data.mst_items;

      var all_item = [];
      var all_mcrt_items = [];
      var all_mst_items = [];

      items.forEach(function (value, i) {
        all_item.push(
          ` <tr>
              <th>${value.nea_code}</th>
              <th>${encoded_items[i]}</th>
              <th>${value.quantity}</th>
              <th class="text-${value.quantity == value.liquidation_quantity ? 'success' : 'danger'}">${value.liquidation_quantity}</th>
              <th>${Number(value.unit_cost).toFixed(2)}</th>
            </tr>
            `)
      });
      console.log(encoded_data)
      mcrt_items.forEach(function (value, i) {
        all_mcrt_items.push(
          ` <tr>
              <th>${value.CodeNo}</th>
              <th>${Math.floor(value.MCRTQty)}</th>
            </tr>
            `)
      });
      
      mst_items.forEach(function (value, i) {
        all_mst_items.push(
          ` <tr>
              <th>${value.CodeNo}</th>
              <th>${Math.floor(value.MSTQty)} </th>
            </tr>
            `)
      });

        // console.log(encoded_items)
        document.getElementById("items").innerHTML = 
        `<table class="table table-bordered">
          <tr>
            <th>NEA Code</th>
            <th>Description</th>
            <th>Qty Req</th>
            <th>Qty Used</th>
            <th>Cost</th>
          </tr>
          <tbody>
            `+all_item.join("")+`
          </tbody>
        </table>`;

        document.getElementById("mcrt_items").innerHTML = 
        `<table class="table table-bordered">
          <tr>
            <th>ITEM Code</th>
            <th>Qty Returned</th>
          </tr>
          <tbody>
            `+all_mcrt_items.join("")+`
          </tbody>
        </table>`;

        document.getElementById("mst_items").innerHTML = 
        `<table class="table table-bordered">
          <tr>
            <th>ITEM Code</th>
            <th>Qty Returned</th>
          </tr>
          <tbody>
            `+all_mst_items.join("")+`
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