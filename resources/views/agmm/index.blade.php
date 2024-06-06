@extends('layouts.app')


@section('content')
<div class="container">
  <div class="row justify-content-center">
      <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                  <div class="col-lg-6">
                      <span class="mb-0 align-middle fs-3">VERIFY ACCOUNTS</span>
                  </div>
                  <div class="col-lg-6 text-end">
                    <a type="button" class="btn btn-sm btn-secondary btn-md text-end" data-bs-toggle="modal" data-bs-target="#rePrint">
                      <i class="fa fa-refresh"></i> RE-PRINT
                    </a>
                    <a type="button" class="btn btn-sm btn-secondary btn-md text-end" data-bs-toggle="modal" data-bs-target="#scanner">
                      <i class="fa fa-qrcode"></i> PRE-REGISTER
                    </a>
                  </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row pb-2">
                <form action="{{ route('agmmAccounts') }}" method="GET">
                  <div class="col-lg-4 pb-2">
                      <input type="text" placeholder="Search by Name" id="account_name" name="account_name" value="{{ request('account_name') }}" class="form-control">
                  </div>
                  <div class="col-lg-4 pb-2">
                      <input type="text" placeholder="Search by Account" id="account_no" name="account_no" maxlength="10" value="{{ request('account_no') }}" class="form-control">
                  </div>
                  <div class="col-lg-4 pb-2">
                      <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> SEARCH</button>
                  </div>
                </form>
              </div>
              <div class="table-responsive">
              <table class="table table-striped table-bordered ">
                <thead>
                  <tr class="text-center">
                    <th>Account</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="show_data">
                  @foreach ($accounts as $index => $account)               
                    <tr class="text-center">
                      <th>{{ substr($account->id, 0, 2) }}-{{ substr($account->id, 2, 4) }}-{{ substr($account->id, 6, 4) }}</th>
                      <th>{{ $account->Name }}</th>
                      <th>{{ $account->Address }}</th>
                      <th>
                        <form id="confirmationForm" method="POST" action="{{ route('agmmVerifyAccount', $account->id) }}">
                          @csrf
                          <button class="btn btn-success btn-sm" type="submit"><i class="fa fa-check"></i></button>
                        </form>
                      </th>
                    </tr>
                  @endforeach 
                </tbody>
               </table>
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
        <h5 class="modal-title" id="staticBackdropLabel">Scan QR Code</h5>
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
    document.addEventListener('DOMContentLoaded', function () {
      document.body.addEventListener('submit', function (event) {
          if (event.target.matches('#confirmationForm')) {
              event.preventDefault(); // Prevent the form from submitting immediately

              Swal.fire({
                  title: 'Are you sure?',
                  text: "Do you really want to submit this form?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Confirm'
              }).then((result) => {
                  if (result.isConfirmed) {
                      event.target.submit(); // If confirmed, submit the form
                  }
              });
          }
      });
    });

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
                if(result.status_message != "error"){
                  // Display allowance data in SweetAlert popup
                  Swal.fire({
                      icon: 'question',
                      title: 'Confirm Registration?',
                      html:
                              '<div>Name: '+ result.registration[0].first_name + ' ' + result.registration[0].last_name +'</div>' + 
                              '<div>Account Number: ' + formattedAccountNo + '</div>',
                      confirmButtonText: 'Confirm',
                      showCancelButton: true,
                      cancelButtonText: 'Cancel'
                  }).then((result) => {
                      if (result.isConfirmed) {
                        
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
                          var url = "{{ route('verifyPreRegistration', ':qrCode') }}".replace(':qrCode', qrCode);

                          fetch(url, requestOptions)
                          .then((response) => response.json())
                          .then((result) => {
                            console.log(result);
                                  Swal.fire({
                                      icon: result.status_message,
                                      title: result.message,
                                      confirmButtonText: 'OK'
                                  }).then((result) => {
                                      if (result.isConfirmed) {
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
</style>
@endsection