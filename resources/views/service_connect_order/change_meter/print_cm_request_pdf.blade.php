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
    hr, .blue{
      padding: 0x;
      margin: 0px;
      border: 2px solid blue;
    }
    hr, .yellow{
      padding: 0x;
      margin: 0px;
      border: 2px solid yellow;
    }
    hr, .black{
      padding: 0x;
      margin: 0px;
      border: 1px solid black;
    }
    .img-logo{
      position: absolute;
      top: 25px;
      left: 20px;
      height: 80px;
      width: 80px;
    }
    .img-iso{
      position: absolute;
      top: 25px;
      right: 10px;
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
  </style>
</head>
<body>
  <header>
    <img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo">
    <img src="{{ public_path('images/iso.png') }}" alt="" class="img-iso">
    <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
    <p class="sub-heading">
      (LEYECO V)<br>
      Brgy. San Pablo, Ormoc City, Leyte
    </p>
  </header>
  <hr class="blue">
  <hr class="yellow">

  <div class="div" style="margin-top: 20px; margin-bottom: 0px; padding-bottom: 0px;">
    <h2 class="text-center" style="padding-bottom: 0px; margin-bottom: 10px;">
    REQUEST FOR CHANGE METER
    </h2>

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
        <tr><td>Consumer Type: {{$data->ConsumerType}}</td></tr>
        <tr><td>Meter OR #: {{$data->meter_or_number }}</td></tr>
        <tr><td>Remarks: {{$data->remarks}}</td></tr>
        <tr><td>Reference/Landmark: {{$data->location}}</td></tr>
      </tbody>
    </table>

    <table class="styled-table" style="font-size: 12px; width: 100%; margin-top: 15px; padding-bottom: 0px;">
      <tbody>
        <tr>
          <td class="text-center" style="font-size: 14px;" >Prepared By:</td>
          <td class="text-center" style="font-size: 14px;">Recommending Approval</td>
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
        <tr><td>Crew: {{$data->Crew}}</td></tr>
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
  <p style="text-align:right; margin-top: 80px">Date and Time Generated: {{ date('m/d/Y h:i:a') }}</p>
</body>
</html>