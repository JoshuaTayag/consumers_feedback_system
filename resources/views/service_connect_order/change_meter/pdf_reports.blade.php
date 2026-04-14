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
          <th rowspan="2">No.</th>
          <th rowspan="2">Control No.</th>
          <th rowspan="2">Name</th>
          <th rowspan="2">Address</th>
          <th colspan="3">OLD KWH METER</th>
          <th colspan="3">NEW KWH METER</th>
          <th rowspan="2">Date Installed</th>
          <th rowspan="2">Signature</th>
        </tr>
        <tr>
          <th>Account #</th>
          <th>Meter #</th>
          <th>Last Reading</th>
          <th>Meter #</th>
          <th>ERC Seal</th>
          <th>Leyeco 5 Seal</th>
        </tr>
      </thead>
      <tbody>
          @foreach($datas as $index => $data)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{$data->control_no}}</td>
            <td>{{$data->last_name.', '.$data->first_name}}</td>
            <td>{{$data->sitio.', '.$data->barangay->barangay_name.', '. $data->municipality->municipality_name}}</td>
            <td>{{ substr($data->account_number, 0, 2) }}-{{ substr($data->account_number, 2, 4) }}-{{ substr($data->account_number, 6, 4) }}</td>
            <td>{{$data->old_meter_no}}</td>
            <td>{{$data->last_reading}}</td>
            <td>{{$data->new_meter_no}}</td>
            <td>{{$data->assignedMeter ? $data->assignedMeter->erc_seal_number : 'N/A'}}</td>
            <td>{{$data->assignedMeter ? $data->assignedMeter->leyeco_seal_number : 'N/A'}}</td>
            <td>{{$data->postedMeterHistory ? $data->postedMeterHistory->date_installed : 'N/A'}}</td>
            <td class="signature-cell">
              @if(isset($signatures[$data->id]) && $signatures[$data->id]->isNotEmpty())
                @php
                  // Find consumer signature first, or use the first available signature
                  $displaySignature = null;
                  foreach($signatures[$data->id] as $signature) {
                    if(isset($signature['signature_type']) && $signature['signature_type'] === 'consumer' && !empty($signature['signature_data'])) {
                      $displaySignature = $signature;
                      break;
                    }
                  }
                  // If no consumer signature, use first available
                  if(!$displaySignature) {
                    foreach($signatures[$data->id] as $signature) {
                      if(!empty($signature['signature_data'])) {
                        $displaySignature = $signature;
                        break;
                      }
                    }
                  }
                @endphp
                
                @if($displaySignature && !empty($displaySignature['signature_data']))
                  @php
                    // Clean up the base64 data
                    $signatureData = $displaySignature['signature_data'];
                    // Remove data URI prefix if present
                    if (strpos($signatureData, 'data:image/') === 0) {
                      $signatureData = substr($signatureData, strpos($signatureData, ',') + 1);
                    }
                    // Clean any whitespace
                    $signatureData = str_replace([' ', '\n', '\r'], '', $signatureData);
                  @endphp
                  
                  <img src="data:image/png;base64,{{ $signatureData }}" 
                      alt="Signature" 
                      class="signature-image"
                      style="max-height: 40px; max-width: 50px; display: block; margin: 0 auto;">
                @else
                  <small>No Signature</small>
                @endif
              @else
                <small>No Signature</small>
              @endif
            </td>
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
            <th class="signature-name">&nbsp;&nbsp;{{ $contractorName }}&nbsp;&nbsp;</th>
          </tr>
          <tr>
            <th class="signature-position">Contractor</th>
          </tr>
        </tbody>
      </table>
    </div>
  @endif

  <div class="text-align" style="margin-top: 0px; padding-top: 50px;">
    <table class="signature-table" style="font-size: 11px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <tbody>
        <tr>
          <th class="signature-header">Checked By:</th>
          <th class="signature-header">Noted By:</th>
          <th class="signature-header">Approved By:</th>
        </tr>
        <tr>
          <th class="signature-name">&nbsp;&nbsp;NIÑO REY C. PONIENTE / ELMA G. MAÑACAP&nbsp;&nbsp;</th>
          <th class="signature-name">&nbsp;&nbsp;GHANDA R. BERNANDINO, DPA&nbsp;&nbsp;</th>
          <th class="signature-name">&nbsp;&nbsp;ANA MARIA LOURDES M. PASTOR, MBM&nbsp;&nbsp;</th>
        </tr>
        <tr>
          <th class="signature-position">CWD Analyst</th>
          <th class="signature-position">MSD Chief</th>
          <th class="signature-position">ISD Manager</th>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="page-footer">
    <p>Note: This is a system generated report | Date and Time Generated: {{ date('m/d/Y h:i:a') }} | <span class="page-number"></span></p>
  </div>
  

</body>
</html>