<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="https://fonts.cdnfonts.com/css/franklin-gothic" rel="stylesheet">
  <title>TSD Form</title>
  <style>
    header{
      font-family: 'Franklin Gothic', sans-serif;
      position: relative;
    }
    .heading{
      text-align: center;
      margin: 10px;
    }
    .sub-heading{
      text-align: center;
      font-size: 15px;
    }
    hr, .black{
      padding: 0x;
      margin: 0px;
      border: 1px solid black;
    }
    .img-logo{
      position: absolute;
      top: 65px;
      /* left: 20px; */
      height: 80px;
      width: 80px;
    }
    .img-iso{
      position: absolute;
      top: 65px;
      /* right: 10px; */
      height: 80px;
      width: 100px;
    }
    .img-signature{
      height: 70px;
      width: 150px;
      margin-bottom: -15px;
    }
    .text-center{
      text-align: center;
    }
    .text-center, h3, h4{
      text-align: center;
      line-height: 0.3;
    }
    .text-underline{
      text-decoration: underline;
      text-transform: uppercase;
    }
    .text-align {
      text-align: justify;
      text-justify: inter-word;
    }
    .styled-table {
        border-collapse: collapse;
        /* margin: 25px 0; */
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        margin-left: auto;
        margin-right: auto;
        padding-top: 20px;
        padding-bottom: 20px;
    }
    .styled-table thead tr {
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 4px 5px;
    }

    .container {
            border: 1px solid black;
            padding: 10px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-left {
            text-align: left;
            width: 60%;
        }
        .header-left h1 {
            margin: 0;
            font-size: 18px;
        }
        .header-right {
            text-align: right;
            width: 40%;
        }
        .info {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
        }
        .info div {
            width: 30%;
        }
        .title {
            text-align: center;
            margin: 20px 0;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .new-header {
            width: 100%; /* Make the table full width */
            border-collapse: collapse; /* Remove gaps between cell borders */
        }
        .new-header td, .new-header th {
            border: 1px solid black; /* Add borders to all cells */
            padding: 8px; /* Add padding inside the cells */
            text-align: left; /* Align text to the left */
        }
        thead {
            background-color: #f0f0f0; /* Optional: Light background color for the header */
        }
  </style>
</head>
<body>
  @php
    $consumerTypes = collect(Config::get('constants.consumer_types'));
    $consumerType = $consumerTypes->firstWhere('id', $data->consumer_type);
  @endphp
  {{-- <header>
    <img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo">
    <img src="{{ public_path('images/iso.png') }}" alt="" class="img-iso">
    <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
    <p class="sub-heading">
      (LEYECO V)<br>
      Brgy. San Pablo, Ormoc City, Leyte
    </p>
  </header> --}}

    {{-- <div class="header">
        <div class="header-left">
            <h1>LEYTE V ELECTRIC COOPERATIVE, INC.</h1>
            <p>Brgy. San Pablo, Ormoc City, Leyte<br>
            Telephone Nos.: PLDT: (053) 839-3920 to 3921 / Globe: (053) 561-4466<br>
            Cellular Phone Nos. Calls Only: Smart: 0998-964-3804; Globe: 0917-836-3895<br>
            Website: www.leyeco-v.com.ph    eMail Address: info@leyeco-v.com.ph</p>
        </div>
        <div class="header-right">
            <p>ISO 9001<br>Quality Management<br>Certified</p>
        </div>
    </div>
    <div class="info">
        <div>Document No.: FM-MSD-012</div>
        <div>Revision No.: 001</div>
        <div>Effectivity Date:</div>
    </div>
    <div class="title">
        Request for Change Meter
    </div> --}}
    <table class="new-header">
      <tbody>
        <tr>
          <td rowspan="4"><img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo"></td>
          <td colspan="3" style="padding-bottom: 0px !important; border-bottom:none;"><h3>LEYTE V ELECTRIC COOPERATIVE, INC.</h3></td>
          <td rowspan="4"><img src="{{ public_path('images/iso.png') }}" alt="" class="img-iso"></td>
        </tr>

        <tr>
          <td colspan="3" style="text-align: center !important; border:none; padding-top:0px;">
            <span style="font-size: 13px">
              Brgy. San Pablo, Ormoc City, Leyte<br>
              Telephone Nos.: PLDT: (053) 839-3920 to 3921 / Globe: (053) 561-4466<br>
              Cellular Phone Nos. Calls Only: Smart: 0998-964-3804; Globe: 0917-836-3895<br>
              Website: www.leyeco-v.com.ph    eMail Address: info@leyeco-v.com.ph
            </span>
          </td>
        </tr>
        <tr>
            <td><span style="font-size: 13px">Document No.: FM-MSD-012</span></td>
            <td><span style="font-size: 13px">Revision No.: 001</span></td>
            <td><span style="font-size: 13px">Effectivity Date: </span</td>
        </tr>
        <tr>
          <td colspan="3"><h3>REQUEST FOR CHANGE METER</h3></td>
        </tr>
      </tbody>
    </table>


  <div class="div" style="margin-top: 0px; margin-bottom: 0px; padding-bottom: 0px;">

    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 25px; padding-bottom: 0px;">
      <tbody>
        <tr><td>Control No. : {{$data->control_no}}</td></tr>
        <tr>
          <td>Account #: {{ substr($data->account_number, 0, 2) }}-{{ substr($data->account_number, 2, 4) }}-{{ substr($data->account_number, 6, 4) }}</td>
          <td>Old Meter #: {{$data->old_meter_no}}</td>
        </tr>
        <tr>
          <td>Name: {{$data->last_name.', '.$data->first_name}}</td>
          <td>C/O: {{$data->care_of}}</td>
        </tr>
        <tr><td>Contact #: {{$data->contact_no}}</td></tr>
        <tr><td>Address: {{$data->sitio.', '.$data->barangay->barangay_name.', '.$data->municipality->municipality_name}}</td></tr>
        <tr><td>Consumer Type: {{ $consumerType['name'] ?? 'Unknown Type'}}</td></tr>
        <tr><td>Meter OR #: {{$data->meter_or_number }}</td></tr>
        <tr><td>Remarks: {{$data->remarks}}</td></tr>
        <tr><td>Reference/Landmark: {{$data->location}}</td></tr>
      </tbody>
    </table>

    <table class="styled-table" style="font-size: 12px; width: 100%; margin-top: 15px; padding-bottom: 0px;">
      <tbody>
        <tr>
          <td class="text-center" style="font-size: 14px;">Received and processed by:</td>
          <td class="text-center" style="font-size: 14px;">Checked by</td>
          <td class="text-center" style="font-size: 14px;">Approved By:</td>
        </tr>
        <tr>
          <td class="text-center"></td>
          <td class="text-center"><img src="images/signatures/bernandino.GIF"  alt="" class="img-signature"></td>
          <td class="text-center"><img src="images/signatures/pastor.GIF"  alt="" class="img-signature"></td>
        </tr>
        <tr>
          <td class="text-center" style="text-decoration: underline;">E. MAÑACAP / N. PONIENTE</td>
          <td class="text-center" style="text-decoration: underline;">GHANDA R. BERNANDINO</td>
          <td class="text-center" style="text-decoration: underline;">ANA MARIA LOURDES M. PASTOR, MBM</td>
        </tr>
        <tr><td></td></tr>
        <tr>
          <td class="text-center">CWD ANALYST</td>
          <td class="text-center">MSD CHIEF</td>
          <td class="text-center">ISD MANAGER</td>
        </tr>
      </tbody>
    </table>

    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 25px; padding-bottom: 0px;">
      <tbody>
        <tr><td>Crew: {{$data->changeMeterRequestCrew ? $data->changeMeterRequestCrew->last_name.', '.$data->changeMeterRequestCrew->first_name : null}}</td></tr>
        <tr>
          <td>Action Taken:</td>
          <td>( ) Acted</td>
          <td>( ) Not Completed</td>
          <td>( ) Rejected</td>
        </tr>
      </tbody>
    </table>
    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 0px; padding-bottom: 10px;">
      <tbody>
        <tr><td>Remarks: {{$data->Remarks}}</td></tr>
        <tr><td>Last Reading: {{$data->LastRdg}}</td></tr>
        <tr>
          <td>New Meter #: {{$data->MeterNo}}</td>
          <td>LEYECO V Seal #: {{$data->SealNo}}</td>
          <td>ERC Seal #: {{$data->{'ERC Seal#'} }}</td>
        </tr>
        <tr>
          <td>Date Installed: {{$data->{'Date Installed'} ? date('F d, Y', strtotime($data->{'Date Installed'})) : ''}}</td>
          <td>Time: {{$data->KvaType}}</td>
        </tr>
        <tr><td>Kwh meter damage/cause: {{$data->Ownership }}</td></tr>
      </tbody>
    </table>
    <hr class="black">
    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 10px; padding-bottom: 0px;">
      <tbody>
        <tr><td>I acknowledge having received the above service.</td></tr>
        <tr><td>SIGNATURE OVER PRINTED NAME: ___________________________</td></tr>
        <tr><td>Relationship Account Holder: _______________________________</td></tr>
      </tbody>
    </table>
  </div>
  <p style="text-align:right; margin-top: 70px">Date and Time Generated: {{ date('m/d/Y h:i:a') }}</p>
</body>
</html>