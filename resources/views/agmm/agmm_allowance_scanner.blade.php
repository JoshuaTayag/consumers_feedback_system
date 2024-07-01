<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form Print</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        html {
            height: 100%;
            margin: 0;
        }

        body {
            background: -webkit-gradient(linear, left bottom, left top, from(#b9e0ff), to(#30a3ff));
            background: -webkit-linear-gradient(bottom, #b9e0ff 0%, #30a3ff 100%);
            background: -moz-linear-gradient(bottom, #b9e0ff 0%, #30a3ff 100%);
            background: -o-linear-gradient(bottom, #b9e0ff 0%, #30a3ff 100%);
            background: linear-gradient(to top, #b9e0ff 0%, #30a3ff 100%);
            height: 100%;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }



        #qr-reader{
            width: 300px; 
            height: 300px;
        }

        .full-width-button {
            width: 100%;
        }

        
        /* Media query for screens smaller than 768px (commonly used for mobile devices) */
        @media screen and (max-width: 768px) {
            #qr-reader {
                width: 100%; /* Set width to 100% of parent element */
                height: auto; /* Allow height to adjust proportionally based on width */
            }
        }


        

        @media print {
            @page {
                size: 80mm 100%; /* Set paper size */
                margin: 0; /* Remove default margins */
            }
            #download, #print, #close {
                display: none;
            }
            #heading{
                font-size: 19px;
            }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container p-5" id="print-container">
    <div class="card col-lg-6 p-3 mx-auto">
        <div class="card-body">
            <div class="h4 mb-3" id="heading">LEYECO V 43rd AGMM Transportation Allowance</div>
            <div class="row my-2">
                <div class="col">
                    <div id="qr-reader" class="mx-auto"></div>
                </div>
            </div>
            <!-- <div class="row justify-content-center" id="buttons">
                <div class="col-auto">
                    <button class="btn btn-sm btn-secondary my-1" id="download"  onclick="downloadData()"><i class="fas fa-download"></i> Scan QR</button>
                </div>
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-warning my-1" id="close" onclick="closeTab()"><i class="fas fa-times"></i> Close</a>
                </div>
            </div> -->
        </div>
    </div>
    <div class="card col-lg-6 p-3 mt-3 mx-auto">
        <div class="card-body">
            <div class="h4 mb-3" id="heading">DASHBOARD</div>
            <div class="row my-2">
                <div class="col">
                    <p>Total consumers claimed: {{ $total_scanned_consumers }}</p>
                    <p>Total allowance disbursed: ₱{{ number_format($total_disbursed_money, 0) }}</p>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-6">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger full-width-button">Logout</button>
                    </form>
                </div>
                <div class="col-6">
                    <a href="{{ route('home') }}" class="btn btn-sm btn-warning full-width-button"> <i class="fa fa-home me-2"></i>Home</a>
                </div>
            </div>

            <!-- <div class="row justify-content-center" id="buttons">
                <div class="col-auto">
                    <button class="btn btn-sm btn-secondary my-1" id="download"  onclick="downloadData()"><i class="fas fa-download"></i> Scan QR</button>
                </div>
                <div class="col-auto">
                    <a href="#" class="btn btn-sm btn-warning my-1" id="close" onclick="closeTab()"><i class="fas fa-times"></i> Close</a>
                </div>
            </div> -->
        </div>
    </div>
</div>
<script>
    // Initialize HTML5-QRCode
    var html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", // elementId for the QR code scanner
        { fps: 25, qrbox: 200 } // optional configurations
    );

    // Define callback for when QR code is detected
    function onScanSuccess(qrCode) {
        // this will stop the scanner (video feed) and clear the scan area.
        html5QrcodeScanner.clear(); 

        // Call fetchAllowance with qrCode
        fetchAllowance(qrCode);  
    }

    function fetchAllowance(qrCode) {
        const myHeaders = new Headers();
        // const token = getAuthToken();
        // console.log(token);
        myHeaders.append("Accept", "application/json");
        // myHeaders.append("Authorization", "Bearer HdSQxFktaKelqX3AC9HbEJfHiGxYaapchoUxpEGr");

        myHeaders.append("Authorization", "Bearer {{ config('services.auth.bearer_token') }}");

        const requestOptions = {
            method: "GET",
            headers: myHeaders,
            redirect: "follow"
        };

        // Construct the URL dynamically
        var url = "{{ route('transpoAllowance', ':qrCode') }}".replace(':qrCode', qrCode);

        // Use the qrCode parameter in the URL to fetch allowance data
        fetch(url, requestOptions)
            .then((response) => response.json()) // Parse response as JSON
            .then((result) => {
            //   console.log(result.status_message);
              
                //   check if it has error
                if(result.status_message == "success"){

                // Extract account number from result
                var accountNo = result.registration[0].account_no;
                // Format account number
                var formattedAccountNo = accountNo.replace(/(\d{2})(\d{4})(\d{4})/, "$1-$2-$3");
                
                    // check if the qrcode is used
                    if(result.registration[0].allowance_status == 0){
                        // Display allowance data in SweetAlert popup
                        Swal.fire({
                            icon: 'question',
                            title: 'Confirm Registration?',
                            html:
                                    '<div>Name: ' + result.registration[0].name +  '</div>' + 
                                    '<div>Account Number: ' + formattedAccountNo  + '</div>' +
                                    '<div>Transpo Allowance: ₱' + result.registration[0].transpo_allowance + '.00</div>' +
                                    '<div><div class="m-2"><label for="remarks" class="form-label">Remarks: (Optional) </label><input type="text" class="form-control" id="remarks" name="remarks"></div></div>',
                            confirmButtonText: 'Confirm',
                            showCancelButton: true,
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                const myHeaders = new Headers();
                                myHeaders.append("Accept", "application/json");
                                //   myHeaders.append("Authorization", "Bearer HdSQxFktaKelqX3AC9HbEJfHiGxYaapchoUxpEGr");
                                //   myHeaders.append("Authorization", "Bearer {{ config('services.auth.bearer_token') }}");

                                const remarks = document.getElementById('remarks').value;
                                const qr_with_remarks = qrCode+"|"+remarks;
                                console.log(qr_with_remarks);
                                const requestOptions = {
                                method: "GET",
                                headers: myHeaders,
                                redirect: "follow"
                                };

                                // Construct the URL dynamically
                                var url = "{{ route('issueTranspoAllowance', ':qrCode') }}".replace(':qrCode', qr_with_remarks);

                                fetch(url, requestOptions)
                                .then((response) => response.json())
                                .then((result) => {
                                        Swal.fire({
                                            icon: result.status_message,
                                            title: result.message,
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                //   html5QrcodeScanner.render(onScanSuccess);
                                                location.reload(true);
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
                        Swal.fire({
                            icon: 'error',
                            title: 'QR code already used!',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                html5QrcodeScanner.render(onScanSuccess);
                            }
                        });
                    }

                }
                else{
                    Swal.fire({
                    icon: result.status_message,
                    title: result.message,
                    confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If user clicks "OK", restart the QR code scanner
                            html5QrcodeScanner.render(onScanSuccess);
                        }
                    });
                }
              
            });     
    }


    // Start scanning
    html5QrcodeScanner.render(onScanSuccess);
</script>
</body>
</html>