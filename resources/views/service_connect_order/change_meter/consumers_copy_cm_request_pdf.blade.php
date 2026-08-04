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
              Cellular Phone Nos. Calls Only: Globe: 0917-683-7230<br>
              Website: www.leyeco-v.com.ph    eMail Address: info@leyeco-v.com.ph
            </span>
          </td>
        </tr>
        <tr>
            <td><span style="font-size: 13px">Document No.: ISD-FM-0014</span></td>
            <td><span style="font-size: 13px">Revision No.: </span></td>
            <td><span style="font-size: 13px">Effectivity Date: 10/24/2025</span></td>
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
        <tr>
          <td>Address: {{$data->sitio.', '.$data->barangay->barangay_name.', '.$data->municipality->municipality_name}}</td>
          <td>Latitude: {{$data->latitude}}</td>
        </tr>
        <tr>
          <td>Consumer Type: {{ $data->consumer_type ?? 'Unknown Type'}}</td>
          <td>Longtitude: {{$data->longitude}}</td>
        </tr>
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
          <td class="text-center"><img src="images/signatures/elma_manacap.GIF"  alt="" class="img-signature" style="opacity: 0.7"></td>
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
          <td>Action Taken: {{$data->status == 1 ? 'ACTED - NOT COMPLETED' : ($data->status == 2 ? 'ACTED - COMPLETED' : ($data->status == 3 ? 'DISPATCHED' : 'UNACTED')) }}</td>
        </tr>
      </tbody>
    </table>
    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <tbody>
        <tr><td>Crew Remarks: {{$data->crew_remarks}}</td></tr>
        <tr><td>Last Reading: {{$data->last_reading}}</td></tr>
        <tr>
          <td>New Meter #: {{$data->assignedMeter->serial_number}}</td>
          <td>LEYECO V Seal #: {{$data->assignedMeter->leyeco_seal_number}}</td>
          <td>ERC Seal #: {{$data->assignedMeter->erc_seal_number}}</td>
        </tr>
        <tr>
          <td>Date Installed: {{$data->date_time_acted ? date('F d, Y', strtotime($data->date_time_acted)) : ''}}</td>
          <td>Time: {{$data->date_time_acted ? date('h:i A', strtotime($data->date_time_acted)) : ''}}</td>
        </tr>
      </tbody>
    </table>
    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <tbody>
        <tr><td>Kwh meter damage/cause: {{$data->damage_cause }}</td></tr>
      </tbody>
    </table>
    <hr class="black">
    <table class="styled-table" style="font-size: 14px; width: 100%; padding-top: 0px; padding-bottom: 0px;">
      <tbody>
        <tr><td><div style="position: relative; z-index: 1; padding-bottom: 40px;">I acknowledge having received the above service.</div></td></tr>
        <tr>
          <td style="position: relative; min-height: 60px;">
            @if(isset($data->signatures) && count($data->signatures) > 0)
              <img src="{{ $data->signatures[0]->signature_image_url }}" 
                   alt="Consumer Signature" 
                   style="max-height: 60px; max-width: 200px; position: absolute; top: -35px; left: 250px; z-index: 1; opacity: 0.8;">
            @endif
            <div style="position: relative; z-index: 2;">
              SIGNATURE OVER PRINTED NAME: <span style="text-decoration: underline; font-weight: bold;">{{$data->signatures[0]->signatory_name ?? '__________'}}</span>
            </div>
          </td>
        </tr>
        <tr><td>Relationship Account Holder: <span style="text-decoration: underline; font-weight: bold;">{{$data->signatures[0]->signatory_position ?? '__________'}}</span></td></tr>
      </tbody>
    </table>
  </div>
  <p style="text-align:right; margin-top: 40px">Date and Time Generated: {{ date('m/d/Y h:i:a') }}</p>
</body>
</html>