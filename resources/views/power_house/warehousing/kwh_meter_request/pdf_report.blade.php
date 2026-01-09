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
    .img-signature{
      position: absolute;
      top: 20px;
      right: 20%;
      height: 80px;
      width: 120px;
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
    }
    
    .signature-table th,
    .signature-table td {
      border: none !important;
    }
    
    .signature-header {
      width: 33.33%; 
      padding-bottom: 70px; 
      border: none !important;
      text-align: center;
    }
    
    .signature-name {
      text-decoration: underline; 
      width: 33.33%; 
      border: none !important;
      text-align: center;
    }
    
    .signature-position {
      width: 33.33%; 
      border: none !important;
      text-align: center;
    }

    /* Add these CSS rules for the footer */
    @page {
      margin: 15px 20px 15px 20px; /* top, right, bottom, left */
    }

    .page-footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      /* height: 0px; */
      text-align: right;
      font-size: 10px;
      color: #666;
      /* padding: 10px; */
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
    <img src="{{ public_path('images/iso.png') }}" alt="" class="img-iso">
    <h2 class="heading">LEYTE V ELECTRIC COOPERATIVE, INC.</h2>
    <p class="sub-heading">
      Brgy. San Pablo, Ormoc City, Leyte<br>
      TECHNICAL SERVICES DEPARTMENT (TSD)
    </p>
  </header>
  <hr class="blue">
  <hr class="yellow">
  <div class="div" style="margin-top: 10px;">
    <h4 class="text-center">KWH-METER LIQUIDATION REPORT (CHANGE METER)</h4>
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
          <th>No.</th>
          <th>CM Request Control No.</th>
          <th>Name</th>
          <th>Address</th>
          <th>Date Installed</th>
          <th style="background-color: rgb(255, 103, 103)">Old Meter</th>
          <th>New Meter</th>
          <th>L5 Seal No</th>
          <th>ERC Seal No</th>
          <th>Remarks</th>
        </tr>
      </thead>
      <tbody>
          @foreach($datas as $index => $data)
          <tr style="background-color: {{ $data->changeMeterRequest ? 'white' : 'rgb(250, 220, 150)' }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $data->changeMeterRequest ? $data->changeMeterRequest->control_no : $data->kwhMeterRequest->control_no }}</td>
            <td>{{ $data->changeMeterRequest ? $data->changeMeterRequest->full_name : 'N/A' }}</td>
            <td>{{ $data->changeMeterRequest ? $data->changeMeterRequest->address : 'N/A' }}</td>
            <td>{{ $data->changeMeterRequest && $data->changeMeterRequest->date_time_acted ? date('m/d/Y', strtotime($data->changeMeterRequest->date_time_acted)) : 'N/A' }}</td>
            <td>{{ $data->changeMeterRequest ? $data->changeMeterRequest->old_meter_no : 'N/A' }}</td>
            <td>{{ $data->meter->serial_number }}</td>
            <td>{{ $data->meter->leyeco_seal_number }}</td>
            <td>{{ $data->meter->erc_seal_number }}</td>
            <td>{{ $data->changeMeterRequest ? ($data->changeMeterRequest->status == 2 ? 'INSTALLED' : 'UNINSTALLED') : 'N/A' }}</td>
          </tr>
          @endforeach
      </tbody>
    </table>
  </div>

  <div class="text-align" style="margin-top: 0px; padding-top: 80px;">
    <table class="signature-table" style="font-size: 13px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <tbody>
        <tr>
          <th class="signature-header">Prepared By:</th>
          <th class="signature-header">Checked By:</th>
          <th class="signature-header">Approved By:</th>
        </tr>
        <tr>
          <th class="signature-name">{{ $requesitioner->employee ? $requesitioner->employee->full_name : 'Please complete employee details' }}</th>
          <th class="signature-name">Genevieve J. Salgarino</th>
          <th class="signature-name">Engr. Ricardo R. Lequin, REE, RME</th>
        </tr>
        <tr>
          <th class="signature-position">{{ $requesitioner->employee ? $requesitioner->employee->position : 'Please complete employee details' }}</th>
          <th class="signature-position">MMS Head</th>
          <th class="signature-position">TSD Manager</th>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="page-footer">
    <p>Note: This is a system generated report | Date and Time Generated: {{ date('m/d/Y h:i:a') }} | <span class="page-number"></span></p>
  </div>
  

</body>
</html>