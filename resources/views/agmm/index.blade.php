@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-12">
                      <span class="mb-0 align-middle fs-3">VERIFY ACCOUNTS</span>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <form action="{{ route('agmmAccounts') }}" method="GET">
                <div class="row pb-2">
                  <div class="col-sm-12 pb-2">
                      <input type="text" placeholder="Search by Name" id="account_name" name="account_name" value="{{ request('account_name') }}" class="form-control">
                  </div>
                  <div class="col-sm-12 pb-2">
                      <input type="text" placeholder="Search by Account" id="account_no" name="account_no" maxlength="10" value="{{ request('account_no') }}" class="form-control">
                  </div>
                  <div class="col text-center">
                    <!-- <a type="button" class="btn btn-sm btn-secondary btn-md text-end" data-bs-toggle="modal" data-bs-target="#rePrint">
                      <i class="fa fa-refresh"></i> RE-PRINT
                    </a> -->
                    <a type="button" class="btn btn-sm btn-secondary btn-md text-end" data-bs-toggle="modal" data-bs-target="#scanner">
                      <i class="fa fa-qrcode"></i> PRE-REGISTER
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> SEARCH</button>
                  </div>
                </div>
              </form>
              <div class="table-responsive">
                <table class="table table-striped table-bordered ">
                  <thead>
                    <tr class="text-center">
                      <th>Account #</th>
                      <th>Account Name</th>
                      <th>Membership</th>
                      <th>Joint</th>
                      <th>Address</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="show_data">
                  @foreach ($accounts as $index => $account)               
                    <tr class="text-center">
                        <th>{{ substr($account->id, 0, 2) }}-{{ substr($account->id, 2, 4) }}-{{ substr($account->id, 6, 4) }}</th>
                        <th>{{ $account->Name }}</th>
                        <th>{{ $account->last_name . ', ' . $account->first_name . ' ' . $account->middle_name }}</th>
                        <th>{{ $account->joint_name  }}</th>
                        <th>{{ $account->Address }}</th>
                        @if($account->account_exists == 0)
                          <th>
                            <form id="confirmationForm_{{$account->id}}" method="POST" action="{{ route('agmmVerifyAccount', $account->id) }}">
                                <input type="hidden" id="swalValue_{{$account->id}}" name="swalValue">
                                <input type="hidden" id="hidden_id_{{$account->id}}" name="id" value="{{$account->id}}">
                                <input type="hidden" id="hidden_amount_{{$account->id}}" name="hidden_amount">
                                <input type="hidden" id="hidden_remarks_{{$account->id}}" name="hidden_remarks">
                                @csrf
                                <button class="btn btn-success btn-sm" type="button" onclick="showConfirmationDialog('{{$account->id}}')"><i class="fa fa-check"></i></button>
                            </form>
                          </th>
                        @else
                          <th>
                              <a href="{{route('printRegistrationQR', $account->id)}}" class="btn btn-sm btn-warning"><i class="fa fa-print"></i></a>
                          </th>
                        @endif
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <div class="container">
                <div class="row text-end bg-dark text-white mb-1">
                    <div class="col">
                        <p class="mb-1 fw-bold">Verified Guest: {{ $total_verified_guest_per_user }}</p>
                    </div>
                    <div class="col">
                        <p class="mb-1 fw-bold">Verified MCO: {{ $total_verified_mco_per_user }}</p>
                    </div>
                    <div class="col">
                        <p class="mb-1 fw-bold">Total Verified Consumer: {{ $total_verified_per_user }}</p>
                    </div>
                </div>
                <div class="row align-items-center">
                  <div class="col-6 ps-0 pe-1">
                      <form id="logout-form" action="{{ route('logout') }}" method="POST">
                          @csrf
                          <button type="submit" class="btn btn-sm btn-danger full-width-button">Logout</button>
                      </form>
                  </div>
                  <div class="col-6 pe-0 ps-1">
                      <a href="{{ route('home') }}" class="btn btn-sm btn-warning full-width-button"> <i class="fa fa-home me-2"></i>Home</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="scanner" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="qr-reader" class="mx-auto"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="rePrint" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Reprint QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" class="form-control" placeholder="Account No" id="account_no_reprint" name="account_no_reprint" maxlength="10">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="rePrint()">RE-PRINT</button>
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
  <script>
    // document.addEventListener('DOMContentLoaded', function () {
    //   document.body.addEventListener('submit', function (event) {
    //       if (event.target.matches('#confirmationForm')) {
    //           event.preventDefault(); // Prevent the form from submitting immediately

    //           Swal.fire({
    //               title: 'Do you really want to verify this consumer?',
    //               // text: "Do you really want to verify this consumer?",
    //               html:
    //                           '' + 
    //                           `<div>
    //                             <div class="form-check">
    //                               <input class="form-check-input" type="radio" name="flexRadioDefault" value="MCO" id="mco">
    //                               <label class="form-check-label" for="mco">
    //                                 MCO
    //                               </label>
    //                             </div>
    //                             <div class="form-check">
    //                               <input class="form-check-input" type="radio" name="flexRadioDefault" value="Guest" id="guest">
    //                               <label class="form-check-label" for="guest">
    //                                 Guest
    //                               </label>
    //                             </div>
    //                             <div>
    //                               <div class="m-2" id="remarks-container">
    //                                 <label for="remarks" class="form-label">Remarks: </label>
    //                                 <input type="text" class="form-control" id="remarks" name="remarks">
    //                               </div>
    //                             </div>
    //                           </div>`,
    //               icon: 'warning',
    //               showCancelButton: true,
    //               confirmButtonColor: '#3085d6',
    //               cancelButtonColor: '#d33',
    //               confirmButtonText: 'Confirm',
    //               didOpen: () => {
    //                   const mcoRadio = document.getElementById('mco');
    //                   const guestRadio = document.getElementById('guest');
    //                   const remarksContainer = document.getElementById('remarks-container');
    //                   const remarksInput = document.getElementById('remarks');
                      
    //                   const updateRemarksVisibility = () => {
    //                       if (guestRadio.checked) {
    //                           remarksContainer.style.display = 'block';
    //                           remarksInput.required = true;
    //                       } else {
    //                           remarksContainer.style.display = 'none';
    //                           remarksInput.required = false;
    //                       }
    //                       // Clear validation message if MCO is selected
    //                       if (mcoRadio.checked) {
    //                           Swal.resetValidationMessage();
    //                       }
    //                   };
                      
    //                   mcoRadio.addEventListener('change', updateRemarksVisibility);
    //                   guestRadio.addEventListener('change', updateRemarksVisibility);
                      
    //                   // Initial call to set the correct state
    //                   updateRemarksVisibility();
    //               },
    //               preConfirm: () => {
    //                   const selectedRadio = document.querySelector('input[name="flexRadioDefault"]:checked');
    //                   if (!selectedRadio) {
    //                       Swal.showValidationMessage('Please select a type of consumer');
    //                       return false; // Return false to prevent SweetAlert from closing
    //                   }
    //                   if (selectedRadio.value === 'Guest' && !document.getElementById('remarks').value) {
    //                       Swal.showValidationMessage('Remarks are required for Guest');
    //                       return false; // Return false to prevent SweetAlert from closing
    //                   }
    //                   return {
    //                       consumerType: selectedRadio.value,
    //                       remarks: document.getElementById('remarks').value
    //                   }; // Return an object with the selected value and remarks to be used in the next `.then()`
    //               }
    //           }).then((result) => {
    //             if (result.isConfirmed) {
    //                 const selectedValue = result.value.consumerType;
    //                 const remarksValue = result.value.remarks;
    //                 const account_no = document.getElementById('hidden_id').value;
    //                 console.log(account_no);
    //                 // Set the selected value to the hidden input field
    //                 document.getElementById('swalValue').value = selectedValue;
    //                 // Set the remarks value to the hidden remarks field
    //                 document.getElementById('hidden_remarks').value = remarksValue;
    //                 // Submit the form
    //                 // event.target.submit();
    //             }
    //           });
    //       }
    //   });
    // });

    function showConfirmationDialog(accountId) {
        Swal.fire({
            title: 'Do you really want to verify this consumer?',
            html: `
                <div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault_${accountId}" value="MCO" id="mco_${accountId}">
                        <label class="form-check-label" for="mco_${accountId}">MCO</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault_${accountId}" value="Guest" id="guest_${accountId}">
                        <label class="form-check-label" for="guest_${accountId}">Guest</label>
                    </div>
                    <div>
                        <div class="m-2" id="remarks-container_${accountId}">
                            <label for="remarks_${accountId}" class="form-label">Remarks: (Optional)</label>
                            <input type="text" class="form-control" id="remarks_${accountId}" name="remarks">
                        </div>
                    </div>
                </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            // didOpen: () => {
            //     const updateRemarksVisibility = () => {
            //         const guestRadio = document.getElementById('guest_' + accountId);
            //         const remarksContainer = document.getElementById('remarks-container_' + accountId);
            //         const remarksInput = document.getElementById('remarks_' + accountId);
            //         if (guestRadio.checked) {
            //             remarksContainer.style.display = 'block';
            //             remarksInput.required = true;
            //         } else {
            //             remarksContainer.style.display = 'none';
            //             remarksInput.required = false;
            //         }
            //         // Clear validation message if MCO is selected
            //         if (document.getElementById('mco_' + accountId).checked) {
            //             Swal.resetValidationMessage();
            //         }
            //     };
                
            //     document.querySelectorAll(`input[name="flexRadioDefault_${accountId}"]`).forEach(radio => {
            //         radio.addEventListener('change', updateRemarksVisibility);
            //     });
                
            //     // Initial call to set the correct state
            //     updateRemarksVisibility();
            // },
            preConfirm: () => {
                const selectedRadio = document.querySelector(`input[name="flexRadioDefault_${accountId}"]:checked`);
                if (!selectedRadio) {
                    Swal.showValidationMessage('Please select a type of consumer');
                    return false; // Return false to prevent SweetAlert from closing
                }
                // if (selectedRadio.value === 'Guest' && !document.getElementById('remarks_' + accountId).value) {
                //     Swal.showValidationMessage('Remarks are required for Guest');
                //     return false; // Return false to prevent SweetAlert from closing
                // }
                return {
                    consumerType: selectedRadio.value,
                    remarks: document.getElementById('remarks_' + accountId).value
                }; // Return an object with the selected value and remarks to be used in the next `.then()`
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedValue = result.value.consumerType;
                const remarksValue = result.value.remarks;
                // Set the selected value to the hidden input field
                document.getElementById('swalValue_' + accountId).value = selectedValue;
                // Set the remarks value to the hidden remarks field
                document.getElementById('hidden_remarks_' + accountId).value = remarksValue;
                // Submit the form
                document.getElementById('confirmationForm_' + accountId).submit();
            }
        });
    }

    // Initialize HTML5-QRCode
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", // elementId for the QR code scanner
        { fps: 25, qrbox: 250 } // optional configurations
    );

    // Define callback for when QR code is detected
    function onScanSuccess(qrCode) {
        // this will stop the scanner (video feed) and clear the scan area.
        html5QrcodeScanner.clear(); 

        // Call fetchAllowance with qrCode
        verifyAccount(qrCode);
    }

    function verifyAccount(qrCode){
      // Swal.fire({
      //     title: 'Are you sure?',
      //     text: "Do you want to verify ",
      //     icon: 'warning',
      //     showCancelButton: true,
      //     confirmButtonColor: '#3085d6',
      //     cancelButtonColor: '#d33',
      //     confirmButtonText: 'Confirm'
      // }).then((result) => {
      //     if (result.isConfirmed) {
      //       event.target.submit(); // If confirmed, submit the form
      //     }
      //     else{
      //       html5QrcodeScanner.render(onScanSuccess);
      //     }
      // });

      const myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");
      // myHeaders.append("Authorization", "Bearer HdSQxFktaKelqX3AC9HbEJfHiGxYaapchoUxpEGr");
      myHeaders.append("Authorization", "Bearer {{ config('services.auth.bearer_token') }}");

      const requestOptions = {
          method: "GET",
          headers: myHeaders,
          redirect: "follow"
      };

      // Construct the URL dynamically
      var url = "{{ route('checkRegistration', ':qrCode') }}".replace(':qrCode', qrCode);
        // console.log(url);
      fetch(url, requestOptions)
            .then((response) => response.json()) // Parse response as JSON
            .then((result) => {
                // Extract account number from result
                var accountNo = result.registration[0].account_no;
                // Format account number
                var formattedAccountNo = accountNo.replace(/(\d{2})(\d{4})(\d{4})/, "$1-$2-$3");
                // console.log(result);
                // check if the qrcode is used
                const registration = result.registration[0];
                const membershipName = `${registration.mem_last_name}, ${registration.mem_first_name} ${registration.mem_middle_name}`;
                if(result.status_message != "error"){
                  // Display allowance data in SweetAlert popup
                  Swal.fire({
                      icon: 'question',
                      title: 'Confirm Registration?',
                      // html:
                      //         '<div>Name: '+ result.registration[0].first_name + ' ' + result.registration[0].last_name +'</div>' + 
                      //         '<div>Account Number: ' + formattedAccountNo + '</div>',

                              html:
                              '' + 
                              `<div>
                                <div>Account Name: `+ result.registration[0].last_name + ', ' + result.registration[0].first_name +`</div>
                                <div class="pb-2" >Account #: ` + formattedAccountNo + `</div>
                                <div class="pb-2 text-danger" >Membership Name: ${membershipName} </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="radio" value="MCO" id="mco">
                                  <label class="form-check-label" for="mco">
                                    MCO
                                  </label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input" type="radio" name="radio" value="Guest" id="guest">
                                  <label class="form-check-label" for="guest">
                                    Guest
                                  </label>
                                </div>
                                <div>
                                    <div class="m-2" id="remarks-container">
                                        <label for="pre_reg_remarks" class="form-label">Remarks: (Optional)</label>
                                        <input type="text" class="form-control" id="pre_reg_remarks" name="pre_reg_remarks">
                                    </div>
                                </div>
                              </div>`,
                      confirmButtonText: 'Confirm',
                      showCancelButton: true,
                      cancelButtonText: 'Cancel',
                      preConfirm: () => {
                          const selectedRadio = document.querySelector('input[name="radio"]:checked');
                          if (!selectedRadio) {
                              Swal.showValidationMessage('Please select a type of consumer');
                              return false; // Return false to prevent SweetAlert from closing
                          }
                          // return selectedRadio.value; // Return the value to be used in the next `.then()`

                          return {
                              consumerType: selectedRadio.value,
                              remarks: document.getElementById('pre_reg_remarks').value
                          };

                      }
                  }).then((result) => {
                      if (result.isConfirmed) {
                          const selectedConsumerType = result.value.consumerType;
                          const remarksValue = result.value.remarks;
                        
                          // const selectedConsumerType = result.value; // Capture the selected radio button value
                        
                          const myHeaders1 = new Headers();
                          myHeaders1.append("Accept", "application/json");
                          // myHeaders.append("Authorization", "Bearer HdSQxFktaKelqX3AC9HbEJfHiGxYaapchoUxpEGr");
                          myHeaders1.append("Authorization", "Bearer {{ config('services.auth.bearer_token') }}");

                          const requestOptions = {
                          method: "GET",
                          headers: myHeaders1,
                          redirect: "follow"
                          };

                          // Construct the URL dynamically
                          var url = "{{ route('verifyPreRegistration', ':qrCode') }}".replace(':qrCode', qrCode);

                          // Include selectedConsumerType in the API request if needed (e.g., as a query parameter)
                          url += `?consumerType=${selectedConsumerType}?remarks=${remarksValue}`;

                          fetch(url, requestOptions)
                          .then((response) => response.json())
                          .then((result) => {
                            console.log(result);
                                  Swal.fire({
                                      icon: result.status_message,
                                      title: result.message,
                                      confirmButtonText: 'OK'
                                  }).then((result1) => {
                                      if (result1.isConfirmed && result.status_message === 'success') {
                                          // html5QrcodeScanner.render(onScanSuccess);
                                        var url = "{{ route('printRegistrationQR', ':accountNo') }}".replace(':accountNo', accountNo); // Specify your URL here
                                        window.location.href = url;
                                      }
                                      else{
                                        html5QrcodeScanner.render(onScanSuccess);
                                      }
                                  });
                          })
                          .catch((error) => console.error(error));
                      } else {
                          // If user clicks "No", restart the QR code scanner
                          html5QrcodeScanner.render(onScanSuccess);
                      }
                  });
                }
                else{
                  // alert("false");
                    Swal.fire({
                        icon: 'error',
                        title: result.message,
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            html5QrcodeScanner.render(onScanSuccess);
                        }
                    });
                }
            })
            .catch((error) => {
                // Show error message in SweetAlert popup
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error QR Code Value!',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // If user clicks "OK", restart the QR code scanner
                        html5QrcodeScanner.render(onScanSuccess);
                    }
                });
            });


    }

    // Event listener for modal shown event to start scanning
    $('#scanner').on('shown.bs.modal', function () {
        html5QrcodeScanner.render(onScanSuccess);
    });

    // Optional: Event listener for modal hidden event to stop scanning
    $('#scanner').on('hidden.bs.modal', function () {
        html5QrcodeScanner.clear();
    });

    function rePrint(){
      var txtAccounNo = document.getElementById("account_no_reprint").value;

      const myHeaders = new Headers();
      myHeaders.append("Accept", "application/json");
      // myHeaders.append("Authorization", "Bearer HdSQxFktaKelqX3AC9HbEJfHiGxYaapchoUxpEGr");
      myHeaders.append("Authorization", "Bearer {{ config('services.auth.bearer_token') }}");

      const requestOptions = {
      method: "GET",
      headers: myHeaders,
      redirect: "follow"
      };

      // Construct the URL dynamically
      var url = "{{ route('getVerifiedRegistration', ':accountNo') }}".replace(':accountNo', txtAccounNo);

      fetch(url, requestOptions)
      .then((response) => response.json())
      .then((result) => {
        console.log(result.status_message);
        if (result.status_message != 'error') {
          var url = "{{ route('printRegistrationQR', ':accountNo') }}".replace(':accountNo', txtAccounNo); // Specify your URL here
          // window.open(url); // '_blank' opens the URL in a new tab
          window.location.href = url;
        } else {
          Swal.fire({
            icon: result.status_message,
            title: result.message,
            confirmButtonText: 'OK'
          });
        }
        
      })
      .catch((error) => console.error(error));

    }

  </script>
@endsection
@section('style')
<style>
  .badge{
    line-height: 0.3;
  }
  .full-width-button {
      width: 100%;
  }
</style>
@endsection