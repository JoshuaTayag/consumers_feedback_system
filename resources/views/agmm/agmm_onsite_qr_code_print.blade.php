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
        
        @media (max-width: 600px) { 

            .qr-container {
                display: flex;
                justify-content: center; /* Center horizontally */
                align-items: center;     /* Center vertically */
                flex-direction: column;  /* Ensure flex direction is column */
            }
            
            /* Center the QR code itself */
            #qrcode {
                float: none;    /* Remove float */
                margin: 0 auto; /* Center the QR code horizontally */
            }
            
            #heading{
                font-size: 11px !important;
                text-align: center;
                padding-top: 0px;
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
                font-size: 8px;
                text-align: center;
                padding-top: 0px;
            }
            #qrcode > img{
                width: 100px;
                height: 100px;
                /* padding: 30px; */
            }
        }

        @media print, (max-width: 600px) {
            #heading {
                font-size: 10px;
                text-align: left !important;
            }
            #qrcode > img {
                border: 2px solid black !important;
                width: 180px !important;
                height: 180px !important;
            }      
        }
    </style>
</head>
<body>
<div class="container" id="print-container">
    <div id="dataToDownload" >
        <div class="card col-lg-6 p-3 mx-auto" id="main-card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    {{-- <img src="{{asset('images/logo.png')}}" style="width: 50px" alt="..." class="text-center"> --}}
                </div>
                <div class="h5 mb-1 text-center">LEYECO V 45th AGMA</div>
                <div class="mb-3 text-center">(PRE-REGISTRATION SLIP)</div>
                <div class="row my-2">
                    <div class="col justify-content-center align-items-center qr-container">
                        <div id="qrcode" class="img-auto"></div>
                    </div>
                </div>
                <div class="row justify-content-center" style="font-size:12px">
                    <div class="col-auto">
                        <p class="mb-1 fw-bold">Account: {{ substr_replace(substr_replace($details->account_no, '-', 2, 0), '-', 7, 0) }}</p>
                        <p class="mb-1 fw-bold">Name: {{ $details->last_name.', '.$details->first_name.' '.$details->middle_name }}</p>
                        <p class="mb-1 fw-bold">Address: {{ $details->Address }}</p>
                        <p class="mb-1 fw-bold">Reg Date: {{ \Carbon\Carbon::parse($details->created_at)->format('m/d/Y h:i A') }}</p>
                        <p class="mb-1 fw-bold">Registered By: {{ strtoupper($verifier) }}</p>
                        <hr>
                        <p class="mb-1 fw-bold">Date: July 11, 2026 </p>
                        <p class="mb-1 fw-bold">Time: 08:00 AM - 4:00 PM </p>
                        @if ($details->ConMunicipality == 'Albuera' || $details->ConMunicipality == 'Kananga' || 
                            $details->ConMunicipality == 'Merida' || 
                            $details->ConMunicipality == 'Palompon' ||
                            $details->ConMunicipality == 'Isabel' || 
                            $details->ConMunicipality == 'Matag-ob' || 
                            $details->ConMunicipality == 'Ormoc City' || 
                            $details->ConMunicipality == 'Ormoc North' || 
                            $details->ConMunicipality == 'Ormoc South')
                            <p class="mb-1 fw-bold">Venue1: ORMOC CITY SUPERDOME</p>
                        @else
                            <p class="mb-1 fw-bold">Venue2: ROMEO ARANTE YSIDORO GYM, LEYTE, LEYTE</p>
                        @endif
                    <div>
                </div>
                
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
        width: 100,
        height: 100,
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
            window.close();
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