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

        #qrcode, img{
            /* display: none; */
            margin: 0 auto;
        }

        #qrcode > img{
            border: 2px solid black;
            padding: 3px;
            border-radius: 10px;
        }

        #footer {
            font-size: 12px;
        }
        
        @media screen and (min-width: 768px) {
            #qrcode{
                float: right; /* Float the QR code to the right */
                margin: 0; /* Remove margin for mobile screens */
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
                font-size: 15px;
                text-align: center;
                padding-top: 10px;
            }
            #details, #footer{
                font-size: 15px;
            }
            #qrcode > img{
                width: 110px;
                height: 110px;
                /* padding: 30px; */
            }
            .card-body{
                padding: 1px;
            }
            #print-container{
                padding: 7px !important;
            }
            #main-card{
                padding-top: 1px !important;
            }
        }

        @media print, (max-width: 600px) {
            #heading {
                font-size: 15px;
                text-align: center !important;
                font-weight: bold;
            }
            #qrcode > img {
                border: 2px solid black !important;
                width: 90px !important;
                height: 90px !important;
            }
            #details, #footer{
                font-size: 10px !important;
                text-align: left !important;
            }
            #footer{
                font-size: 15px !important;
                text-align: left !important;
                font-weight: bold;
            }
            
        }
    </style>
</head>
<body>
<div class="container p-5" id="print-container">
    <div id="dataToDownload" >
        <div class="card col-lg-6 p-3 mx-auto" id="main-card">
            <div class="card-body">
                <div class="h4 mb-3 text-center" id="heading">LEYECO V 43rd <br>AGMM REGISTRATION DETAILS</div>
                <div class="row my-2">
                    <div class="col-4 col-md-6 justify-content-center align-items-center">
                        <div id="qrcode" class="img-auto"></div>
                    </div>
                    <div class="col mt-2" id="details">
                        <strong>Name: <br>{{ $details->name }}</strong><br>
                        <strong>Account No. : {{ substr_replace(substr_replace($details->account_no, '-', 2, 0), '-', 7, 0) }}</strong><br>
                        <strong>Contact No. : {{ $details->contact_no ? $details->contact_no : 'None' }}</strong><br>
                        <strong>Membership OR: {{ $details->membership_or }}</strong><br>
                        <strong>Date: {{ \Carbon\Carbon::parse($details->created_at)->format('m/d/Y h:i A') }}</strong>
                    </div>
                </div>
                <div class="text-end mt-4" id="footer">Consumer Type: {{ $details->registration_type }}</div>
                <div class="mb-3 text-end" id="details">Verified By: {{ strtoupper($verifier) }}</div>
                <div class="row justify-content-center" id="buttons">
                    <div class="col-auto">
                        <button class="btn btn-sm btn-secondary my-1" id="download"  onclick="downloadData()"><i class="fas fa-download"></i> Download</button>
                    </div>
                    <div class="col-auto">
                        <a href="#" class="btn btn-sm btn-success my-1" id="print" onclick="printTab()"><i class="fas fa-print"></i> Print</a>
                    </div>
                    <div class="col-auto">
                        <a href="#" class="btn btn-sm btn-warning my-1" id="close" onclick="closeTab()"><i class="fas fa-times"></i> Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script>
    const qrcode = new QRCode(document.getElementById('qrcode'), {
        text: "{{ $details->qr_code_value }}",
        width: 110,
        height: 110,
        colorDark: '#000',
        colorLight: '#fff',
        // correctLevel: QRCode.CorrectLevel.M
    });

    // Function to automatically trigger printing
    function printTab() {
        window.print(); // Automatically print
    }

    function closeTab() {
        if (confirm("Are you sure you want to close this window?")) {
            window.location.href = "{{ route('agmmAccounts') }}";
        }
    }

    function downloadTab() {
        window.close(); // Close the current tab
    }

    function downloadData() {

        document.getElementById('buttons').style.visibility = 'hidden';

        // Get the content of the data to be downloaded
        var dataToDownload = document.getElementById('dataToDownload');

        // Convert the HTML content to an image using html2canvas
        html2canvas(dataToDownload, {
            onrendered: function(canvas) {
                // Convert the canvas to a data URL representing a PNG image
                var imageDataUrl = canvas.toDataURL('image/png');

                // Create a temporary link element
                var link = document.createElement('a');
                link.href = imageDataUrl;
                link.download = 'agmm_registration_details.png'; // Set the file name

                // Trigger the download
                link.click();

                document.getElementById('buttons').style.visibility = 'visible';
            }
        });
    }
</script>
</body>
</html>


<style>
    
</style>