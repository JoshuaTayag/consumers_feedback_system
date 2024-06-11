<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10"> -->
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

        .custom-primary {
            background-color: #78c7ff;
            border-color: #5d82ee; /* Optional: If you want to change border color */
            color: black; /* Optional: Text color */
            border-radius: 20px !important;
            font-weight: bold;
        }
        .custom-primary:hover {
            background-color: #ffd900; /* Optional: Hover color */
            border-color: #78c7ff; /* Optional: Hover border color */
            color: black; /* Optional: Hover text color */
        }

        .form-label{
            font-size: 20px;
        }
        .left-side {
            background-image: url('https://leyeco-v.com.ph/images/43rd-AGMM-Updated.png');
            background-size:  contain;
            background-repeat: no-repeat;
            background-position: center;
            color: #fff; /* Change text color to white */
            border-top-left-radius: 10px; /* Apply border radius to top left corner */
            border-bottom-left-radius: 10px; /* Apply border radius to bottom left corner */
            overflow: hidden; /* Clip the image to the rounded corners */
        }

        #form-background{
            background-color: white;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            margin: 0;
            
        }

        @media print {
            @page {
                size: A6; /* Set paper size */
                margin: 0; /* Remove default margins */
                /* padding: 0; */
            }
            body *:not(#printable-area) {
                display: none; /* Hide all elements except for the printable area */
            }

            #printable-area {
                display: block !important; /* Ensure printable area is displayed */
            }

        }

    </style>
</head>
<body>
<div class="container">
    @if ($errors->any())
    <div class="row justify-content-center pt-4">
        <div class="col-md-8 col-sm-8">
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
    <div class="container pt-5">
        <div class="row" id="form-background">
            <div class="col-md-6 left-side">

            </div>
            <div class="col-md-6 p-5">
                <div class="row pb-4">
                    <div class="col-md-2 col-sm-6">
                        <img src="https://leyeco-v.com.ph/images/logo.png" alt="" class="img-fluid" width="85px">
                    </div>
                    <div class="col-md-10 d-flex align-items-center">
                        <h3 class="text-center">43rd AGMM ON-SITE Registration Form</h3>
                    </div>
                </div>
                <form method="POST" action="{{ route('agmmRegisterPost') }}">
                    @csrf
                    <div class="mb-2">
                        <label for="first_name" class="form-label">First Name <span class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                    </div>
                    <div class="mb-2">
                        <label for="last_name" class="form-label">Last Name <span class="text-danger fw-bold">*</span></label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                    </div>
                    <div class="mb-2">
                        <label for="account_no" class="form-label">Account Number (Ex: 00-0000-0000)<span class="text-danger fw-bold"> *</span></label>
                        <input type="text" class="form-control" id="account_no" name="account_no" maxlength="12" value="{{ old('account_no') ? preg_replace('/(\d{2})(\d{4})(\d{4})/', '$1-$2-$3', old('account_no')) : '' }}" pattern="\d{2}-\d{4}-\d{4}" required>
                    </div>
                    <div class="mb-2">
                        <label for="contact_no" class="form-label">Contact No (Ex: 09*********) <span class="text-danger fw-bold"> *</span></label>
                        <input type="text" class="form-control @error('contact_no') is-invalid @enderror" value="{{ old('contact_no') }}" id="contact_no" pattern="^((09))[0-9]{9}" name="contact_no" maxlength="11" required>
                    </div>
                    <hr class="my-3">
                    <div class="mb-2 text-center">
                        <button type="submit" class="btn custom-primary rounded px-4">REGISTER</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
@if(Session::has('successMessage'))
    <script>
        // SweetAlert message with QR code
        var swalMessageWithQR = `
            <div>
                <h3 class="pb-3" >{{ Session::get('successMessage') }}</h3>
            </div>
        `;

        // Trigger the SweetAlert message for success
        Swal.fire({
            html: swalMessageWithQR,
            icon: 'success',
            showCloseButton: true,
            allowOutsideClick: false,
            showCancelButton: true,
            cancelButtonText: 'View Details',
        }).then((result) => {
            // Check if the cancel button was clicked
            if (result.dismiss === Swal.DismissReason.cancel) {
                // Redirect the page
                window.open("{{ route('printRegistrationQRGuest', Session::get('data')['AccountNumberWithoutHypens']) }}", "_blank");

            }
        });
    </script>
@endif

@if(Session::has('errorMessage'))
    <script>
        var swalMessageWithQR = `
            <div>
                <h3 class="pb-3" >{{ Session::get('errorMessage') }}</h3>
            </div>
        `;

        // Trigger the SweetAlert message for success
        Swal.fire({
            html: swalMessageWithQR,
            icon: 'error',
            allowOutsideClick: false,
            showCloseButton: true,
            // timer: 3000, // Set the timer for auto close (milliseconds)
        });
    </script>
@endif


<script>
    document.getElementById('account_no').addEventListener('input', function() {
        var value = this.value.replace(/\D/g, ''); // Remove non-numeric characters
        var formattedValue = '';

        // Insert hyphens at the appropriate positions
        for (var i = 0; i < value.length; i++) {
            if (i === 2 || i === 6) {
                formattedValue += '-';
            }
            formattedValue += value.charAt(i);
        }

        // Update the input field value
        this.value = formattedValue;
    });
</script>
</body>
</html>