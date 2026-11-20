<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="https://fonts.cdnfonts.com/css/franklin-gothic" rel="stylesheet">
  <title>CM Report</title>
  <style>
    header{
      font-family: 'Franklin Gothic', sans-serif;
      position: relative;
      padding-top: 0px !important;
      margin-top: 0px !important;
    }
    .heading {
      text-align: center;
      margin: 1px 10px 10px 10px !important; /* Top, right, bottom, left */
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
        background-color: #00ddb1;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 3px 4px;
    }
    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
        
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #00ddb1;
    }

    .styled-table, th, td {
      border: 1px solid rgb(0, 0, 0);
    }

    .signature-table {
        border-collapse: collapse;
        border: none !important;
        width: 100%;
    }

    .signature-table th,
    .signature-table td {
        border: none !important;
        padding: 0;
        text-align: center;
    }

    .signature-header {
        width: 33.33%;
        padding-bottom: 5px !important;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
    }

    .signature-image {
        width: 33.33%;
        height: 60px;
        text-align: center;
        vertical-align: bottom;
    }

    .img-signature {
        width: 150px;
        height: 60px;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .signature-image-cm {
        width: 100%;
        height: 60px;
    }

    .signature-name {
        width: 33.33%;
        text-align: center;
        text-decoration: underline;
        font-weight: bold;
        padding-top: 0 !important;
        text-transform: uppercase;
        font-size: 12px;
    }

    .signature-position {
        width: 33.33%;
        text-align: center;
        padding-top: 2px !important;
        text-transform: uppercase;
        font-size: 12px;
    }

    /* Add these CSS rules for the footer */
    @page {
      margin: 15px 20px 15px 20px; /* top, right, bottom, left */
      padding: 0px 0px 0px 0px; /* top, right, bottom, left */
    }

    body {
      margin-bottom: 30px;
    }

    .page-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      /* height: 30px; */
      text-align: right;
      font-size: 10px;
      color: #666;
      background-color: white;
      border-top: 1px solid #ddd;
    }

    .page-number:after {
      content: "Page " counter(page) " of " counter(pages);
    }
  </style>
</head>
<body>
  <header>
    <img src="{{ public_path('images/logo.png') }}" alt="" class="img-logo">
    <img src="{{ public_path('images/iso_2025.jpg') }}" alt="" class="img-iso">
    <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
    <p class="sub-heading">
      Brgy. San Pablo, Ormoc City, Leyte<br>
      CUSTOMER WELFARE DESK (ISD)
    </p>
  </header>
  <hr class="blue">
  <hr class="yellow">
  <div class="div" style="margin-top: 10px;">
    <h4 class="text-center">CHANGE METER REQUEST REPORT</h4>
    <h4 class="text-center">From: {{ date('m/d/Y', strtotime(request('date_from'))) }} to {{ date('m/d/Y', strtotime(request('date_to'))) }}</h4>
  </div>
  
  <!-- Application Status: {{ request('app_status') == 0 ? 'ALL' : (request('app_status') == 1 ? 'INSTALLED' : (request('app_status') == 2 ? 'UNACTED' : (request('app_status') == 3 ? 'ACTED' : (request('app_status') == 4 ? 'NOT COMPLETED' : '')))) }} -->
  <!-- <div class="div" style="margin-top: 20px; margin-bottom: 0px; padding-bottom: 0px;">
    <h3 class="text-center" style="padding-bottom: 0px; margin-bottom: 0px;">
      CHANGE METER REQUEST REPORT
    </h3>
  </div> -->

  <div class="text-align" style="margin-top: 0px; padding-top: 0px;">
    <table class="styled-table" style="font-size: 11px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <thead>
        <tr>
          <th>Date</th>
          <th>Account No.</th>
          <th>Name</th>
          <th>Address</th>
          <th>Meter No.</th>
          <th>Requested By</th>
          <th>Damage Cause</th>
          <th>Contact No.</th>
          <th>Landmark</th>
          <th>Date Accepted</th>
        </tr>
      </thead>
      <tbody>
          @foreach($datas as $index => $data)
          <tr>
            <td>{{ $data->created_at ? date('m/d/Y', strtotime($data->created_at)) : '' }}</td>
            <td>{{ substr($data->account_number, 0, 2) }}-{{ substr($data->account_number, 2, 4) }}-{{ substr($data->account_number, 6, 4) }}</td>
            <td>{{$data->last_name.', '.$data->first_name}}</td>
            <td>{{$data->sitio.', '.$data->barangay->barangay_name.', '. $data->municipality->municipality_name}}</td>
            <td>{{$data->meter_no}}</td>
            <td>{{$data->requested_by}}</td>
            <td>{{$data->reason_for_change_meter}}</td>
            <td>{{$data->contact_no}}</td>
            <td>{{$data->landmark}}</td>
            <td>{{$data->changeMeterRequest ? ($data->changeMeterRequest->created_at ? date('m/d/Y', strtotime($data->changeMeterRequest->created_at)) : '') : ''}}</td>
          </tr>
          @endforeach
      </tbody>
    </table>
  </div>

  @if (request('contractor_id') != null)
    <div class="text-align" style="margin-top: 0px; padding-top: 50px;">
      <table class="signature-table" style="font-size: 11px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
        <tbody>
          <tr>
            <th class="signature-header">Prepared By:</th>
            <th class="signature-header"></th>
            <th class="signature-header"></th>
          </tr>
          <tr>
            <th class="signature-name">&nbsp;&nbsp;{{ $contractor->contractor_team_leader_full_name }}&nbsp;&nbsp;</th>
          </tr>
          <tr>
            <th class="signature-position">Contractor</th>
          </tr>
        </tbody>
      </table>
    </div>
  @endif

  <div class="page-footer">
    <p>Note: This is a system generated report | Date and Time Generated: {{ date('m/d/Y h:i:a') }} | <span class="page-number"></span></p>
  </div>
  

</body>
</html>